<?php

namespace Controllers;

use System\View;
use Models\Tasks;
use Models\Users;

/**
 * Главный контроллер приложения
 *
 * @author farza
 */
class mainController
{

    public function actionIndex()
    {

        // Рендер главной страницы портала
        View::render('index', [

        ]);
    }

    public function actionAllTasks()
    {

        $post = $_POST;

        $tasks = new Tasks();
        $data = $tasks->getAll($post);

        $res = array_slice($data, $post['start'], $post['length']);

        $res = [
            'draw' => $post['draw'],
            'all' => COUNT($data),
            'data' => $res,
            'recordsTotal' => COUNT($data),
            'recordsFiltered' => COUNT($data),
        ];

        echo json_encode($res);

    }

    public function actionAddTask()
    {

        $post = $_POST;

        $tasks = new Tasks();

        $res = $tasks->addTask($post);

        echo json_encode($res);

    }

    public function actionAdmin() {

        View::render('login', [

        ]);
    }

    public function actionLogin() {

        $post = $_POST;

        $users = new Users();

        $res = $users->login($post);

        if ($res)
            $_SESSION['admin'] = true;

        echo json_encode($res);

    }

    public function actionLogout() {
        unset ($_SESSION['admin']);

        echo json_encode(true);
    }

    public function actionTextChange() {

        if (!$_SESSION['admin']) {
            echo json_encode([
                'status' => false,
                'message' => 'Вы не авторизованы как администратор'
            ]);

            return false;
        }

        $post = $_POST;

        $tasks = new Tasks();

        $res = $tasks->changeTask($post);

        echo json_encode($res);
    }

    public function actionStatusChange() {

        if (!$_SESSION['admin']) {
            echo json_encode([
                'status' => false,
                'message' => 'Вы не авторизованы как администратор'
            ]);

            return false;

        }

        $post = $_POST;

        $tasks = new Tasks();

        $res = $tasks->changeStatus($post);

        echo json_encode($res);
    }

    public function actionTaskDel() {

        if (!$_SESSION['admin']) {
            echo json_encode([
                'status' => false,
                'message' => 'Вы не авторизованы как администратор'
            ]);

            return false;

        }

        $post = $_POST;

        $tasks = new Tasks();

        $res = $tasks->taskDel($post);

        echo json_encode($res);

    }

}

