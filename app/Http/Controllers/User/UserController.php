<?php

namespace App\Http\Controllers\User;

use App\helper\RolePermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPUnit\Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $req)
    {
        try {
            $total = intval(ceil(count(User::all()) / 10));
            $result = $this->userService->getUsers($req->all());
            return view('User.UserRetrieve')->with('result', $result)->with('total', $total)->with('status', 'Search Results');
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }


    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $roles = Role::all();
            return view('User.UserForm', compact('roles'));
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    /**
     * Store a newly created role in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $req)
    {
        try {

            DB::beginTransaction();
            $user = $this->userService->store([
                "name" => $req->name,
                "email" => $req->email,
                "password" => bcrypt($req->password)
            ]);

            $roles = $req->roles;
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
            DB::commit();
            return redirect()->route('Users')->with('status', 'New User Created');
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }


    /**
     * Show the form for editing the specified user.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try {
            DB::beginTransaction();
            $user_id = $this->userService->edit($id);
            $model_roles = DB::table('model_has_roles')->where('model_id', $id)->get();
            $selected_roles = [];
            $roles = Role::all();
            foreach ($model_roles as $role) {
                $selected_roles[] = $role->role_id;
            }
            return view('User.UserForm', compact('user_id', 'selected_roles', 'roles'));
            DB::commit();
        } catch (\Exception $exception) {
            Log::error($exception);
        }

    }

    /**
     * Update the specified user in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $req, $id)
    {

        try {
            DB::beginTransaction();
            $this->userService->update($id, $req->all());
            DB::commit();
            return redirect()->route('Users')->with('status', 'User Details Updated');
        } catch (\Exception $exception) {
            Log::error($exception);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->userService->destroy($id);
            DB::commit();
            return redirect()->route('user.index');
        } catch (\Exception $exception) {

            Log::error($exception);
        }
    }
}
