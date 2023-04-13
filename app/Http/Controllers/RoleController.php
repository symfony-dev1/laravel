<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('roles/index', ['roles' => $roles, 'permissions' => $permissions]);
    }
    public function store(Request $request)
    {
        $request->validate(
            [
                "role" => "required",
            ]
        );
        $role = Role::where("name", $request->role)->first();
        if (!$role) {
            $role = new Role;
        }
        $role->name = $request->role;
        $role->save();
        return redirect()->back()->with("success", "Role added successfully");
    }

    public function updateRoles(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        unset($data['submit']);
        if (Auth::user()->hasRole('admin')) {
            foreach ($data as $key => $value) {
                $role = Role::findByName($key);
                $role->syncPermissions([$value]);
            }
            return back()->with('success', 'Data Saved SuccessFully');
        } else {
            return back()->with('error', 'You don"t allow for this permission');
        }
    }
    public function destroy($id)
    {
        Role::where("id", $id)->delete();
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
                "role" => "required",
            ]
        );
        $role = Role::where("id", $id)->first();
        $role->name = $request->role;
        $role->save();
    }
}
