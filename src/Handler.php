<?php
/**
 * Created by PhpStorm.
 * User: iceqi
 * Date: 2017/11/7
 * Time: 下午5:15
 */

namespace Iceqi\VipPointManager;


class Handler
{

    private static $_events;


    public function __construct($event)
    {
        if(!self::$_events[$event->role]){
            self::$_events[$event->role] = (new ucfirst($event->role))($event) ;
        }

        return self::$_events;
    }

}