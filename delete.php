<?php
function renderDelete() {
    $db = new PDO('sqlite:' . __DIR__ . '/phonebook.db');
    $message = '';

    if (isset($_GET['delete_id'])) {
        $id = $_GET['delete_id'];

        $stmt = $db->prepare("SELECT surname FROM contacts WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $contact = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($contact) {
            $surname = htmlspecialchars($contact['surname']);
            $db->prepare("DELETE FROM contacts WHERE id = :id")->execute([':id' => $id]);
            $message = "<p class='success'>Запись с фамилией {$surname} удалена</p>";
        } else {
            $message = "<p class='error'>Ошибка: запись не найдена</p>";
        }
    }

    $contacts = $db->query("SELECT id, surname, name, lastname FROM contacts ORDER BY surname, name")->fetchAll(PDO::FETCH_ASSOC);

    $html = $message;

    if (empty($contacts)) {
        $html .= '<p>Записей нет.</p>';
        return $html;
    }

    $html .= '<div class="contact-list">';
    foreach ($contacts as $c) {
        $initials = mb_substr($c['name'], 0, 1) . '.';
        if (!empty($c['lastname'])) {
            $initials .= ' ' . mb_substr($c['lastname'], 0, 1) . '.';
        }
        $name = htmlspecialchars($c['surname'] . ' ' . $initials);
        $html .= "<a href='index.php?page=delete&delete_id={$c['id']}' 
                     onclick=\"return confirm('Удалить {$name}?')\">{$name}</a>";
    }
    $html .= '</div>';

    return $html;
}
?>