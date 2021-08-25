<?php

namespace App\Http\Controllers\User;

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
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('User.UserForm');
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $req)
    {

        try {
            DB::beginTransaction();
            $this->userService->store([
                "name" => $req->name,
                "email" => $req->email,
                "password" => bcrypt($req->password)
            ]);
            DB::commit();
            return redirect()->route('user.index')->with('status', 'New User Created');


        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try {
            DB::beginTransaction();
            $user_id = $this->userService->edit($id);
            return view('User.UserForm', compact('user_id'));
            DB::commit();
        } catch (\Exception $exception) {
            Log::error($exception);
        }

    }

    /**
     * Update the specified resource in storage.
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
            $this->userService->update([
                "name" => $req->name,
                "email" => $req->email,
                "password" => bcrypt($req->password)
            ], $id);
            DB::commit();
            return redirect()->route('user.index')->with('status', 'User Details Updated');
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
