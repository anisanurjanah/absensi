<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    //create
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'date' => 'required',
            'reason' => 'required',
        ]);

        // $permission = new Permission();
        // $permission->user_id = $request->user()->id;
        // $permission->type = $request->type;
        // $permission->date_permission = $request->date;
        // $permission->reason = $request->reason;
        // $permission->is_approved = 0;

        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     $path = $image->storeAs('public/permissions', $image->hashName());
        //     Log::info('File stored at: ' . $path);
        //     $permission->image = $image->hashName();
        // }

        // $permission = Permission::create([
        //     'user_id' => $request->user()->id,
        //     'type' => $request->type,
        //     'date_permission' => $request->date,
        //     'reason' => $request->reason,
        //     'is_approved' => 0,
        //     'image' => $request->hasFile('image')
        //         ? tap($request->file('image'))->storeAs('public/permissions', $request->file('image')->hashName())->hashName()
        //         : null,
        // ]);

        // $permission->save();

        DB::table('permissions')->insert([
            'user_id' => $request->user()->id,
            'type' => $request->type,
            'date_permission' => $request->date,
            'reason' => $request->reason,
            'is_approved' => 0,
            'image' => $request->hasFile('image')
                ? tap($request->file('image'))->storeAs('public/permissions', $request->file('image')->hashName())->hashName()
                : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Permission created successfully'], 201);
    }
}
