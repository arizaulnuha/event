<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $event = Event::latest()->paginate(10);
        return view('event.index', compact('event'));
    }
    /**
* create
*
* @return void
*/
public function create()
{
    return view('event.create');
}


/**
* store
*
* @param  mixed $request
* @return void
*/


/**
* edit
*
* @param  mixed $blog
* @return void
*/
public function edit(Event $event)
{
    return view('event.edit', compact('event'));
}


/**
* update
*
* @param  mixed $request
* @param  mixed $blog
* @return void
*/
public function store(Request $request)
{
    $this->validate($request, [
        'image'     => 'required|image|mimes:png,jpg,jpeg',
        'nama'     => 'required',
        'lokasi'   => 'required'
    ]);

    //upload image
    $image = $request->file('image');
    $image->storeAs('public/events', $image->hashName());

    $event = Event::create([
        'image'     => $image->hashName(),
        'nama'     => $request->nama,
        'lokasi'   => $request->lokasi
    ]);

    if($event){
        //redirect dengan pesan sukses
        return redirect()->route('event.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }else{
        //redirect dengan pesan error
        return redirect()->route('event.index')->with(['error' => 'Data Gagal Disimpan!']);
    }
}

public function update(Request $request, Event $event)
{
    $this->validate($request, [
        'nama'     => 'required',
        'lokasi'   => 'required'
    ]);

    //get data Blog by ID
    $event = Event::findOrFail($event->id);

    if($request->file('image') == "") {

        $event->update([
            'nama'     => $request->nama,
            'lokasi'   => $request->lokasi
        ]);

    } else {

        //hapus old image
        Storage::disk('local')->delete('public/events/'.$event->image);

        //upload new image
        $image = $request->file('image');
        $image->storeAs('public/events', $image->hashName());

        $event->update([
            'image'     => $image->hashName(),
            'nama'     => $request->nama,
            'lokasi'   => $request->lokasi
        ]);

    }

    if($event){
        //redirect dengan pesan sukses
        return redirect()->route('event.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }else{
        //redirect dengan pesan error
        return redirect()->route('event.index')->with(['error' => 'Data Gagal Diupdate!']);
    }
}


}
