<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 17:52
 */

namespace App\repositories;


use App\Models\User;

class UserRepository
{
    public function getUser($type,$data)
    {
        switch ($type){
            case 'id':
                $user = User::find($data);
                    break;
            case 'name':
                $user = User::query()->where('name',$data)->first();
                break;
            case 'email':
                $user = User::query()->where('email',$data)->first();
                break;
        }
        return $user;
    }
}