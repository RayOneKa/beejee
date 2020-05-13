<?php


namespace Models;


class Tasks
{

    private $dsn = 'mysql:host=mysql.zzz.com.ua;dbname=rayoneka;';
    private $login = 'beejeemvcuser';
    private $password = 'Admin123';

    public function getAll($post)
    {
        $post = $_POST;

        $pdo = new \PDO($this->dsn, 'beejeemvcuser', 'Admin123');

        $order = $post['order'][0]['dir'];

        $column = $post['columns'][$post['order'][0]['column']]['name'];

        if ($order)
            $order = 'ORDER BY '.$column.' ' .$order;

        $sql = "SELECT * FROM tasks $order";

        $data = $pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

        $res = [];

        foreach ($data as $item) {
            $status = $item['status'] == 0 ? 'Не выполнена' : 'Выполнена';

            if ($_SESSION['admin'])
                $status = '<button data-id="'.$item['id'].'" class="btn btn-primary status_change">'.$status.'</button> <span data-id="'.$item['id'].'" class="task_del">x</span>';

            $text = htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8');
            if ($_SESSION['admin']) {
                $text = '<label class="form-control text_change" data-id="'.$item['id'].'">' . htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') . '</label>';
            }

            $res[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'email' => $item['email'],
                'text' => $text,
                'status' => $status
            ];
        }

        return $res;
    }

    public function addTask($data) {

        $pdo = new \PDO($this->dsn, $this->login, $this->password);

        $name = htmlspecialchars($data['name']);
        $email = htmlspecialchars($data['email']);
        $text = htmlspecialchars($data['text']);

        $sql = "INSERT INTO tasks (name, email, text) values ('$name', '$email', '$text')";

        return $pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function changeTask($data) {

        $pdo = new \PDO($this->dsn, $this->login, $this->password);

        $text = $data['text'];
        $id = $data['id'];

        $sql = "UPDATE tasks SET text = '$text' WHERE id = $id";
        return $pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function changeStatus($data) {

        $pdo = new \PDO($this->dsn, $this->login, $this->password);

        $status = (int)$data['status'];
        $id = $data['id'];

        $sql = "UPDATE tasks SET status = $status WHERE id = $id";


        return $pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function taskDel($data) {

        $pdo = new \PDO($this->dsn, $this->login, $this->password);

        $id = $data['id'];

        $sql = "DELETE FROM tasks WHERE id = $id";

        return $pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

}