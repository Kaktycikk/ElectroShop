<?php

session_start();
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

include __DIR__ . '/../includes/db.php';

$error = "";
$success = "";

if (isset($_GET["registered"]))
{
    $success = "Регистрация прошла успешно. Теперь войдите в аккаунт.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = trim(strtolower($_POST["email"]));
    $password = $_POST["password"];

    if (empty($email) || empty($password))
    {
        $error = "Заполните все поля.";
    }
    else
    {
        $result = pg_query_params(
            $conn,
            "SELECT * FROM users WHERE email = $1",
            array($email)
        );

        if (!$result)
        {
            $error = "Ошибка базы данных.";
        }
        elseif (pg_num_rows($result) == 0)
        {
            $error = "Неверный email или пароль.";
        }
        else
        {
            $user = pg_fetch_assoc($result);

            $user["is_admin"] = ($user["is_admin"] === "t");

            if (password_verify($password, $user["password"]))
            {
                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["is_admin"] = $user["is_admin"];
                $_SESSION["user_email"] = $user["email"];

                if ($user["is_admin"])
                {
                    header("Location: admin/dashboard.php");
                }
                else
                {
                    header("Location: index.php");
                }

                exit;
            }
            else
            {
                $error = "Неверный email или пароль.";
            }
        }
    }
}

?>

<?php include __DIR__ . '/../includes/site_header.php'; ?>

<section class="auth-section">

    <div class="container">

        <div class="auth-card">

            <div class="row g-0">

                <!-- Левая часть -->

                <div class="col-lg-7">

                    <div class="auth-form">

                        <h1>

                            Добро пожаловать!

                        </h1>

                        <p class="text-muted mb-4">

                            Войдите в аккаунт, чтобы продолжить покупки.

                        </p>

                        <?php if ($success): ?>

                            <div class="alert alert-success">

                                <?= $success ?>

                            </div>

                        <?php endif; ?>

                        <?php if ($error): ?>

                            <div class="alert alert-danger">

                                <?= $error ?>

                            </div>

                        <?php endif; ?>

                        <form method="POST">

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

                            <div class="mb-4">

                                <label class="form-label">

                                    Пароль

                                </label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Введите пароль"
                                    autocomplete="current-password"
                                    required>

                            </div>

                            <button
                                class="btn btn-warning w-100"
                                type="submit">

                                Войти

                            </button>

                        </form>

                        <p class="text-center mt-4 mb-0">

                            Нет аккаунта?

                            <a href="register.php">

                                Зарегистрироваться

                            </a>

                        </p>

                    </div>

                </div>

                <!-- Правая часть -->

                <div class="col-lg-5">

                    <div class="auth-info">

                        <div>

                            <i class="bi bi-person-circle auth-icon"></i>

                            <h2>

                                ElectroShop

                            </h2>

                            <p>

                                Личный кабинет покупателя

                            </p>

                        </div>

                        <ul>

                            <li><i class="bi bi-box-seam"></i> История заказов</li>

                            <li><i class="bi bi-truck"></i> Отслеживание заказов</li>

                            <li><i class="bi bi-lightning-charge"></i> Быстрое оформление</li>

                            <li><i class="bi bi-percent"></i> Акции и специальные предложения</li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include __DIR__ . '/../includes/site_footer.php'; ?>