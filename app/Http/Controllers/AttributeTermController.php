<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttributeTerm;
use Validator;
use Illuminate\Support\Str;

class AttributeTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
                "attribute_term_title" => "required",
            ]
        );

        if (empty($request->slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->attribute_term_title)));
        } else {
            $slug = $request->slug;
        }

        if ($request->edit_id && $request->edit_id != "") {
            $attribute_term =  AttributeTerm::where("id", $request->edit_id)->first();
            $checkExist = AttributeTerm::where("slug", $slug)->where("id", "!=", $request->edit_id)->where("attribute_id", $request->attribute_id)->first();
        } else {
            $attribute_term = new AttributeTerm;
            $checkExist = AttributeTerm::where("slug", $slug)->where("attribute_id", $request->attribute_id)->first();
        }


        // $checkExist = attribute::where("slug", $slug)->first();
        if ($checkExist) {
            if ($request->edit_id && $request->edit_id != "") {
                $check = AttributeTerm::where("slug", "like",  $slug . "-%")->where("id", "!=", $request->edit_id)->where("attribute_id", $request->attribute_id)->first();
            } else {
                $check = AttributeTerm::where("slug", "like",  $slug . "-%")->where("attribute_id", $request->attribute_id)->first();
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
        //     $attribute =  attribute::where("id", $id)->first();
        // } else {
        //     $attribute = new attribute;
        // }

        $attribute_term->name = $request->attribute_term_title;
        $attribute_term->short_description = $request->description;
        $attribute_term->attribute_id = $request->attribute_id;
        $attribute_term->slug = $s; // strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->attribute_term_title)));
        $attribute_term->save();
        if ($request->edit_id && $request->edit_id != "") {
            return redirect()->back()->with("success", "Attribute term updated successfully");
        } else {
            return redirect()->back()->with("success", "Attribute term added successfully");
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
        $attribute_term = AttributeTerm::find($id);
        return response()->json($attribute_term);
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
        AttributeTerm::where("id", $id)->delete();
    }

    public function bulkAction(Request $request)
    {
        $selectedIds =  $request->selectedIds;
        $action = $request->action;
        if ($action == "delete") {
            AttributeTerm::whereIn("id", $selectedIds)->delete();
        }
    }
}
