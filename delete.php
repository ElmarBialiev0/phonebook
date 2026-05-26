<?php
function renderDelete() {
    $db = new PDO('sqlite:' . __DIR__ . '/phonebook.db');
    $message = '';

    // Удаление записи
    if (isset($_GET['delete_id'])) {
        $id = $_GET['delete_id'];

        $stmt = $db->prepare("SELECT lastname FROM contacts WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($contact) {
            $lastname = htmlspecialchars($contact['lastname']);
            $db->prepare("DELETE FROM contacts WHERE id = :id")->execute([':id' => $id]);
            $message = "<p class='msg-success'>Запись с фамилией {$lastname} удалена</p>";
        } else {
            $message = "<p class='msg-error'>Ошибка: запись не найдена</p>";
        }
    }

    // Получаем список контактов
    $contacts = $db->query("SELECT id, lastname, firstname, middlename FROM contacts ORDER BY lastname, firstname")->fetchAll(PDO::FETCH_ASSOC);

    $html = $message;

    if (empty($contacts)) {
        $html .= '<p>Записей нет.</p>';
        return $html;
    }

    $html .= '<div class="contact-list">';
    foreach ($contacts as $c) {
        $initials = mb_substr($c['firstname'], 0, 1) . '.';
        if (!empty($c['middlename'])) {
            $initials .= ' ' . mb_substr($c['middlename'], 0, 1) . '.';
        }
        $name = htmlspecialchars($c['lastname'] . ' ' . $initials);
        $html .= "<a href='index.php?page=delete&delete_id={$c['id']}' 
                     onclick=\"return confirm('Удалить {$name}?')\">{$name}</a>";
    }
    $html .= '</div>';

    return $html;
}
?>