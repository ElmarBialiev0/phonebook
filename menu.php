<?php
function renderMenu() {
    $page = $_GET['page'] ?? 'view';
    $sort = $_GET['sort'] ?? 'id';

    $html = '<header>';

    $items = [
        'view'   => 'Просмотр',
        'add'    => 'Добавление записи',
        'edit'   => 'Редактирование записи',
        'delete' => 'Удаление записи',
    ];

    foreach ($items as $key => $label) {
        $active = ($page === $key) ? 'select' : '';
        $html .= "<a href='index.php?page={$key}' class='{$active}'>{$label}</a>";
    }

    $html .= '</header>';

    // Подменю сортировки — только на странице просмотра
    if ($page === 'view') {
        $html .= '<div class="submenu">';

        $sorts = [
            'id'       => 'По порядку добавления',
            'surname'  => 'По фамилии',
            'date'     => 'По дате рождения',
        ];

        foreach ($sorts as $key => $label) {
            $active = ($sort === $key) ? 'select' : '';
            $html .= "<a href='index.php?page=view&sort={$key}' class='{$active}'>{$label}</a>";
        }

        $html .= '</div>';
    }

    return $html;
}
?>