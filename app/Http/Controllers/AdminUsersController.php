<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\Http\Requests\UsersEditRequest;
use App\User;
use App\Role;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Requests;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::all();


        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::lists('name','id')->all();

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {
        //
        if(trim($request->password) == ''){
            $input = $request->except('password');
        }
        else{

            $input = $request->all();

            $input['password'] = bcrypt($request->password);
        }

//        return $request->all();

//        User::create($request->all());


        if($file = $request->file('photo_id')){
            $name = time() . $file->getClientOriginalName();

            $file->move('images', $name);

            $photo = Photo::create(['file'=>$name]);

            $input['photo_id'] = $photo->id;
        }

        User::create($input);

        Session::flash('created_user', 'The user has been created');

        return redirect('/admin/users');
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
        return view('admin.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);

        $roles = Role::lists('name', 'id')->all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersEditRequest $request, $id)
    {
        //find all users with id or fail
        $user = User::findOrFail($id);

        if(trim($request->password) == ''){
            $input = $request->except('password');
        }
        else{

            $input = $request->all();

            //Hashed password
            $input['password'] = bcrypt($request->password);
        }


        if($file = $request->file('photo_id')){

            $name = time() . $file->getClientOriginalName();

            $file->move('images', $name);

            $photo = Photo::create(['file' => $name]);
            $input['photo_id'] = $photo->id;
        }

        $user->update($input);

        return redirect('/admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //this will delete a user but dont delete photo into photo table.

//       $user = User::findOrFail($id);
//
//       //This will delete delete image from directory
//       unlink(public_path() . $user->photo->file);
//
//       $user->delete();

        $user = User::findOrFail($id); //this will find user with specific id

        if($user->photo){ //if user have photo
            $photo = Photo::findOrFail($user->photo->id); //find photo of user which have profile photo
            unlink(public_path() . $user->photo->file); //then unlink and concatenate with name of user photo
            $photo->delete(); //then delete it, this will delete user into user table and will delete photo into photo table.
        }

        $user->delete(); //if user dont have any profile photo this will deleting a user without any photo.

       Session::flash('deleted_user', 'The user has been deleted');

       return  redirect('/admin/users');
    }
}
