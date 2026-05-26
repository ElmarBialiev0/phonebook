<?php
function renderViewer($sort = 'id', $pageNum = 1) {
    $db = new PDO('sqlite:' . __DIR__ . '/phonebook.db');

    $perPage = 10;
    $offset = ($pageNum - 1) * $perPage;

    // Допустимые поля сортировки
    $allowedSorts = ['id', 'lastname', 'birthdate'];
    if (!in_array($sort, $allowedSorts)) {
        $sort = 'id';
    }

    // Общее количество записей
    $total = $db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
    $totalPages = ceil($total / $perPage);

    // Получаем записи
    $stmt = $db->prepare("SELECT * FROM contacts ORDER BY {$sort} ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $html = '';

    if (empty($contacts)) {
        $html .= '<p>Записей нет.</p>';
        return $html;
    }

    // Таблица
    $html .= '<table>';
    $html .= '<tr>
        <th>#</th>
        <th>Фамилия</th>
        <th>Имя</th>
        <th>Отчество</th>
        <th>Пол</th>
        <th>Дата рождения</th>
        <th>Телефон</th>
        <th>Адрес</th>
        <th>E-mail</th>
        <th>Комментарий</th>
    </tr>';

    foreach ($contacts as $row) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['lastname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['firstname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['middlename']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['gender']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['birthdate']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['address']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

    // Пагинация
    if ($totalPages > 1) {
        $html .= '<div class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            $active = ($i === $pageNum) ? 'active' : '';
            $html .= "<a href='index.php?page=view&sort={$sort}&p={$i}' class='{$active}'>{$i}</a>";
        }
        $html .= '</div>';
    }

    return $html;
}
?>