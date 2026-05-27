<?php

namespace App\Http\Controllers;

use App\Models\BloodStock;
use App\Models\Hospital;
use Illuminate\Http\Request;

class BloodStockController extends Controller
{
    // Simple CRUD for Hospital Admin
    public function index()
    {
        $hospital = Hospital::where('user_id', auth()->id())->firstOrFail();
        $stocks = $hospital->stocks;
        return view('hospital_admin.stocks.index', compact('hospital', 'stocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'blood_group' => 'required|string',
            'units_available' => 'required|integer|min:0'
        ]);

        $hospital = Hospital::where('user_id', auth()->id())->firstOrFail();

        BloodStock::updateOrCreate(
            ['hospital_id' => $hospital->id, 'blood_group' => $request->blood_group],
            ['units_available' => $request->units_available]
        );

        return back()->with('status', 'Stock updated');
    }
}
