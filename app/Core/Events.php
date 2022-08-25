<?php
/*
 * User: keke
 * Date: 2021/7/16
 * Time: 11:30
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

namespace chat\sw\Core;

use chat\sw\Router\HttpRouter;
use chat\sw\Router\WsRouter;
use Simps\DB\Redis;

class Events
{
    private $server;

    public function __construct($server)
    {
        $this->server = $server;
    }

    public function onOpen(\Swoole\WebSocket\Server $server, $request)
    {
        echo "server: handshake success with fd{$request->fd}\n";
    }

    public function onMessage(\Swoole\WebSocket\Server $server, $frame)
    {
//        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        $server->push($frame->fd, WsRouter::MsgHandle($server, $frame));//处理路由
//        var_dump($frame->opcode == WEBSOCKET_OPCODE_TEXT, $frame->opcode == WEBSOCKET_OPCODE_BINARY);
//        if ($server->isEstablished($frame->fd)) {
//            $task_id = $server->task(["t" => 1], 0);
//            $server->push($frame->fd, json_encode(['msg' => "hello world"]));
//        }
//        foreach ($server->connections as $fd) {
//            // 需要先判断是否是正确的websocket连接，否则有可能会push失败
//            if ($server->isEstablished($fd)) {
//                $task_id = $server->task(["t" => 1], 0);
//                $server->push($fd, json_encode(['msg' => "hello world"]));
//            }
//        }
    }

    public function onClose($ser, $fd)
    {
        echo "client {$fd} closed\n";
    }

    public function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response)
    {
        if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.png') {
            $response->end();
            return;
        }
        $pathUrl = strtolower($request->server['path_info']);//请求的地址
        $setUrlList = HttpRouter::GetHandlers();
        if (!isset($setUrlList[$pathUrl])) {
            $response->end("<h1>err 404</h1>");
            return;
        }
        call_user_func_array($setUrlList[$pathUrl], [$request, $response, $this->server]);
    }

    public function onWorkerStart(\Swoole\Server $server, int $workerId)
    {
        $config = DI()->config->get('conf.redis');
        if (!empty($config)) {
            Redis::getInstance($config);
        }
    }

    //使用 task 必须为 Server 设置 onTask 和 onFinish 回调，否则 Server->start 会失败
    public function onTask(\Swoole\Server $server, int $task_id, int $src_worker_id, array $data)
    {
        echo "Tasker进程接收到数据,task_id:";
        var_dump($task_id);
        var_dump($data);
    }

    public function onFinish(\Swoole\Server $server, int $task_id, int $src_worker_id, array $data)
    {

    }
}