<?php

session_start();
include __DIR__ . '/../../includes/db.php';

if (isset($_POST["edit_user"]))
{
    $userId = (int)$_POST["user_id"];

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    // Проверяем, не занят ли email
    $exists = (int)pg_fetch_result(
        pg_query_params(
            $conn,
            "
            SELECT COUNT(*)
            FROM users
            WHERE LOWER(email) = LOWER($1)
              AND id <> $2
            ",
            [
                $email,
                $userId
            ]
        ),
        0,
        0
    );

    if ($exists > 0)
    {
        $_SESSION["user_error"] =
            "Пользователь с таким email уже существует.";

        header("Location: users.php");
        exit;
    }

    $result = pg_query_params(
        $conn,
        "
        UPDATE users
        SET
            name = $1,
            email = $2
        WHERE id = $3
        ",
        [
            $name,
            $email,
            $userId
        ]
    );

    if (!$result)
    {
        die(pg_last_error($conn));
    }

    header("Location: users.php");
    exit;
}

if (isset($_POST["delete_user"]))
{
    $userId = (int)$_POST["user_id"];

    $result = pg_query_params(
        $conn,
        "
        DELETE FROM users
        WHERE id = $1
        ",
        [
            $userId
        ]
    );

    if (!$result)
    {
        die(pg_last_error($conn));
    }

    header("Location: users.php");
    exit;
}

$search = trim($_GET["search"] ?? "");

$sql = "
    SELECT
        id,
        name,
        email,
        is_admin
    FROM users
    WHERE is_admin = FALSE
    ";

$params = [];
$index = 1;

if ($search !== "")
{
    $sql .= "
        AND (
            LOWER(name) LIKE LOWER($" . $index . ")
            OR LOWER(email) LIKE LOWER($" . $index . ")
        )
    ";

    $params[] = "%" . $search . "%";
    $index++;
}

$sql .= "
ORDER BY id
";

$users = pg_query_params(
    $conn,
    $sql,
    $params
);

if (!$users)
{
    die(pg_last_error($conn));
}

if (!$users)
{
    die(pg_last_error($conn));
}

$error = $_SESSION["user_error"] ?? "";

unset($_SESSION["user_error"]);

include __DIR__ . '/../../includes/admin_header.php';

?>

<div class="container-fluid">

    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <div class="col-md-10 main-content">

            <div class="dashboard-banner">

                <h1>Пользователи</h1>

                <p>
                    Управление пользователями системы
                </p>

            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">

                <form
                    method="GET"
                    class="d-flex"
                    style="max-width:350px; width:100%;">

                    <input
                        type="text"
                        class="form-control me-2"
                        name="search"
                        placeholder="Поиск пользователя..."

                        value="<?= htmlspecialchars($search) ?>">

                    <button
                        type="submit"
                        class="edit-btn">

                        <i class="bi bi-search"></i>

                    </button>

                </form>

            </div>

            <div class="table-wrapper">

                <table class="table">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Действия</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($user = pg_fetch_assoc($users)): ?>

                        <tr>

                            <td><?= $user['id']; ?></td>

                            <td><?= $user['name']; ?></td>

                            <td><?= $user['email']; ?></td>

                            <td>

                                <div class="d-flex gap-2">

                                    <button
                                        class="edit-btn edit-user-btn"

                                        data-id="<?= $user["id"] ?>"
                                        data-name="<?= htmlspecialchars($user["name"]) ?>"
                                        data-email="<?= htmlspecialchars($user["email"]) ?>"

                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal">

                                        <i class="bi bi-pencil-fill"></i>

                                    </button>

                                    <button
                                        class="delete-btn delete-user-btn"

                                        data-id="<?= $user["id"] ?>"
                                        data-name="<?= htmlspecialchars($user["name"]) ?>"

                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteUserModal">

                                        <i class="bi bi-x-lg"></i>

                                    </button>

                                </div>
                            </td>

                        </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- Редактирование пользователя -->

<div class="modal fade" id="editUserModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST">

                <input
                    type="hidden"
                    id="editUserId"
                    name="user_id">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Редактировать пользователя

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <?php if (!empty($error)): ?>

                    <div class="alert alert-danger">

                        <i class="bi bi-exclamation-circle me-2"></i>

                        <?= htmlspecialchars($error) ?>

                    </div>

                    <?php endif; ?>

                    <div class="mb-3">

                        <label class="form-label">

                            Имя

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="editUserName"
                            name="name"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Email

                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="editUserEmail"
                            name="email"
                            required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-modal-cancel"
                        data-bs-dismiss="modal">

                        <i class="bi bi-x-lg me-2"></i>

                        Отмена

                    </button>

                    <button
                        type="submit"
                        name="edit_user"
                        class="btn btn-modal-save">

                        <i class="bi bi-check-lg me-2"></i>

                        Сохранить

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Удаление пользователя -->

<div class="modal fade" id="deleteUserModal">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST">

                <input
                    type="hidden"
                    id="deleteUserId"
                    name="user_id">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Удаление пользователя

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    Вы действительно хотите удалить пользователя
                    <strong id="deleteUserName"></strong>?

                </div>

                <div class="modal-footer d-flex justify-content-end gap-2">

                    <button
                        type="button"
                        class="btn btn-modal-cancel"
                        data-bs-dismiss="modal">

                        <i class="bi bi-x-lg me-2"></i>

                        Отмена

                    </button>

                    <button
                        type="submit"
                        name="delete_user"
                        class="btn btn-modal-delete">

                        <i class="bi bi-trash me-2"></i>

                        Удалить

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>

<?php if (!empty($error)): ?>

<script>

document.addEventListener("DOMContentLoaded", () => {

    new bootstrap.Modal(
        document.getElementById("editUserModal")
    ).show();

});

</script>

<?php endif; ?>