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

           shell_exec('sudo bash /usr/bin/apache/createFTP.sh ' . $user . ' ' . $pass . ' 2>&1');
//         $config = parse_ini_file('ssh.ini');
//
//         $username = $user;
//         $password = $pass;
//         set_include_path(get_include_path() . PATH_SEPARATOR . 'private/ssh');
//         include('private/ssh/Net/SSH2.php');
//         $ssh = new Net_SSH2(''.$config['servername'].'');
//         $ssh->login(''.$config['username'].'', ''.$config['password'].'') or die ("Login failed");
//         $directory = '../var/www/html/'.$username;
//         $ssh->exec('mkdir '. $directory);
//         $ssh->exec('useradd -m -d /var/www/html/'.$username.' '.$username);
//         $ssh->exec('echo "'.$username.':'.$password.'" | sudo chpasswd');
//         $ssh->exec('sudo usermod -aG sftp '.$username);
//         $ssh->exec('sudo chown root /var/www/html/'.$username);
//         $ssh->exec('chmod go-w /var/www/html/'.$username);
//         $ssh->exec('sudo mkdir /var/www/html/'.$username.'/workspace');
//         $ssh->exec('sudo chown '.$username.':sftp /var/www/html/'.$username.'/workspace');
//         $ssh->exec('sudo chmod ug+rwX /var/www/html/'.$username.'/workspace');
//         var_dump($ssh);

        // $test = exec('mkdir '. $directory);
        // var_dump($test);
        // shell_exec('useradd -m -d /var/www/html/'.$username.' '.$username);
        // shell_exec('echo "'.$username.':'.$password.'" | sudo chpasswd');
        // shell_exec('sudo usermod -aG sftp '.$username);
        // shell_exec('sudo chown root /var/www/html/'.$username);
        // shell_exec('chmod go-w /var/www/html/'.$username);
        // shell_exec('sudo mkdir /var/www/html/'.$username.'/workspace');
        // shell_exec('sudo chown '.$username.':sftp /var/www/html/'.$username.'/workspace');
        // shell_exec('sudo chmod ug+rwX /var/www/html/'.$username.'/workspace');
    }
    
}

?>