<?php
/**
 * Created by PhpStorm.
 * User: nareg
 * Date: 10/22/18
 * Time: 1:36 PM
 */

class CreateFTP {

    static $ssh;

    function __construct(){
        self::createUser();
    }

    function createUser(){
        set_include_path(get_include_path() . PATH_SEPARATOR . 'ssh');
        include('ssh/Net/SSH2.php');
        $ssh = new Net_SSH2('172.16.33.106');
        $ssh->login('root', 'portfolio4290') or die ("Login failed");
        $directory = '/var/www/html/nareg';
        echo $ssh->exec("command");
    }

    function connectSSH(){

    }

}

$ftp = new CreateFTP();


?>