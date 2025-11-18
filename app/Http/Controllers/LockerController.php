<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use Illuminate\Http\Request;

class LockerController extends Controller
{
    public function index()
    {
        $lockers = Locker::all();
        return view('lockers.index', compact('lockers'));
    }

    public function create()
    {
        return view('lockers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'locker_number' => 'required',
            'status' => 'required'
        ]);

        Locker::create($request->all());

        return redirect()->route('lockers.index')->with('success', 'Locker berhasil ditambah.');
    }

    public function edit($id)
    {
        $locker = Locker::findOrFail($id);
        return view('lockers.edit', compact('locker'));
    }

    public function update(Request $request, $id)
    {
        $locker = Locker::findOrFail($id);
        $locker->update($request->all());

        return redirect()->route('lockers.index')->with('success', 'Locker diperbarui.');
    }

    public function destroy($id)
    {
        Locker::destroy($id);
        return redirect()->route('lockers.index')->with('success', 'Locker dihapus.');
    }
}
