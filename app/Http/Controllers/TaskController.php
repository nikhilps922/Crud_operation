<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Task;
use App\Models\Category;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::leftjoin('categorys as taskCategory','taskCategory.id','task.category_id')->
        select('task.*','taskCategory.id as categoryId','taskCategory.name as categoryName')->orderBy('task_id', 'ASC')->get();

        $categorys = Category::all();
        return view('dashboard',compact('tasks','categorys'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    //    dd($request->all());
    $this->validate($request, [
        'category' => 'required|',
        'title' => 'required|min:3|max:50',
        'description' => 'required|min:3|max:50',
        'date' => 'required|'
        ]);
        $task = new Task();
        $task->category_id = $request->category;
        $task->title = $request->title;
        $task->description =$request->description;
        $task->date =$request->date;
        $task->save();
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
        $data = Task::find($id);

        return response()->json(['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:3|max:50',
            'description' => 'required|min:3|max:50',
            'date' => 'required|'
            ]);
            $id = $request->id;
           $data = Task::find($id);
           $data->title = $request->title;
           $data->description = $request->description;
           $data->date = $request->date;
           $data->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // dd($request->all());
        Task::find($request->id)->delete();
        return response()->json(['message'=>'success']);
    }
}
