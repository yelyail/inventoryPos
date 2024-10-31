<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class printController extends Controller
{
    public function inventoryReportPrint(Request $request){
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date'
        ], [
            'from_date.required' => 'Please provide a starting date.',
            'to_date.required' => 'Please provide an ending date.',
            'to_date.after_or_equal' => 'The ending date must be the same or later than the starting date.'
        ]);
    
        $fromDate = \Carbon\Carbon::parse($request->input('from_date'))->startOfDay();
        $toDate = \Carbon\Carbon::parse($request->input('to_date'))->endOfDay();
    
        $reportCounter = $request->session()->get('report_counter', 1);
        $request->session()->put('report_counter', $reportCounter + 1);
    }
}
