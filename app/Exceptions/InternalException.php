<?php

namespace App\Exceptions;

use Exception;

class InternalException extends Exception
{
    protected $msgForUser;
    public function __construct(string $message = "",string $msgForUser = "系统错误" ,int $code = 400)
    {
        parent::__construct($message, $code);
        $this->msgForUser = $msgForUser;
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()){
            return response()->json(['msg'=>$this->msgForUser],$this->code);
        }
        return view('pages.error',['msg'=>$this->msgForUser]);
    }
}
