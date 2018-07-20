<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/20
 * Time: 9:49
 */
function route_class(){
    return str_replace('.','-',Route::currentRouteName());
}