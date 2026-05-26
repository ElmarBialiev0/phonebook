<?php
function renderAdd() {
    $db = new PDO('sqlite:' . __DIR__ . '/phonebook.db');
    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
        $lastname   = trim($_POST['lastname'] ?? '');
        $firstname  = trim($_POST['firstname'] ?? '');
        $middlename = trim($_POST['middlename'] ?? '');
        $gender     = trim($_POST['gender'] ?? '');
        $birthdate  = trim($_POST['birthdate'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');
        $address    = trim($_POST['address'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $comment    = trim($_POST['comment'] ?? '');

        if ($lastname !== '' && $firstname !== '') {
            $stmt = $db->prepare("INSERT INTO contacts 
                (lastname, firstname, middlename, gender, birthdate, phone, address, email, comment)
                VALUES (:lastname, :firstname, :middlename, :gender, :birthdate, :phone, :address, :email, :comment)");

            $result = $stmt->execute([
                ':lastname'   => $lastname,
                ':firstname'  => $firstname,
                ':middlename' => $middlename,
                ':gender'     => $gender,
                ':birthdate'  => $birthdate,
                ':phone'      => $phone,
                ':address'    => $address,
                ':email'      => $email,
                ':comment'    => $comment,
            ]);

            if ($result) {
                $message = '<p class="msg-success">Запись добавлена</p>';
            } else {
                $message = '<p class="msg-error">Ошибка: запись не добавлена</p>';
            }
        } else {
            $message = '<p class="msg-error">Ошибка: фамилия и имя обязательны</p>';
        }
    }

    $html = $message;
    $html .= '<form method="POST" action="index.php?page=add">';
    $html .= '<input type="hidden" name="action" value="add">';

    $fields = [
        'lastname'   => 'Фамилия *',
        'firstname'  => 'Имя *',
        'middlename' => 'Отчество',
        'phone'      => 'Телефон',
        'address'    => 'Адрес',
        'email'      => 'E-mail',
        'comment'    => 'Комментарий',
    ];

    foreach ($fields as $name => $label) {
        $html .= '<div class="form-group">';
        $html .= "<label>{$label}</label>";
        if ($name === 'comment') {
            $html .= "<textarea name='{$name}'></textarea>";
        } else {
            $html .= "<input type='text' name='{$name}'>";
        }
        $html .= '</div>';
    }

    // Пол
    $html .= '<div class="form-group">';
    $html .= '<label>Пол</label>';
    $html .= '<select name="gender">';
    $html .= '<option value="">— не указан —</option>';
    $html .= '<option value="м">Мужской</option>';
    $html .= '<option value="ж">Женский</option>';
    $html .= '</select>';
    $html .= '</div>';

    // Дата рождения
    $html .= '<div class="form-group">';
    $html .= '<label>Дата рождения</label>';
    $html .= '<input type="date" name="birthdate">';
    $html .= '</div>';

    $html .= '<button type="submit" class="btn">Добавить запись</button>';
    $html .= '</form>';

    return $html;
}
?>