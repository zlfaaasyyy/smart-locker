<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Locker;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;

class DepositController extends Controller
{
    // Show list
    public function index()
    {
        $deposits = Deposit::with('locker')->get();
        return view('deposits.index', compact('deposits'));
    }

    // Form create
    public function create()
    {
        $lockers = Locker::where('status', 'available')->get();
        return view('deposits.create', compact('lockers'));
    }

    // Store
    public function store(Request $request, WhatsAppService $wa)
    {
        $request->validate([
            'user_name'        => 'required|string|min:3',
            'user_phone'       => 'required',
            'item_description' => 'required|min:3',
            'locker_id'        => 'required|exists:lockers,id'
        ]);

        $phone = normalizePhone($request->user_phone);
        $pickupCode = generatePickupCode();

        $deposit = Deposit::create([
            'user_name'        => $request->user_name,
            'user_phone'       => $phone,
            'item_description' => $request->item_description,
            'locker_id'        => $request->locker_id,
            'pickup_code'      => $pickupCode,
            'status'           => 'stored'
        ]);

        Locker::find($request->locker_id)->update([
            'status' => 'occupied'
        ]);

        // SEND WA
        $wa->sendMessage(
            $phone,
            "Halo {$deposit->user_name}! 👋\n".
            "Penitipan barang BERHASIL.\n\n".
            "📦 Loker: {$deposit->locker->locker_number}\n".
            "🔐 Kode Ambil: {$deposit->pickup_code}\n\n".
            "Gunakan kode ini saat mengambil barang."
        );

        return redirect()->route('deposits.index')->with('success', 'Data penitipan berhasil disimpan.');
    }

    // Edit
    public function edit($id)
    {
        $deposit = Deposit::findOrFail($id);
        $lockers = Locker::all();
        return view('deposits.edit', compact('deposit', 'lockers'));
    }

    // Update
    public function update(Request $request, $id)
    {
        $request->validate([
            'item_description' => 'required|min:3'
        ]);

        $deposit = Deposit::findOrFail($id);
        $deposit->update($request->all());

        return redirect()->route('deposits.index')->with('success', 'Data diperbarui.');
    }

    // Delete
    public function destroy($id)
    {
        $deposit = Deposit::findOrFail($id);

        Locker::find($deposit->locker_id)->update([
            'status' => 'available'
        ]);

        $deposit->delete();

        return redirect()->route('deposits.index')->with('success', 'Data dihapus.');
    }

    // Validate pickup code
    public function validatePickup(Request $request)
    {
        $request->validate([
            'pickup_code' => 'required|numeric'
        ]);

        $deposit = Deposit::where('pickup_code', $request->pickup_code)->first();

        if (!$deposit) {
            return back()->with('error', 'Kode salah atau tidak ditemukan.');
        }

        $deposit->update(['status' => 'picked_up']);
        
        Locker::find($deposit->locker_id)->update([
            'status' => 'available'
        ]);

        return back()->with('success', 'Barang berhasil diambil.');
    }
}
