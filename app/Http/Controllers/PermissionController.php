<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                "permission" => "required",
            ]
        );
        $permission = Permission::where("name", $request->permission)->first();
        if (!$permission) {
            $permission = new Permission;
        }
        $permission->name = $request->permission;
        $permission->save();
        return redirect()->back()->with("success", "Permission added successfully");
    }
    public function destroy($id)
    {
        Permission::where("id", $id)->delete();
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                "permission" => "required",
            ]
        );
        $permission = Permission::where("id", $id)->first();
        $permission->name = $request->permission;
        $permission->save();
    }
}
