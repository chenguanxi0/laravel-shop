<?php

namespace App\Http\Controllers;

use App\Notifications\EmailVerificationNotification;
use App\repositories\EmailRepository;
use App\repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;


class EmailVerificationController extends Controller
{
    protected $emailRepository;
    protected $userRepository;

    public function __construct(EmailRepository $emailRepository,UserRepository $userRepository)
    {
        $this->emailRepository = $emailRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * 验证 邮箱链接
     * @param  [type]  [description]
     * @return [type]  [description]
     * @date 2018/7/21
     */
    public function verify(Request $request)
    {

        $email = $request->email;
        $token = ($request->all())['amp;token'];
        //验证值是否存在
        if (!$email || !$token){
            throw new Exception('验证链接不正确');
        }
        //验证链接是否正确
        if (Cache::get('email_verify'.$email) != $token){
            throw new Exception('验证链接不正确或已过期');
        }
        //验证用户是否存在
        $user = $this->userRepository->getUser('email',$email);
        if (is_null($user)){
            throw new Exception('用户不存在');
        }
        //全都验证通过 清除缓存 更新email_verified字段
        Cache::forget('email_verify'.$email);
        $user->update(['email_verified'=>1]);

        return view('pages.success',['msg'=>'恭喜,邮箱验证通过!']);
    }

    /**
     * 发送邮件
     * @param  [type]  [description]
     * @return [type]  [description]
     * @date 2018/7/21
     */
    public function send(Request $request)
    {
        $user = $request->user();
        if (!$this->emailRepository->hasVerify($user)){
            $user->notify(new EmailVerificationNotification());
            return view('pages.success',['msg'=>'邮箱发送成功!']);
        }
    }
}
