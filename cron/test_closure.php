<?php
/**
 * Created by PhpStorm.
 * User: guokeling
 * Date: 2019/1/4
 * Time: 18:33
 */

class test
{
    public static function show()
    {
        var_dump(func_get_args());
    }
}

call_user_func(array('test', 'show'), 'hello world!');

$test = 'lihaile';

$func2 = function($name = 'World') use (&$test)
{
    echo $test . " Hello " . $name . "\n";
};
var_dump($func2 instanceof Closure);
$func2('LiLy!');
$test = "zhenniua";

$func2('LiLy!');

