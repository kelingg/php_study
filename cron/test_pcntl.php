<?php

/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2019/1/5
 * Time: 16:41
 */

//Tick 是一个在代码段中解释器每执行 N 条低级语句就会发生的事件，这个代码段需要通过declare来指定。
declare(ticks = 1);

//信号处理
//int pcntl_alarm ( int $seconds )
//设置一个$seconds秒后发送SIGALRM信号的计数器
//
//bool pcntl_signal ( int $signo , callback $handler [, bool $restart_syscalls ] )
//为$signo设置一个处理该信号的回调函数
//
//下面是一个隔2秒发送一个SIGALRM信号，并由signal_handler函数获取，然后打印一个“Caught SIGALRM”的例子：
function signal_handler($signal)
{
    echo "Caught SIGALRM\n";
    pcntl_alarm(2);
}

pcntl_signal(SIGALRM, 'signal_handler', true);
pcntl_alarm(2);

$i = 0;
while($i++<5) {
    echo $i."\n";
    sleep(1);
}

//在当前的进程空间中执行指定程序，类似于c中的exec族函数。所谓当前空间，即载入指定程序的代码覆盖掉当前进程的空间，执行完该程序进程即结束。
//pcntl_exec('/usr/local/bin/php', array('/home/jm/www/PHPHelper/example/test_pcntl.php'));

$pid = pcntl_fork();
if($pid) {
    pcntl_wait($status);
    $id = getmypid();
    echo "parent process,pid {$id}, child pid {$pid}\n";
    echo posix_getpid() . " - " . posix_getppid() . "\n";
} else {
    $id = getmypid();
    echo "child process,pid {$id} \n";
    echo posix_getpid() . " - " . posix_getppid() . "\n";
    sleep(2);
}