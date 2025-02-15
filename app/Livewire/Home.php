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
    public function deleteCost(int $id)
    {
        Cost::destroy($id);

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
            WHERE user_id = " . auth()->user()->id
        );

        return view('livewire.home')
            ->with([
                'costs' => $costs,
                'month' => Carbon::now()->isoFormat('MMMM'),
                'monthTotal' => $monthTotal,
                'monthTotal2' => $monthTotal2,
                'todayTotal' => $todayTotal,
                'todayTotal2' => $todayTotal2,
            ]);
    }
}
