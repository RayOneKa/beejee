<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
            integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
            crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <link rel="stylesheet" href="../Libs/sweetalert2/dist/sweetalert2.min.css">
    <script src="../Libs/sweetalert2/dist/sweetalert2.min.js"></script>

    <title>Задачник</title>

    <style>

        html, body {
            width: 100%;
            height: 100%;
        }

        .text_change {
            cursor: pointer;
        }

        .status_change {
            width: 100%;
        }

        .statusTd {
            position: relative;
        }

        .task_del {
            color: white;
            background-color: #8b2d2e;
            position: absolute;
            top: -7px;
            right: -11px;
            border: 1px solid #660800;
            border-radius: 100px;
            padding: 2px 11px 4px 11px;
            cursor: pointer;
        }

        .task_del:hover {
            background-color: #b88782;
        }

    </style>
</head>
<body>

<?php if ($_SESSION['admin']): ?>
    <div style="position:fixed; left: 0; top: 100px; background-color: #333333; color: white; padding: 5px; border-radius: 0 4px 4px 0;">
        Вы вошли как администратор
        <button id="logout" class="btn btn-warning">Выйти</button>
    </div>
<?php else: ?>
    <div style="position:fixed; left: 0; top: 100px; color: white; padding: 5px; border-radius: 0 4px 4px 0;">
        <a href="main/admin" class="btn btn-info">Войти как администратор</a>
    </div>
<?php endif; ?>

