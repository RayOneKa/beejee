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

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.bootstrap4.min.css">

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <link rel="stylesheet" href="../Libs/sweetalert2/dist/sweetalert2.min.css">
    <script src="../Libs/sweetalert2/dist/sweetalert2.min.js"></script>


    <title>Авторизация</title>
</head>
<body>

<div style="padding-top: 150px; width: 500px; height: 500px; position:absolute; transform: translate(-50%, -50%); top: 30%; left: 50%">

    <?php if ($_SESSION['admin']): ?>
        Вы вошли как администратор
        <button id="logout" class="btn btn-warning">Выйти</button>
        <a href="/" class="btn btn-info">На главную</a>
    <?php else: ?>
        <form method="POST">
            <h3>Войти как администратор:</h3>
            <div style="display: none" id="login_error" class="alert alert-danger" role="alert">
                Ошибка авторизации
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Логин</label>
                <input id="login" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="exampleFormControlInput1">Пароль</label>
                <input type="password" class="form-control" id="password">
            </div>
            <button id="asdfasdf" class="btn btn-info">Войти</button>
        </form>
    <?php endif; ?>




</div>

<script>

    $('#login, #password').on('input', function () {
        $('#login_error').hide();
    });

    $('#logout').click(function () {

        $.ajax({
            url: "logout",
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

    $('#asdfasdf').click(function(e) {

        e.preventDefault();

        let login = $('#login').val();
        let password = $('#password').val();

        let errors = '';

        if (!login) {
            errors += 'Логин не может быть пустым!<br>';
        }
        if (!password) {
            errors += 'Пароль не может быть пустым!';
        }

        if (errors) {

            swal('Внимание!', errors, 'error');
            return false;

        }

        let data = {
            'login': login,
            'password': password
        };

        $.ajax({
            url: "login",
            dataType: "json",
            type: 'POST',
            data: data,
            success: function (res) {
                if (res == false) {
                    $('#login_error').show();
                } else {
                    window.location.reload();
                }
            }

        });

    });


</script>

</body>
</html>