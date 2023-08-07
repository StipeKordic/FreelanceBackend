<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::all();
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
        }else {
            // Ako slika nije dostupna, postavite putanju do podrazumijevane slike
            $image_path = '../storage/app/images/izgled.png';
        }
        $review = 0;
        $num_reviews = 0;
        return Post::create([
            "user_id"=>$request->user_id,
            "service_id"=>$request->service_id,
            "price"=>$request->price,
            "description"=>$request->description,
            "review"=>$review,
            "num_reviews"=>$num_reviews,
            'image_path'=>$image_path
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Post::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'image_path'=>'nullable|image|mimes:jpeg,png,jpg'
        ]);
        $image_path = $post->image_path;
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $image_path = $image->store('images', 'public');
        }
        return $post->update(
            [
                "price"=>$request->price,
                "description"=>$request->description,
                "review"=>$request->review,
                "num_reviews"=>$request->num_reviews,
                'image_path'=>$image_path
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        return $post->delete();
    }
}