<div class="container" style="padding-top: 50px">
    <div class="row">

        <div class="col-6 form-group">

            <form method="POST">
                <h3>Добавить новую задачу:</h3>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Имя</label>
                    <input id="newTaskName" type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlInput1">Email</label>
                    <input type="email" class="form-control" id="newTaskEmail" placeholder="name@example.com">
                </div>
                <div style="display: none" id="emailValid" class="alert alert-warning" role="alert">
                    Введите верный e-mail!
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Текст задачи</label>
                    <textarea class="form-control" id="newTaskText" rows="3"></textarea>
                </div>
                <button id="addNewTask" class="btn btn-info">Добавить</button>
            </form>

        </div>

        <div class="col-12">

            <table id="tasks_table" class="table table-bordered">
                <thead>
                <tr>
                    <th>#id</th>
                    <th>Имя</th>
                    <th>e-mail</th>
                    <th>Текст задачи</th>
                    <th>Статус</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

    function ValidateEmail(val) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(val)) {
            return (true)
        }
        return (false)
    }

    $(document).ready(function () {

        $('body').on('click', '.task_del', function () {

            let id = $(this).data('id');

            swal({
                type: 'question',
                text: 'Удалить задачу?',
                allowOutsideClick: false,
                showCancelButton: true,
                cancelButtonText: 'Отмена',
                confirmButtonText: 'Удалить',
                title: 'Вы уверены?',
                preConfirm: function () {
                    return new Promise(function (resolve, reject) {

                        $.ajax({
                            url: "main/taskDel",
                            dataType: "json",
                            type: 'POST',
                            data: {
                                id: id,
                            },
                            success: function (res) {

                                if (res['status'] == false) {
                                    reject(res['message']);
                                }

                                resolve()
                            },
                            error: function (data) {
                                reject('Что-то пошло не так');
                            }

                        });

                    })
                }
            }).then(function () {
                table.ajax.reload();
            });
        });

        $('body').on('click', '.status_change', function () {

            let id = $(this).data('id');
            let status = '';
            if ($(this).text() == 'Не выполнена')
                status = 1;
            else
                status = 0;

            swal({
                type: 'question',
                text: 'Изменить статус?',
                allowOutsideClick: false,
                showCancelButton: true,
                cancelButtonText: 'Отмена',
                confirmButtonText: 'Изменить',
                title: 'Вы уверены?',
                preConfirm: function () {
                    return new Promise(function (resolve, reject) {

                        $.ajax({
                            url: "main/statusChange",
                            dataType: "json",
                            type: 'POST',
                            data: {
                                id: id,
                                status: status
                            },
                            success: function (res) {

                                if (res['status'] == false) {
                                    reject(res['message']);
                                }

                                resolve()
                            },
                            error: function (data) {
                                reject('Что-то пошло не так');
                            }

                        });

                    })
                }
            }).then(function () {
                table.ajax.reload();
            });

        });


        $('body').on('click', '.text_change', function () {

            let text = $(this).text().replace(/"/g, '');

            console.log(text);

            let id = $(this).data('id');

            swal({
                type: 'question',
                html: `
                <div class="col-12">
                    <input id="new_text" class="form-control" value="${text}">
                </div>
                `,
                allowOutsideClick: false,
                showCancelButton: true,
                cancelButtonText: 'Отмена',
                confirmButtonText: 'Сохранить',
                title: 'Изменить текст задания',
                preConfirm: function () {
                    return new Promise(function (resolve, reject) {

                        $.ajax({
                            url: "main/textChange",
                            dataType: "json",
                            type: 'POST',
                            data: {
                                id: id,
                                text: $('#new_text').val()
                            },
                            success: function (res) {

                                if (res['status'] == false) {
                                    reject(res['message']);
                                }

                                resolve()
                            },
                            error: function (data) {
                                reject('Что-то пошло не так');
                            }

                        });

                    })
                }
            }).then(function () {
                table.ajax.reload();
            });

        });

        $('#logout').click(function () {

            $.ajax({
                url: "main/logout",
                dataType: "json",
                type: 'POST',
                data: {},
                success: function (res) {

                    if (res) {
                        window.location.reload();
                    }
                }
            });
        });

        $('#newTaskEmail').on('input', function () {

            let val = $(this).val();

            let check = ValidateEmail(val);

            if (!check)
                $('#emailValid').show();
            else
                $('#emailValid').hide();

        });

        $('#addNewTask').click(function (e) {

            e.preventDefault();

            let name = $('#newTaskName').val();
            let email = $('#newTaskEmail').val();
            let text = $('#newTaskText').val();

            let errors = '';

            if (!name)
                errors += 'Вы не ввели имя!<br>';

            if (!email)
                errors += 'Вы не ввели email!<br>';
            else {
                let check = ValidateEmail(email);

                if (!check)
                    errors += 'Вы ввели неверный e-mail!';
            }

            if (!text)
                errors += 'Вы не ввели текст!<br>';



            if (errors) {
                swal('Внимание!', errors, 'warning');
                return false;
            }

            let data = {
                'name': name,
                'email': email,
                'text': text,
            };

            $.ajax({
                url: "main/addTask",
                dataType: "json",
                type: 'POST',
                data: data,
                success: function (res) {

                    swal({
                        title: 'Готово!',
                        text: 'Задача успешно добавлена',
                        type: 'success',
                        allowOutsideClick: false,
                    }).then(function () {

                        $('#emailValid').hide();
                        $('#newTaskName').val('');
                        $('#newTaskEmail').val('');
                        $('#newTaskText').val('');
                        table.ajax.reload();

                    });


                }

            });

        });

        let table = $('#tasks_table').DataTable({
            "language": {
                "processing": "Подождите...",
                "search": "Поиск:",
                "lengthMenu": "Показать _MENU_ записей",
                "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                "infoEmpty": "Записи с 0 до 0 из 0 записей",
                "infoFiltered": "(отфильтровано из _MAX_ записей)",
                "infoPostFix": "",
                "loadingRecords": "Загрузка записей...",
                "zeroRecords": "Записи отсутствуют.",
                "emptyTable": "В таблице отсутствуют данные",
                "paginate": {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                },
                "aria": {
                    "sortAscending": ": активировать для сортировки столбца по возрастанию",
                    "sortDescending": ": активировать для сортировки столбца по убыванию"
                }
            },
            "lengthMenu": [[3, 25, 50, -1], [3, 25, 50, "All"]],
            "pageLength": 3,
            "searching": false,
            "processing": true,
            "serverSide": true,
            "pagind": true,
            "order": [[0, 'ASC']],
            "ajax": function (data, callback, settings) {


                // if (data.draw > 1) {
                //     let page = $('.page-link', $('.page-item.active')).data('dt-idx');
                //     console.log(page);
                //
                //     data.start = page * data.length;
                // }

                $.ajax({
                    url: "main/allTasks",
                    dataType: "json",
                    type: 'POST',
                    data: data,
                    success: function (res) {

                        callback(res);

                    }

                });
            },
            "columns": [
                {"data": "id", "name": 'id', "orderable": false},
                {"data": "name", "name" : 'name', "orderable": true},
                {"data": "email", "name" : 'email', "orderable": true},
                {"data": "text", "name" : 'text', "orderable": false},
                {"data": "status", "name" : 'status', 'sClass' : 'statusTd', "orderable": true},
            ],

        });
    });

</script>

</body>
</html>