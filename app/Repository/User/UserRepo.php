<?php

namespace App\Repository\User;

use App\Models\User;
use App\Repository\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;

class UserRepo extends Repository{
//    public function __construct()
//    {
//        $this->request=new Request();
//    }

    public function getModel():string
    {
        return User::class;
    }

}
