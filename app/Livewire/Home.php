<?php

namespace App\Livewire;

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
        return view('livewire.home')
            ->with([
                'costs' => Cost::query()
                    ->where('user_id', auth()->user()->id)
                    ->orderByDesc('date')
                    ->orderByDesc('id')
                    ->with('category')
                    ->limit(30)
                    ->get(),
                'month' => Carbon::now()->isoFormat('MMMM'),
                'monthTotal' => Cost::query()
                    ->whereMonth('date', date('m'))
                    ->whereYear('date', date('Y'))
                    ->where('user_id', auth()->user()->id)
                    ->sum('price'),
                'todayTotal' => Cost::query()
                    ->where('date', Carbon::now()->toDateString())
                    ->where('user_id', auth()->user()->id)
                    ->sum('price')
            ]);
    }
}
