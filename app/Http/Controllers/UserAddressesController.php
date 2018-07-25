<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses;
        return view('user_addresses.index',compact('addresses'));
    }
    public function create()
    {
        $address = new UserAddress();
        return view('user_addresses.create_and_edit',compact('address'));
    }
    public function store(UserAddressRequest $request)
    {
        $addresses = $request->only([
            'province',
            'city',
            'district',
            'address',
            'zip',
            'contact_name',
            'contact_phone',
        ]);
        $request->user()->addresses()->create($addresses);
        return redirect()->route('user_addresses.index');
    }
}
