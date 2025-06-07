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
    public function deleteCost(string $uuid, string $uuid2)
    {
        Cost::where('uuid', $uuid)->delete();

        VovanDB::query("
            DELETE FROM costs
            WHERE uuid = '" . $uuid2 . "'"
        );

        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        $monthTotal = Cost::query()
            ->whereMonth('date', date('m'))
            ->whereYear('date', date('Y'))
            ->where('user_id', auth()->user()->id)
            ->sum('price');

        $monthData = VovanDB::select("
            SELECT * 
            FROM costs 
            WHERE 
            date >= '" . Carbon::now()->startOfMonth()->format('Y-m-d') . "'
            AND user_id = " . auth()->user()->id
        );

        $monthTotal2 = array_reduce($monthData, function ($acc, $i) {
            $acc += $i['price'];
            return $acc;
        }, 0);

        $todayTotal = Cost::query()
            ->where('date', Carbon::now()->toDateString())
            ->where('user_id', auth()->user()->id)
            ->sum('price');
            
        $todayData = VovanDB::select("
            SELECT * 
            FROM costs 
            WHERE date >= '" . Carbon::now()->format('Y-m-d') . "'
            AND user_id = " . auth()->user()->id
        );

        $todayTotal2 = array_reduce($todayData, function ($acc, $i) {
            $acc += $i['price'];
            return $acc;
        }, 0);

        $costs = Cost::query()
            ->where('user_id', auth()->user()->id)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->with('category')
            ->limit(30)
            ->get();

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

        $costs2 = array_slice($costsData, 0, 30);

        $costs2 = array_map(function ($item) use ($categories) {
            $item['date'] = Carbon::create($item['date'])->isoFormat('D MMM');
            $item['price'] = priceFormat($item['price']);
            $item['category'] = $categories[$item['category_id']];

            return $item;
        }, $costs2);

        return view('livewire.home')
            ->with([
                'costs' => $costs,
                'costs2' => $costs2,
                'month' => Carbon::now()->isoFormat('MMMM'),
                'monthTotal' => $monthTotal,
                'monthTotal2' => $monthTotal2,
                'todayTotal' => $todayTotal,
                'todayTotal2' => $todayTotal2,
            ]);
    }
}
