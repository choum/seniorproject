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

    }

    function createUser($user, $pass){
        $username = $user;
        $password = $pass;
        set_include_path(get_include_path() . PATH_SEPARATOR . 'ssh');
        include('ssh/Net/SSH2.php');
        $ssh = new Net_SSH2('172.16.33.106');
        $ssh->login('root', 'portfolio4290') or die ("Login failed");
        $directory = '../var/www/html/'.$username;
        $ssh->exec('mkdir '. $directory);
        $ssh->exec('useradd -m -d /var/www/html/'.$username.' '.$username);
        $ssh->exec('echo "'.$username.':'.$password.'" | sudo chpasswd');
        $ssh->exec('sudo usermod -aG sftp '.$username);
        $ssh->exec('sudo chown root /var/www/html/'.$username);
        $ssh->exec('chmod go-w /var/www/html/'.$username);
        $ssh->exec('sudo mkdir /var/www/html/'.$username.'/workspace');
        $ssh->exec('sudo chown '.$username.':sftp /var/www/html/'.$username.'/workspace');
        $ssh->exec('sudo chmod ug+rwX /var/www/html/'.$username.'/workspace');
    }
    
}

?>