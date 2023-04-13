<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;
use Validator;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $attributes = Attribute::sortable();

        $search = $request->search;
        if ($search && $search != "") {
            $attributes = $attributes->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')->orWhere('short_description', 'like', '%' . $search . '%');
            });
        }
        $attributes = $attributes->paginate(10);
        return view("attributes/index", compact("attributes"));
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
                "attribute_title" => "required",
            ]
        );


        if (empty($request->slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->attribute_title)));
        } else {
            $slug = $request->slug;
        }

        if ($request->edit_id && $request->edit_id != "") {
            $attribute =  Attribute::where("id", $request->edit_id)->first();
            $checkExist = Attribute::where("slug", $slug)->where("id", "!=", $request->edit_id)->first();
        } else {
            $attribute = new Attribute;
            $checkExist = Attribute::where("slug", $slug)->first();
        }


        // $checkExist = attribute::where("slug", $slug)->first();
        if ($checkExist) {
            if ($request->edit_id && $request->edit_id != "") {
                $check = Attribute::where("slug", "like",  $slug . "-%")->where("id", "!=", $request->edit_id)->first();
            } else {
                $check = Attribute::where("slug", "like",  $slug . "-%")->first();
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

        $attribute->name = $request->attribute_title;
        $attribute->short_description = $request->description;
        $attribute->slug = $s; // strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->attribute_title)));
        $attribute->save();
        if ($request->edit_id && $request->edit_id != "") {
            return redirect()->back()->with("success", "Attribute updated successfully");
        } else {
            return redirect()->back()->with("success", "Attribute added successfully");
        }
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
        $attribute = Attribute::where('id', $id)->first();

        // $terms = $attribute->terms()->paginate(10);


        $terms =  $attribute->terms()->sortable();

        $search = $request->search;
        if ($search && $search != "") {
            $terms = $terms->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')->orWhere('short_description', 'like', '%' . $search . '%');
            });
        }
        $terms = $terms->paginate(10);
        return view("attribute_terms/index", compact("attribute", "terms"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attribute = Attribute::find($id);
        return response()->json($attribute);

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

    public function bulkAction(Request $request)
    {
        $selectedIds =  $request->selectedIds;
        $action = $request->action;
        if ($action == "delete") {
            Attribute::whereIn("id", $selectedIds)->delete();
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
        Attribute::where("id", $id)->delete();
    }

    public  function getAttributeTerms($id)
    {
        $attribute = Attribute::with('terms')->where("id", $id)->first();
        $jsonArray = [];
        foreach ($attribute->terms as $term) {
            $id = $term->id;
            $name = $term->name;
            $arr = array($id => $name);
            $jsonArray += $arr;
        }
        $jsonArray["attribute_terms"] = $jsonArray;
        $jsonArray["attribute_name"] = $attribute->name;
        $jsonArray["attribute_id"] = $attribute->id;
        return response()->json($jsonArray);
    }
}
