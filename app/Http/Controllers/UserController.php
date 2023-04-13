<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Tag;
use Validator;
use App\Models\Attribute;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $users = User::sortable();
        $search = $request->search;
        if ($search && $search) {
            $users = $users->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        $users = $users->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $attributes =   $attributes = Attribute::all();

        $categories = Category::orderby("name", "asc")->get();
        $brands = Brand::orderby("name", "asc")->get();
        $tags = Tag::orderby("name", "asc")->get();

        return view('users.form', compact('categories', "brands", "tags", "attributes"));
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'user_name' => 'required',
                    'user_email' => 'required',
                ]
            );
            return redirect()->back()->with("success", "User created successfully."); //, '');
        } catch (\Exception $e) {
            return redirect()->back()->with("error", "Something went wrong!"); //, '');
        }
    }

    public function bulkAction(Request $request)
    {
        $selectedIds =  $request->selectedIds;
        $action = $request->action;
        if ($action == "delete") {
            User::whereIn("id", $selectedIds)->delete();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where("id", $id)->delete();
    }

    public function edit($id)
    {
        $attributes =   $attributes = Attribute::all();
        $user = User::with("categories", "tags", "brands")->where("id", $id)->first();
        $categories = Category::orderby("name", "asc")->get();
        $brands = Brand::orderby("name", "asc")->get();
        $tags = Tag::orderby("name", "asc")->get();
        return view('users.form', compact('categories', "brands", "tags", "attributes", "user"));
    }
    public function show($id)
    {
        $user = User::with("categories", "tags", "brands")->where("id", $id);
        return response()->json($user);
    }
}
