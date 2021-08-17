<?php

namespace App\Repository\User;

use App\Models\User;
use App\Repository\Repository;

/**
 * Class UserRepository
 * @package App\Repository\User
 */
class UserRepository
{

    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Returns list of users
     *
     * @return User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        $users = $this->user::all();
        return $users;
    }

    /**
     * Stores the user details
     *
     * @param $data
     *
     * @return mixed
     */
    public function store($data)
    {
        $user = $this->user::create($data);
        return $user;
    }

    /**
     * Fetch the user details of specified id
     *
     * @param $id
     *
     * @return mixed
     */
    public function edit($id)
    {
        $user = $this->user::findOrFail($id);
        return $user;
    }

    /**
     * Updates user details
     *
     * @param $data
     *
     * @param $id
     *
     * @return mixed
     */
    public function update($data, $id)
    {
        $user = $this->user::findOrFail($id);
        $user->fill($data);
        $user->save();
        return $user;
    }

    /**
     * Deletes the user of specified id
     *
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        $user = $this->user::findOrFail($id);
        $user->delete();
        return $user;
    }


}
