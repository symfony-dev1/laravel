<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $tags = Tag::sortable();
        $search = $request->search;
        if ($search && $search) {
            $tags = $tags->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        $tags = $tags->paginate(10);
        return view("tags/index", compact("tags"));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                "tag_title" => "required",
            ]
        );


        if (empty($request->slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->tag_title)));
        } else {
            $slug = $request->slug;
        }

        if ($request->edit_id && $request->edit_id != "") {
            $tag =  Tag::where("id", $request->edit_id)->first();
            $checkExist = Tag::where("slug", $slug)->where("id", "!=", $request->edit_id)->first();
        } else {
            $tag = new Tag;
            $checkExist = Tag::where("slug", $slug)->first();
        }


        // $checkExist = tag::where("slug", $slug)->first();
        if ($checkExist) {
            if ($request->edit_id && $request->edit_id != "") {
                $check = Tag::where("slug", "like",  $slug . "-%")->where("id", "!=", $request->edit_id)->first();
            } else {
                $check = Tag::where("slug", "like",  $slug . "-%")->first();
            }
            if ($check) {
                $string = $check->slug;
                $exploded = explode('-', $string);
                $no = end($exploded);
                $s = $slug . "-" . $no + 1;
            } else {
                $s = $slug . "-1";
            }
        } else {
            $s = $slug;
        }
        // dd($s);


        // if ($request->edit_id && $request->edit_id != "") {
        //     $id = $request->edit_id;
        //     $tag =  tag::where("id", $id)->first();
        // } else {
        //     $tag = new tag;
        // }

        $tag->title = $request->tag_title;
        $tag->description = $request->description;
        $tag->slug = $s; // strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->tag_title)));
        $tag->save();
        if ($request->edit_id && $request->edit_id != "") {
            return redirect()->back()->with("success", "Tag updated successfully");
        } else {
            return redirect()->back()->with("success", "Tag added successfully");
        }
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);
        return response()->json($tag);

        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Tag::where("id", $id)->delete();
    }

    public function bulkAction(Request $request)
    {
        $selectedIds =  $request->selectedIds;
        $action = $request->action;
        if ($action == "delete") {
            Tag::whereIn("id", $selectedIds)->delete();
        }
    }
}
