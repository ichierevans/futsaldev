<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = User::where('email', 'NOT LIKE', '%admin%')
                        ->latest()
                        ->paginate(10);
        return view('admin.data_customer', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:6',
        ]);

        // Hash the password
        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.data.customer')
            ->with('success', 'Customer berhasil ditambahkan');
    }

    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($customer->id)],
            'phone' => 'required|string|max:20',
            'password' => 'nullable|min:6',
        ]);

        // Only update password if it's provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        $customer->update($validated);

        return redirect()->route('admin.data.customer')
            ->with('success', 'Data customer berhasil diperbarui');
    }

    public function destroy(User $customer)
    {
        $customer->delete();

        return redirect()->route('admin.data.customer')
            ->with('success', 'Customer berhasil dihapus');
    }
} 