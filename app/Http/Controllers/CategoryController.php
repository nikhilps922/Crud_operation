<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorys = Category::orderBy('id', 'ASC')->get();
        return view('category.index',compact('categorys'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     //    dd($request->all());
    $this->validate($request, [
        'name' => 'required|min:3|max:50' ]);
        $category = new Category();
        $category->name = $request->name;
        $category->save();
        return response()->json(['message'=>'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
           // dd($request->all());
           $id = $request->id;
           $data = Category::find($id);
           return response()->json(['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:50'
            ]);
            $id = $request->id;
           $data = Category::find($id);
           $data->name = $request->name;
           $data->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
       // dd($request->all());
       Category::find($request->id)->delete();
       return response()->json(['message'=>'success']);
    }
}
