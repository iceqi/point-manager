<?php
/**
 * Created by PhpStorm.
 * User: iceqi
 * Date: 2017/11/3
 * Time: 下午3:46
 */

namespace Iceqi\VipPointManager;

use Hanson\Vbot\Contact\Groups;
use Hanson\Vbot\Extension\AbstractMessageHandler;
use Hanson\Vbot\Message\Text;
use Hanson\Vbot\Support\Log;
use Illuminate\Support\Collection;

class VipPointManager extends AbstractMessageHandler
{

    public $role = 'user';
    private $message;
    private $configs;

    public $author = 'Iceqi';
    public $version = '1.0';
    public $name = 'vip_point_manager';
    public $zhName = '积分管理';
    public $operation_message_obj;
    private $_handle;



    public function handler(Collection $message)
    {
        $this->initMessage($message);
        $this->initRole();


    }

    public function initMessage($message)
    {

        $this->message = $message;
        $this->message['user_obj'] = [
            'group_name' => $message['from']['NickName'], //群名
            'group_id' => $message['from']['UserName'],// 群组id
            'nickname' => $message['sender']['NickName'], // 发消息的用户
            'real_name' => $message['sender']['RemarkName'], // 好友备注名称
        ];
    }

    private function initRole()
    {

        if (isset($this->config['groups'][$this->operation_message_obj['group_name']]) && is_array($this->config['groups'][$this->operation_message_obj['group_name']])) {
            if ($this->message['real_name'] == $this->config['groups'][$this->operation_message_obj['group_name']]['manager']) {
                $this->role = 'manager';
            }
        }

    }

    private function eventManager(){

       $this->_handle =  new Handler($this);
    }




    public function is_group()
    {
        if ($this->message['type'] === 'text' && $this->message['fromType'] === 'Group') {
            return true;
        }
    }

    /**
     * 注册拓展时的操作.
     */
    public function register()
    {
        $this->configs = vbot('config')->get('extension.' . $this->name);
    }

}