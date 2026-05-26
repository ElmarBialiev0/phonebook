<?php
function renderViewer($sort = 'id', $pageNum = 1) {
    $db = new PDO('sqlite:' . __DIR__ . '/phonebook.db');

    $perPage = 10;
    $offset = ($pageNum - 1) * $perPage;

    $allowedSorts = ['id', 'surname', 'date'];
    if (!in_array($sort, $allowedSorts)) {
        $sort = 'id';
    }

    $total = $db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
    $totalPages = ceil($total / $perPage);

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
        $html .= '<td>' . htmlspecialchars($row['surname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['lastname']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['gender']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['date']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['location']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';

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