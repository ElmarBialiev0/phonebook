<?php
function renderEdit() {
    $db = new PDO('sqlite:' . __DIR__ . '/phonebook.db');
    $message = '';
    $button = 'Сохранить изменения';

    $contacts = $db->query("SELECT id, surname, name FROM contacts ORDER BY surname, name")->fetchAll(PDO::FETCH_ASSOC);

    if (empty($contacts)) {
        return '<p>Записей нет.</p>';
    }

    $currentId = $_GET['id'] ?? $contacts[0]['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['button'])) {
        $id       = $_POST['id'] ?? 0;
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
            $stmt = $db->prepare("UPDATE contacts SET
                surname=:surname, name=:name, lastname=:lastname,
                gender=:gender, date=:date, phone=:phone,
                location=:location, email=:email, comment=:comment
                WHERE id=:id");

            $result = $stmt->execute([
                ':id'       => $id,
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

            $currentId = $id;
            $contacts = $db->query("SELECT id, surname, name FROM contacts ORDER BY surname, name")->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                $message = '<p class="success">Запись обновлена</p>';
            } else {
                $message = '<p class="error">Ошибка: запись не обновлена</p>';
            }
        } else {
            $message = '<p class="error">Ошибка: фамилия и имя обязательны</p>';
        }
    }

    $stmt = $db->prepare("SELECT * FROM contacts WHERE id = :id");
    $stmt->execute([':id' => $currentId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $row = $db->query("SELECT * FROM contacts ORDER BY surname, name LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $currentId = $row['id'];
    }

    // Список контактов
    $html = '<div class="div-edit">';
    foreach ($contacts as $c) {
        $active = ($c['id'] == $currentId) ? 'currentRow' : '';
        $html .= "<a href='index.php?page=edit&id={$c['id']}' class='{$active}'>{$c['surname']} {$c['name']}</a><br>";
    }
    $html .= '</div>';

    $html .= $message;

    // Форма
    $html .= "<form name='form_add' method='post' action='index.php?page=edit&id={$currentId}'>";
    $html .= '<div class="column">';
    $html .= "<input type='hidden' name='id' value='{$currentId}'>";

    $html .= '<div class="add"><label>Фамилия</label><input type="text" name="surname" placeholder="Фамилия" value="' . htmlspecialchars($row['surname']) . '"></div>';
    $html .= '<div class="add"><label>Имя</label><input type="text" name="name" placeholder="Имя" value="' . htmlspecialchars($row['name']) . '"></div>';
    $html .= '<div class="add"><label>Отчество</label><input type="text" name="lastname" placeholder="Отчество" value="' . htmlspecialchars($row['lastname']) . '"></div>';

    $html .= '<div class="add"><label>Пол</label>';
    $html .= '<select name="gender">';
    foreach (['' => '— не указан —', 'мужской' => 'мужской', 'женский' => 'женский'] as $val => $label) {
        $selected = ($row['gender'] === $val) ? 'selected' : '';
        $html .= "<option value='{$val}' {$selected}>{$label}</option>";
    }
    $html .= '</select></div>';

    $html .= '<div class="add"><label>Дата рождения</label><input type="date" name="date" value="' . htmlspecialchars($row['date']) . '"></div>';
    $html .= '<div class="add"><label>Телефон</label><input type="text" name="phone" placeholder="Телефон" value="' . htmlspecialchars($row['phone']) . '"></div>';
    $html .= '<div class="add"><label>Адрес</label><input type="text" name="location" placeholder="Адрес" value="' . htmlspecialchars($row['location']) . '"></div>';
    $html .= '<div class="add"><label>Email</label><input type="email" name="email" placeholder="Email" value="' . htmlspecialchars($row['email']) . '"></div>';
    $html .= '<div class="add"><label>Комментарий</label><textarea name="comment" placeholder="Краткий комментарий">' . htmlspecialchars($row['comment']) . '</textarea></div>';

    $html .= "<button type='submit' name='button' value='{$button}' class='form-btn'>{$button}</button>";
    $html .= '</div>';
    $html .= '</form>';

    return $html;
}
?>