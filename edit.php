<?php
function renderEdit() {
    $db = new PDO('sqlite:' . __DIR__ . '/phonebook.db');
    $message = '';

    // Получаем все контакты для списка
    $contacts = $db->query("SELECT id, lastname, firstname FROM contacts ORDER BY lastname, firstname")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($contacts)) {
        return '<p>Записей нет.</p>';
    }

    // Определяем текущий id
    $currentId = $_GET['id'] ?? $contacts[0]['id'];

    // Сохранение изменений
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
        $id         = $_POST['id'] ?? 0;
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
            $stmt = $db->prepare("UPDATE contacts SET
                lastname=:lastname, firstname=:firstname, middlename=:middlename,
                gender=:gender, birthdate=:birthdate, phone=:phone,
                address=:address, email=:email, comment=:comment
                WHERE id=:id");

            $result = $stmt->execute([
                ':id'         => $id,
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

            $currentId = $id;

            if ($result) {
                $message = '<p class="msg-success">Запись обновлена</p>';
                // Обновляем список после редактирования
                $contacts = $db->query("SELECT id, lastname, firstname FROM contacts ORDER BY lastname, firstname")->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $message = '<p class="msg-error">Ошибка: запись не обновлена</p>';
            }
        } else {
            $message = '<p class="msg-error">Ошибка: фамилия и имя обязательны</p>';
        }
    }

    // Получаем данные текущей записи
    $stmt = $db->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->execute([':id' => $currentId]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contact) {
        $contact = $db->query("SELECT * FROM contacts ORDER BY lastname, firstname LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $currentId = $contact['id'];
    }

    // Список контактов
    $html = '<div class="contact-list">';
    foreach ($contacts as $c) {
        $active = ($c['id'] == $currentId) ? 'active' : '';
        $html .= "<a href='index.php?page=edit&id={$c['id']}' class='{$active}'>{$c['lastname']} {$c['firstname']}</a>";
    }
    $html .= '</div>';

    $html .= $message;

    // Форма редактирования
    $html .= "<form method='POST' action='index.php?page=edit&id={$currentId}'>";
    $html .= '<input type="hidden" name="action" value="edit">';
    $html .= "<input type='hidden' name='id' value='{$currentId}'>";

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
        $value = htmlspecialchars($contact[$name] ?? '');
        $html .= '<div class="form-group">';
        $html .= "<label>{$label}</label>";
        if ($name === 'comment') {
            $html .= "<textarea name='{$name}'>{$value}</textarea>";
        } else {
            $html .= "<input type='text' name='{$name}' value='{$value}'>";
        }
        $html .= '</div>';
    }

    // Пол
    $html .= '<div class="form-group">';
    $html .= '<label>Пол</label>';
    $html .= '<select name="gender">';
    foreach (['' => '— не указан —', 'м' => 'Мужской', 'ж' => 'Женский'] as $val => $label) {
        $selected = ($contact['gender'] === $val) ? 'selected' : '';
        $html .= "<option value='{$val}' {$selected}>{$label}</option>";
    }
    $html .= '</select>';
    $html .= '</div>';

    // Дата рождения
    $birthdate = htmlspecialchars($contact['birthdate'] ?? '');
    $html .= '<div class="form-group">';
    $html .= '<label>Дата рождения</label>';
    $html .= "<input type='date' name='birthdate' value='{$birthdate}'>";
    $html .= '</div>';

    $html .= '<button type="submit" class="btn">Сохранить изменения</button>';
    $html .= '</form>';

    return $html;
}
?>