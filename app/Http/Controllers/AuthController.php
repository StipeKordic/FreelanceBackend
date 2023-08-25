<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Service;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email'=>'required|email',
            'password'=> 'required|confirmed',
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'image_path'=>'nullable|image|mimes:jpeg,png,jpg'
        ]);
        $image_path = null;
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $image_path = $image->store('images', 'public');
        }else {
            // Ako slika nije dostupna, postavite putanju do podrazumijevane slike
            $image_path = 'images/profilna.jpg';
        }

        $user = User::create(
            [
                'email'=>$request->email,
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'password'=>Hash::make($request->password),
                'image_path'=>$image_path
            ]
        );
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => 3
        ]);

        return $user;
    }
    public function index()
    {
        $users = User::with('userRole')
            ->withCount('posts')
            ->get();
        return response()->json($users);
    }
    public function updateUser(Request $request, User $user){
        $validated = $request->validate([
            'email'=>'nullable|email',
            'new_password'=> 'nullable|confirmed',
            'first_name'=>'nullable|string',
            'last_name'=>'nullable|string',
            'image_path'=>'nullable|image|mimes:jpeg,png,jpg',

        ]);
        if ($request->has('password')){
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
            $new_password = Hash::make($request->new_password);
        }else {
            $new_password = $user->password;
        }
        return $user->update(
            [
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'password'=>$new_password
            ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('pziToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }
    public function user(Request $request)
    {
        return Auth::user();
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response('Success');
    }

    public function destroy($id)
    {
        $userrole = UserRole::where('user_id', $id);
        $userrole->delete();
        $post = Post::where('user_id', $id);
        $post->delete();
        $user = User::where('id', $id);
        return $user->delete();
    }
}
