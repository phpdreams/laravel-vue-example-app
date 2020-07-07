<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use App\Entries;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EntriesController extends Controller
{
    public function index()
    {
        $entries = Entries::where('user_id', auth()->user()->id)->orderBy('entry_time', 'desc')->simplePaginate(100);

        // some fun code to split our data out by day and calculate daily balances
        $days = [];
        $this_day = $day_balance = null;

        foreach($entries as $entry) {
            $entry_time = Carbon::parse($entry->entry_time);
            $day_text = $entry_time->format('D, j M');
            $day_text = $entry_time->isToday() ? 'Today' : $day_text;
            $day_text = $entry_time->isYesterday() ? 'Yesterday' : $day_text;

            if(is_null($this_day)) $this_day = $day_text;
            if($day_text != $this_day) {
                $days[$this_day]['title'] = $this_day;
                $days[$this_day]['balance'] = number_format($day_balance, 2);
                $day_balance = 0;
                $this_day = $day_text;
            }
            $day_balance += $entry->amount;
            $days[$this_day]['entries'][$entry->id] = $entry->toArray();
        }
        $days[$this_day]['title'] = $this_day;
        $days[$this_day]['balance'] = number_format($day_balance, 2);

        $days['morePages'] = $entries->hasMorePages();

        return $days;
    }

    public function store(Request $request)
    {
        $entry = Entries::create($request->all());
        return $entry;
    }

    public function update(Request $request, $id)
    {
        $entry = Entries::findOrFail($id);
        $entry->update($request->all());
        return $entry;
    }

    public function destroy($id)
    {
        $entry = Entries::findOrFail($id);
        $entry->delete();
        return '';
    }
}
