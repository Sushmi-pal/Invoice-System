<?php

namespace App\Service;

use App\helper\Search;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repository\User\UserRepository;
use App\Repository\User\UserRepo;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


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
    public function index()
    {
        $users = $this->userRepository->all();
        $url = \Illuminate\Support\Facades\Request::fullUrl();
        $contains = Str::contains($url, 'sort');
        $sort = 'asc';
        $order_column = 'name';
        $parameters = [];
        if ($contains) {
            $sortby = explode('?', $url)[1];
            $params = explode('&', $sortby);

//            print_r($params);
            foreach ($params as $k => $v) {
                $a = explode('=', $v);
                array_push($parameters, $a[1]);
            }
            if (count($parameters) === 3) {
                $order_column = $parameters[0];
                $search = $parameters[1];
                $sort = $parameters[2];
            }
            if (count($parameters) == 2) {
                $order_column = $parameters[0];
                $sort = $parameters[1];
            }
        }
        $data = \Illuminate\Support\Facades\Request::input('search');
        if ($data != '' and $contains) {
            $se = Search::SearchParams($data, $sort, $order_column);
            return $se;
        }
        if ($data != '' and !$contains) {
            $search = Search::SearchParams($data, '', '');
            return $search;
        }
        return [$sort, $order_column, $users];
    }

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
        $user = $this->userRepository->update($data, $id);
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

        $search = '';
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
