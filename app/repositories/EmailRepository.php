<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 17:17
 */

namespace App\repositories;

use App\Exceptions\InvalidRequestException;


class EmailRepository implements BaseRepositoryInterface
{
    /**
     * hasVerify
     * @throws InvalidRequestException
     * @param  [type]  [description]
     * @return [type]  [description]
     * @date 2018/7/24
     */
    public function hasVerify($user)
    {
        if ($user->email_verified){
            throw new InvalidRequestException('已经验证过邮箱了');
        }
        return false;
    }
}