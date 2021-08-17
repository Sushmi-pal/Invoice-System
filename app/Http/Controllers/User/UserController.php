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


    public function index(){
        try {
            DB::beginTransaction();
            $result = $this->userService->index();
            if (count($result) == 3) {
                $sort = $result[0];
                $order_column = $result[1];
                $users = $result[2];
                if ($sort == 'asc') {
                    return view('User.UserRetrieve', ['users' => DB::table('users')->orderBy($order_column)->simplePaginate(5)]);
                } else {
                    return view('User.UserRetrieve', ['users' => DB::table('users')->orderByDesc($order_column)->simplePaginate(5)]);

                }
            } else {
                $search = $result;
                return view('User.UserRetrieve', compact('search'));
            }
        DB::commit();
        }
        catch(\Exception $exception){
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $req, $id)
    {

        try {
            DB::beginTransaction();
            $this->userService->update([
                "name" => $req->name,
                "email" => $req->email,
                "password"=>bcrypt($req->password)
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

//    public function GetUser(Request $request){
//        $data=$request->input('search');
//        $search=User::where('name', 'like', "%{$data}%")
//            ->orWhere('email', 'like', "%{$data}%")->get();
//        return view('User.UserRetrieve', compact('search'));
//    }
}
