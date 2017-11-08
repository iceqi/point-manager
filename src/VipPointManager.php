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

    public $author = 'Iceqi';

    public $version = '1.0';

    public $name = 'vip_point_manager';

    public $zhName = '积分管理';

    private static $array = [];

    private $configs;

    private $event_arr = [

        '积分变动'=>[['增加','减少']],
        '移除用户'=>[
            'user'
        ],
        '查询积分'=>[
            'user'
        ],
    ];
    private $_operation = [
        '+' => ['str' => '增加', 'remind' => '请继续保持哦'],
        '-' => ['str' => '减少', 'remind' => '请注意您的积分哦']
    ];

    public function handler(Collection $message)
    {
        if ($message['type'] === 'text' && $message['fromType'] === 'Group') {

            $group_name = $message['from']['NickName'];  //群名
            $nickname = $message['sender']['NickName']; // 发消息的用户
            $real_name = $message['sender']['RemarkName']; // 好友备注名称
            $group_id = $message['from']['UserName']; //群组id
            if (isset($this->config['groups'][$group_name]) && is_array($this->config['groups'][$group_name])) {
                if ($real_name == $this->config['groups'][$group_name]['manager']) {
                    $event_user = explode(' ', $message['pure']);
                    if (count($event_user) > 0) {
                        $event = $event_user[0];
                        if (in_array($event, $this->event_arr)) {
                            $operation = substr($event_user[1], -2, 1);
                            $point = substr($event_user[1], -1, 1);
                            $user = str_replace($point, '', str_replace($operation, '', $event_user[1]));
                            if ($user_point = explode(':', $user)) {
                                Text::send($group_id, "{$user} 管理员 ({$real_name}) {$this->_operation[$operation]['str']}了【{$point}】积分，{$this->_operation[$operation]['remind']}");
                            }
                        }
                    }
                }
            } else {
                if ($message['pure'] === '查积分') {
                    Text::send($group_id, "@{$nickname} 积分查询成功");
                }
            }
        }

    }

    public function operation()
    {

    }

    public function execute()
    {

    }


    /**
     * 注册拓展时的操作.
     */
    public function register()
    {
        $this->configs = vbot('config')->get('extension.' . $this->name);
    }

}