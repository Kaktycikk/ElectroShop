<?php

if (isset($_SESSION["user_id"]))
{
    if ($_SESSION["is_admin"])
    {
        header("Location: admin/dashboard.php");
    }
    else
    {
        header("Location: index.php");
    }

    exit;
}

session_start();

include __DIR__ . '/../includes/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = trim($_POST["name"]);
    $email = trim(strtolower($_POST["email"]));
    $password = $_POST["password"];
    $password_confirm = $_POST["password_confirm"];

    if (
        empty($name) ||
        empty($email) ||
        empty($password) ||
        empty($password_confirm)
    )
    {
        $error = "Заполните все поля.";
    }
    elseif (strlen($name) < 2)
    {
        $error = "Имя должно содержать минимум 2 символа.";
    }
    elseif (strlen($name) > 100)
    {
        $error = "Имя не должно превышать 100 символов.";
    }
    elseif ($password !== $password_confirm)
    {
        $error = "Пароли не совпадают.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $error = "Некорректный email.";
    }
    elseif (strlen($password) < 6)
    {
        $error = "Пароль должен содержать минимум 6 символов.";
    }
    else
    {
        $result = pg_query_params(
            $conn,
            "SELECT id FROM users WHERE email = $1",
            array($email)
        );

        if (pg_num_rows($result) > 0)
        {
            $error = "Пользователь с таким email уже существует.";
        }
        else
        {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $result = pg_query_params(
                $conn,
                "INSERT INTO users (name, email, password)
                VALUES ($1, $2, $3)",
                array($name, $email, $hash)
            );

            if ($result)
            {
                header("Location: login.php?registered=1");
                exit;
            }
            else
            {
                $error = "Не удалось зарегистрировать пользователя.";
            }
        }
    }
}

?>

<?php include __DIR__ . '/../includes/site_header.php'; ?>

<section class="auth-section">

    <div class="container">

        <div class="register-card">

            <div class="row g-0">

                <div class="col-lg-6">

                    <div class="auth-form">

                        <h1>

                            Добро пожаловать в ElectroShop!

                        </h1>

                        <p class="text-muted mb-4">

                            Создайте аккаунт и получите доступ ко всем возможностям магазина.

                        </p>

                        <?php if ($error): ?>

                            <div class="alert alert-danger">

                                <?= $error ?>

                            </div>

                        <?php endif; ?>

                        <form method="POST">

                            <div class="mb-3">

                                <label class="form-label">

                                    Имя

                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="Введите имя"
                                    value="<?= isset($name) ? htmlspecialchars($name) : '' ?>"
                                    autocomplete="name"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="Введите email"
                                    value="<?= isset($email) ? htmlspecialchars($email) : '' ?>"
                                    autocomplete="email"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Пароль

                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Введите пароль"
                                    autocomplete="new-password"
                                    required>

                            </div>

                            <div class="mb-4">

                                <label class="form-label">

                                    Подтверждение пароля

                                </label>

                                <input
                                    type="password"
                                    name="password_confirm"
                                    class="form-control"
                                    placeholder="Повторите пароль"
                                    autocomplete="new-password"
                                    required>

                            </div>

                            <button type="submit"
                                class="btn btn-warning w-100">

                                Зарегистрироваться

                            </button>

                        </form>

                        <p class="text-center mt-4 mb-0">

                            Уже есть аккаунт?

                            <a href="login.php">

                                Войти

                            </a>

                        </p>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="register-image">

                        <img
                            src="img/register/register.png"
                            alt="Регистрация">

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/site_footer.php'; ?>