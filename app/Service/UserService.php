<?php

namespace App\Service;

use App\Repository\User\UserRepo;
use Illuminate\Support\Facades\DB;


class UserService
{

    /**
     * @var $userRepository
     */
    protected $userRepository;

    /**
     * UserService constructor.
     *
     * @param UserRepo $userRepository
     */
    public function __construct(UserRepo $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     *  Displays the list of users
     *
     * @return array
     */

    /**
     * Stores the user details
     *
     * @param $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store($data)
    {
        $user = $this->userRepository->store($data);
        return $user;
    }

    /**Edits the user details
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        return $user;
    }

    /**Updates user details
     *
     * @param $id
     *
     * @param $data
     *
     * @return bool
     */
    public function update($id, $data)
    {
        $roles = $data['roles'];
        $user = $this->userRepository->update($id, $data);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        foreach ($roles as $role) {
            DB::table('model_has_roles')->insert([
                'role_id' => $role,
                'model_type' => 'App\Models\User',
                'model_id' => $id
            ]);

        }
        return $user;

    }

    /**Deletes user details
     *
     * @param $id
     *
     * @return int
     */
    public function destroy($id)
    {
        return $this->userRepository->delete($id);

    }

    public function getUsers($params)
    {
        $where = [];
        $orWhere = [];
        if (isset($params['search'])) {
            $search = $params['search'];
            $where = [['name', 'like', "%{$search}%"]];
            $orWhere = [['email', 'like', "%{$search}%"]];
        }
        $select = ['*'];
        $orderBy = '';
        $order = 1 ? isset($params['order']) and isset($params['sort']) : 0;
        if ($order) {
            $orderBy = [$params['order'], $params['sort']];
        }
        $skip = '';
        $take = '10';

        if (isset($params['page'])) {
            $skip = ($params['page'] - 1) * $take;
        }

        return $this->userRepository->getUsers($select, $where, $orWhere, $orderBy, $skip, $take);
    }
}
