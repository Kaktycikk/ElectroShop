
<?php include __DIR__ . '/../../includes/admin_header.php'; ?>

<div class="container-fluid">

    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <div class="col-md-10 p-4">

            <div class="dashboard-banner">

                <h1>Панель управления</h1>

                <p>
                    Интернет-магазин электроники ElectroAdmin
                </p>

            </div>

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="card shadow stat-card">
                        <div class="card-body">
                            <h6>Товары</h6>
                            <div class="stat-number">156</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow stat-card">
                        <div class="card-body">
                            <h6>Категории</h6>
                            <div class="stat-number">12</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow stat-card">
                        <div class="card-body">
                            <h6>Пользователи</h6>
                            <div class="stat-number">35</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow stat-card">
                        <div class="card-body">
                            <h6>Заказы</h6>
                            <div class="stat-number">84</div>
                        </div>
                    </div>
                </div>

            </div>

            <h3 class="section-title">
                Последние заказы
            </h3>

            <div class="card shadow">

                <div class="card-body">

                    <table class="table">

                        <thead>
                            <tr>
                                <th>№</th>
                                <th>Клиент</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td>1001</td>
                                <td>Иван Иванов</td>
                                <td>125 000 ₽</td>
                                <td>Оплачен</td>
                            </tr>

                            <tr>
                                <td>1002</td>
                                <td>Петр Петров</td>
                                <td>89 000 ₽</td>
                                <td>В обработке</td>
                            </tr>

                            <tr>
                                <td>1003</td>
                                <td>Анна Смирнова</td>
                                <td>45 000 ₽</td>
                                <td>Отправлен</td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../../includes/admin_footer.php'; ?>