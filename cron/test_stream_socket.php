<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2019/1/5
 * Time: 19:22
 */

// 1.启动服务端: php test_stream_socket.php 1
// 2.户端发消息: php test_stream_socket.php 0 'Hello'

$isServer = $argv[1];
if(!empty($argv[2])) {
    $message = $argv[2];
}
echo "is server: " . $isServer . "\n";


$ip = '127.0.0.1';
$port = 5769;
if($isServer) {
    $socket = stream_socket_server("tcp://" . $ip . ':' . $port, $errno, $errstr);
    if(!$socket) {
        echo 'server fail:' . $errno . ' - ' . $errstr . "\n";
    } else {
        while(true){
            $connect = stream_socket_accept($socket);
            $receiveData = fread($connect, 1024);
            $receiveData = mb_convert_encoding($receiveData, 'UTF-8', 'GBK');
            echo 'server receive is :' . $receiveData.PHP_EOL;
            if($receiveData!==false) {
                $returnMsg = $receiveData .' has received '. PHP_EOL;
                fwrite($connect, $returnMsg, strlen($returnMsg));
            } else {
                echo "socket read is fail \n";
            }
            fclose($connect);
        }
    }
    fclose($socket);

} else {
    $socket  = stream_socket_client("tcp://" . $ip . ':' . $port, $errno, $errstr);
    if(!$socket) {
        echo 'client connect fail:' . $errno . ' - ' . $errstr . "\n";
    } else {
        if(empty($message)) {
            $message = "Hello 周杰伦!";
        }
        $messageUTF8 = mb_convert_encoding($message, 'GBK', 'UTF-8');

        fwrite($socket, $messageUTF8, strlen($messageUTF8));
        echo "client write success :" . $message . PHP_EOL;
        while(!feof($socket)){
            echo fgets($socket, 1024) . "\n";
        }
        fclose($socket);
    }
}
