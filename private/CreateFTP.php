<?php
/*
 * Created by Nareg Khodanian
 * Description: While currently not in use, the purpose of this class is to create new users for the purpose of use SFTP access in order to upload
 * and edit projects previously uploaded.
 * Due to security risks and the lack of differing assigned roles and privileges, this classes use
 * has been removed from all other sections of code and is kept in for possible use in later versions.
 */

class CreateFTP {

    static $ssh;

    function __construct(){

    }

    function createUser($user, $pass){

        $config = parse_ini_file('ssh.ini');

        $username = $user;
        $password = $pass;
        set_include_path(get_include_path() . PATH_SEPARATOR . 'private/ssh');
        include('private/ssh/Net/SSH2.php');
        $ssh = new Net_SSH2(''.$config['servername'].'');
        $ssh->login(''.$config['username'].'', ''.$config['password'].'') or die ("Login failed");
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