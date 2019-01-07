<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2019/1/5
 * Time: 17:33
 */

// 1.启动服务端: php test_socket.php 1
// 2.户端发消息: php test_socket.php 0 'Hello'

$isServer = $argv[1];
if(!empty($argv[2])) {
    $message = $argv[2];
}
echo "is server: " . $isServer . "\n";

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

$ip = '127.0.0.1';
$port = 5768;
if($isServer) {
    if(socket_bind($socket, $ip, $port) == false) {
        echo 'server bind fail:' . socket_strerror(socket_last_error()) . "\n";
    }

    if(socket_listen($socket, 4) == false) {
        echo 'server listen fail:' . socket_strerror(socket_last_error()) . "\n";
    }

    while(true){
        $acceptResource = socket_accept($socket);
        if($acceptResource !== false) {
            $string =  socket_read($acceptResource, 1024);
            $string = mb_convert_encoding($string, 'UTF-8', 'GBK');
            echo 'server receive is :' . $string.PHP_EOL;
            if($string!==false) {
                $returnMsg = $string .' has received '. PHP_EOL;
                socket_write($acceptResource, $returnMsg, strlen($returnMsg));
            } else {
                echo "socket read is fail \n";
            }
            socket_close($acceptResource);
        }

    }
} else {
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 1, 'usec' => 0));
    socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 5, 'usec' => 0));
    if(socket_connect($socket, $ip, $port) == false) {
        echo 'client connect fail:' . socket_strerror(socket_last_error()) . "\n";
    } else {
        if(empty($message)) {
            $message = "Hello 周杰伦!";
        }
        $messageUTF8 = mb_convert_encoding($message, 'GBK', 'UTF-8');
        if(socket_write($socket, $messageUTF8, strlen($messageUTF8)) == false) {
            echo 'client write fail :' . socket_strerror(socket_last_error()) . "\n";
        } else {
            echo "client write success :" . $message . PHP_EOL;
            while($callback = socket_read($socket, 1024)){
                echo 'server return message is :'  . $callback . "\n";
            }
        }
    }
}
socket_close($socket);

// https://www.cnblogs.com/loveyoume/p/6076101.html