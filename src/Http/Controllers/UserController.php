<?php

namespace LuisRolesPermisos\LuisPermisos\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use LuisRolesPermisos\LuisPermisos\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('haveaccess', 'user.index');
        $users = User::with('roles')->orderBy('id', 'desc')->paginate(5);

        return view('LuisPermisos::user.index', compact('users'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', [$user, ['user.show', 'userown.show']]);
        $roles = Role::orderBy('name')->get();
        return view('LuisPermisos::user.show', compact('roles', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', [$user, ['user.edit', 'userown.edit']]);
        $roles = Role::orderBy('name')->get();
        return view('LuisPermisos::user.edit', compact('roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        $this->validate($request, [
            'name'          => 'required|max:50|unique:users,name,' . $user->id,
            'email'          => 'required|max:50|unique:users,email,' . $user->id,
        ]);
        $user->roles()->sync($request->get('roles'));
        return redirect()->route('user.index')->with('status_success', 'User Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('haveaccess', 'user.destroy');
        $user->delete();
        return redirect()->route('user.index')->with('status_success', 'User Removed Successfully.');
    }
}
