<?php


namespace Models;


class Users
{

    private $dsn = 'mysql:host=mysql.zzz.com.ua;dbname=rayoneka;';
    private $login = 'beejeemvcuser';
    private $password = 'Admin123';

    public function login($data) {

        $pdo = new \PDO($this->dsn, $this->login, $this->password);

        $login = $data['login'];
        $password = md5($data['password']);

        $sql = "SELECT * FROM users WHERE login = '$login' and password = '$password'";

        $res = $pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        if ($res)
            return true;
        else
            return false;
    }

}