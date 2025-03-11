<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::paginate(15);
        return view('data_base.lab2.index', compact('workers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'second_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'position' => 'required|string|max:255',
        ]);

        Worker::create($request->all());
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $worker = Worker::findOrFail($id);

        $request->validate([
            'second_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'position' => 'required|string|max:255',
        ]);

        $worker->update($request->all());

        return redirect()->back();
    }

    public function destroy($id)
    {
        Worker::destroy($id);
        return redirect()->back();
    }
}
