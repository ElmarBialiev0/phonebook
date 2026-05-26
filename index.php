<?php
require_once 'menu.php';
require_once 'viewer.php';
require_once 'add.php';
require_once 'edit.php';
require_once 'delete.php';

$page = $_GET['page'] ?? 'view';
$sort = $_GET['sort'] ?? 'id';
$pageNum = (int)($_GET['p'] ?? 1);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Записная книжка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php echo renderMenu(); ?>

<main>
    <?php
    switch ($page) {
        case 'view':
            echo renderViewer($sort, $pageNum);
            break;
        case 'add':
            echo renderAdd();
            break;
        case 'edit':
            echo renderEdit();
            break;
        case 'delete':
            echo renderDelete();
            break;
        default:
            echo renderViewer($sort, $pageNum);
    }
    ?>
</main>

<footer>
    <p>Задание для самостоятельной работы «Phonebook»</p>
</footer>

</body>
</html>