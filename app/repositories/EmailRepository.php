<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 17:17
 */

namespace App\repositories;

use Mockery\Exception;

class EmailRepository implements BaseRepositoryInterface
{
    public function hasVerify($user)
    {
        if ($user->email_verified){
            throw new Exception('已经验证过邮箱了');
        }
        return false;
    }
}