<?php
/*
 * User: keke
 * Date: 2021/7/14
 * Time: 17:01
 *——————————————————佛祖保佑 ——————————————————
 *                   _ooOoo_
 *                  o8888888o
 *                  88" . "88
 *                  (| -_- |)
 *                  O\  =  /O
 *               ____/`---'\____
 *             .'  \|     |//  `.
 *            /  \|||  :  |||//  \
 *           /  _||||| -:- |||||-  \
 *           |   | \\  -  /// |   |
 *           | \_|  ''\---/''  |   |
 *           \  .-\__  `-`  ___/-. /
 *         ___`. .'  /--.--\  `. . __
 *      ."" '<  `.___\_<|>_/___.'  >'"".
 *     | | :  ` - `.;`\ _ /`;.`/ - ` : | |
 *     \  \ `-.   \_ __\ /__ _/   .-` /  /
 *======`-.____`-.___\_____/___.-`____.-'======
 *                   `=---='
 *——————————————————代码永无BUG —————————————————
 */

namespace chat\sw\Controller;


use chat\sw\Core\Rule;

class WsController extends Rule
{
    public function rule()
    {
        return [
            'stop' => [
                'data' => ['name' => 'data', 'require' => true, 'type' => 'string']
            ]
        ];
    }

    public function stop(\Swoole\WebSocket\Server $server, array $msg): array
    {
        return ["code" => 0, "msg" => "123123"];
    }
}