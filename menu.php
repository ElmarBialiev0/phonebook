<?php
function renderMenu() {
    $page = $_GET['page'] ?? 'view';
    $sort = $_GET['sort'] ?? 'id';

    $html = '<nav>';
    $html .= '<div class="main-menu">';

    $items = [
        'view'   => 'Просмотр',
        'add'    => 'Добавление записи',
        'edit'   => 'Редактирование записи',
        'delete' => 'Удаление записи',
    ];

    foreach ($items as $key => $label) {
        $active = ($page === $key) ? 'active' : '';
        $html .= "<a href='index.php?page={$key}' class='{$active}'>{$label}</a>";
    }

    $html .= '</div>';

    // Подменю сортировки — только на странице просмотра
    if ($page === 'view') {
        $html .= '<div class="sub-menu">';

        $sorts = [
            'id'        => 'По порядку добавления',
            'lastname'  => 'По фамилии',
            'birthdate' => 'По дате рождения',
        ];

        foreach ($sorts as $key => $label) {
            $active = ($sort === $key) ? 'active' : '';
            $html .= "<a href='index.php?page=view&sort={$key}' class='{$active}'>{$label}</a>";
        }

        $html .= '</div>';
    }

    $html .= '</nav>';

    return $html;
}
?>