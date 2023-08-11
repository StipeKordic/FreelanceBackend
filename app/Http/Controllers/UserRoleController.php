<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserRole::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return UserRole::create([
            'user_id'=>$request->user_id,
            'role_id'=>$request->role_id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return UserRole::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userRole = UserRole::where('id', $id);
        return $userRole->update(
            [
                'role_id'=>$request->role_id
            ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserRole  $userRole
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userRole = UserRole::where('id', $id);
        return $userRole->delete();
    }
    public function getRoleByUser($idUser)
    {
        $userRole = UserRole::where('user_id', $idUser)->get();
        return response()->json($userRole);
    }
}
