<?php
function renderAdd() {
    $db = new PDO('sqlite:' . __DIR__ . '/phonebook.db');
    $message = '';
    $button = 'Добавить запись';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button'])) {
        $surname  = trim($_POST['surname'] ?? '');
        $name     = trim($_POST['name'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $gender   = trim($_POST['gender'] ?? '');
        $date     = trim($_POST['date'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $comment  = trim($_POST['comment'] ?? '');

        if ($surname !== '' && $name !== '') {
            $stmt = $db->prepare("INSERT INTO contacts
                (surname, name, lastname, gender, date, phone, location, email, comment)
                VALUES (:surname, :name, :lastname, :gender, :date, :phone, :location, :email, :comment)");

            $result = $stmt->execute([
                ':surname'  => $surname,
                ':name'     => $name,
                ':lastname' => $lastname,
                ':gender'   => $gender,
                ':date'     => $date,
                ':phone'    => $phone,
                ':location' => $location,
                ':email'    => $email,
                ':comment'  => $comment,
            ]);

            if ($result) {
                $message = '<p class="success">Запись добавлена</p>';
            } else {
                $message = '<p class="error">Ошибка: запись не добавлена</p>';
            }
        } else {
            $message = '<p class="error">Ошибка: фамилия и имя обязательны</p>';
        }
    }

    $row = [
        'surname' => '', 'name' => '', 'lastname' => '',
        'gender' => '', 'date' => '', 'phone' => '',
        'location' => '', 'email' => '', 'comment' => ''
    ];

    $html = $message;
    $html .= '<form name="form_add" method="post" action="index.php?page=add">';
    $html .= '<div class="column">';

    $html .= '<div class="add"><label>Фамилия</label><input type="text" name="surname" placeholder="Фамилия" value=""></div>';
    $html .= '<div class="add"><label>Имя</label><input type="text" name="name" placeholder="Имя" value=""></div>';
    $html .= '<div class="add"><label>Отчество</label><input type="text" name="lastname" placeholder="Отчество" value=""></div>';

    $html .= '<div class="add"><label>Пол</label>';
    $html .= '<select name="gender">';
    $html .= '<option value="">— не указан —</option>';
    $html .= '<option value="мужской">мужской</option>';
    $html .= '<option value="женский">женский</option>';
    $html .= '</select></div>';

    $html .= '<div class="add"><label>Дата рождения</label><input type="date" name="date" value=""></div>';
    $html .= '<div class="add"><label>Телефон</label><input type="text" name="phone" placeholder="Телефон" value=""></div>';
    $html .= '<div class="add"><label>Адрес</label><input type="text" name="location" placeholder="Адрес" value=""></div>';
    $html .= '<div class="add"><label>Email</label><input type="email" name="email" placeholder="Email" value=""></div>';
    $html .= '<div class="add"><label>Комментарий</label><textarea name="comment" placeholder="Краткий комментарий"></textarea></div>';

    $html .= "<button type='submit' name='button' value='{$button}' class='form-btn'>{$button}</button>";
    $html .= '</div>';
    $html .= '</form>';

    return $html;
}
?>