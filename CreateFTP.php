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
        $directory = '../var/www/html/test3';
        $ssh->exec('mkdir '. $directory);
        $ssh->exec("useradd -M test3");
        $ssh->exec('echo "test3:test" | sudo chpasswd');
        $ssh->exec('pkill -u test3' );
        $ssh->exec('chown -R test3 '. $directory);
    }

    function connectSSH(){

    }

}

$ftp = new CreateFTP();


?>