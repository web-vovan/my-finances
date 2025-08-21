<?php

namespace App\Livewire;

use App\Adapters\VovanDB;
use App\Models\Cost;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class Home extends Component
{
    #[On('delete-cost')]
    public function deleteCost(string $uuid)
    {
        VovanDB::query("
            DELETE FROM costs
            WHERE uuid = '" . $uuid . "'"
        );

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        $monthData = VovanDB::select("
            SELECT * 
            FROM costs 
            WHERE 
            date >= '" . Carbon::now()->startOfMonth()->format('Y-m-d') . "'
            AND user_id = " . auth()->user()->id
        );

        $monthTotal = array_reduce($monthData, function ($acc, $i) {
            $acc += $i['price'];
            return $acc;
        }, 0);

        $todayData = VovanDB::select("
            SELECT * 
            FROM costs 
            WHERE date >= '" . Carbon::now()->format('Y-m-d') . "'
            AND user_id = " . auth()->user()->id
        );

        $todayTotal = array_reduce($todayData, function ($acc, $i) {
            $acc += $i['price'];
            return $acc;
        }, 0);

        $costsData = VovanDB::select("
            SELECT *
            FROM costs
            WHERE user_id = " . auth()->user()->id . "
            ORDER BY date DESC"
        );

        usort($costsData, function ($a, $b) {
            if ($a['date'] == $b['date']) {
                return ($a['id'] > $b['id']) ? -1 : 1;
            }

            return 0;      
        });
        
        $categoryData = VovanDB::select("
            SELECT id, name
            FROM categories
        ");

        $categories = array_reduce($categoryData, function ($acc, $item) {
            $acc[$item['id']] = $item['name'];
            return $acc;
        }, []);

        $costs = array_slice($costsData, 0, 50);

        $costs = array_map(function ($item) use ($categories) {
            $item['date'] = Carbon::create($item['date'])->isoFormat('D MMM');
            $item['price'] = priceFormat($item['price']);
            $item['category'] = $categories[$item['category_id']];

            return $item;
        }, $costs);

        return view('livewire.home')
            ->with([
                'costs' => $costs,
                'month' => Carbon::now()->isoFormat('MMMM'),
                'monthTotal' => $monthTotal,
                'todayTotal' => $todayTotal,
            ]);
    }
}
