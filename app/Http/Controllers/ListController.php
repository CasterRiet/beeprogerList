<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TodoList;
use Illuminate\Support\Facades\Redirect;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        dd('hier?');
        $items = TodoList::all();
//        dd($items);
        return view('list')->with('items', $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = new TodoList;
        $item->item = $request->input('item');
        $item->save();

//        $response = array(
//            'item'    => $request->input('item'),
//        );

        $addeditem = TodoList::all()->last();

        return \Response::json($addeditem);
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
     * @param Request $request
     * @param  int $id
     * @return void
     */
    public function edit(Request $request, $id)
    {

    }

    public function rename(Request $request, $id)
    {
        $item = TodoList::findOrFail($id);
        $item->item = $request->input('item');
        $item->save();
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *store
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = TodoList::findOrFail($id);
        if ($item->crossed == 'true') {
            $item->crossed = 'false';
            $item->save();
            return \Response::json(false);
        } else {
            $item->crossed = 'true';
            $item->save();
            return \Response::json(true);
        }

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = TodoList::findOrFail($id);
        $item->delete();

        return $item;
    }
}
