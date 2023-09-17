<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::withCount('posts')->get();
        return response()->json($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image_path'=>'required|image|mimes:jpeg,png,jpg'
        ]);
        $image_path = null;
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $image_path = $image->store('images', 'public');
        }

        return Service::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'short_description'=>$request->short_description,
            'image_path'=>$image_path
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Service::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'image_path'=>'nullable|image|mimes:jpeg,png,jpg',
            'name'=>'nullable',
            'description'=>'nullable',
            'short_description'=>'nullable',
        ]);
        $image_path = $service->image_path;
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $image_path = $image->store('images', 'public');
        }
        return $service->update(
            [
                'name'=>$request->name,
                'description'=>$request->description,
                'short_description'=>$request->short_description,
                'image_path'=>$image_path
            ]);
    }
    public function updateImage(Request $request, Service $service)
    {
        $validated = $request->validate([
            'image_path'=>'required|image|mimes:jpeg,png,jpg'
        ]);
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $image_path = $image->store('images', 'public');
        }

        return $service->update(
            [
                'image_path'=>$image_path
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::where('service_id', $id);
        $post->delete();
        $service = Service::where('id', $id);
        return $service->delete();
    }
}
