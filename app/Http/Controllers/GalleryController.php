<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries=Gallery::orderBy('id', 'DESC')->where('user_id', Session::get('user_id'))->get();
        return view('index', compact('galleries'));
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
        $val=$request->validate([
            'judul'=>"required",
            'deskripsi'=>"required",
            'photo'=>"required",
            // 'status'=>"required",
        ]);
        if ($request->hasFile('photo'))
        {
            $filePath=Storage::disk('public')->put('images/posts', request()->file('photo'));
            $val['photo']=$filePath;
        }
        $create=Gallery::create([
            'judul'=>$val['judul'],
            'deskripsi'=>$val['deskripsi'],
            'photo'=>$val['photo'],
            // 'status'=>$val['status'],
            'user_id'=>Session::get('user_id'),
        ]);
        if ($create)
        {
            session()->flash('Succes', 'Gallery Telah Dibuat');
            // session()->alert('Succes', 'Gallery Telah Dibuat');
            return redirect('/gallery');
        }
        return abort(500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        if ($request->hasFile('photo'))
        {
            $filePath=Storage::disk('public')->put('images/posts', request()->file('photo'));
            $gallery->judul=$request->judul;
            $gallery->deskripsi=$request->deskripsi;
            $gallery->photo=$filePath;
            // $gallery->status=$request->status;
            $gallery->user_id=Session::get('user_id');
            $gallery->save();
        }else {
            $gallery->judul=$request->judul;
            $gallery->deskripsi=$request->deskripsi;
            $gallery->photo=$request->photo;
            // $gallery->status=$request->status;
            $gallery->user_id=Session::get('user_id');
            $gallery->save();
        }
        session()->flash('Succes', 'Gallery Telah Diedit');
        return redirect('/gallery');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        $gallery->delete();
        // session()->alert('Succes', 'Gallery Telah Dibuat');
        session()->flash('Succes', 'Gallery Telah Dihapus');
        return redirect('/gallery');
    }
}
