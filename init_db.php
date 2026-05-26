<?php
$db = new PDO('sqlite:' . __DIR__ . '/phonebook.db');

$db->exec("CREATE TABLE IF NOT EXISTS contacts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    lastname TEXT NOT NULL,
    firstname TEXT NOT NULL,
    middlename TEXT,
    gender TEXT,
    birthdate TEXT,
    phone TEXT,
    address TEXT,
    email TEXT,
    comment TEXT
)");

// Добавим тестовые записи
$db->exec("INSERT INTO contacts (lastname, firstname, middlename, gender, birthdate, phone, address, email, comment) VALUES
('Иванов', 'Иван', 'Иванович', 'м', '1990-01-15', '+7 900 123-45-67', 'Москва, ул. Ленина, 1', 'ivanov@mail.ru', 'Тестовая запись'),
('Петрова', 'Анна', 'Сергеевна', 'ж', '1985-05-20', '+7 900 234-56-78', 'Москва, ул. Мира, 5', 'petrova@mail.ru', ''),
('Сидоров', 'Алексей', 'Петрович', 'м', '1995-11-03', '+7 900 345-67-89', 'СПб, пр. Невский, 10', 'sidorov@mail.ru', ''),
('Козлова', 'Мария', 'Андреевна', 'ж', '1992-07-18', '+7 900 456-78-90', 'Москва, ул. Садовая, 3', 'kozlova@mail.ru', ''),
('Новиков', 'Дмитрий', 'Олегович', 'м', '1988-03-25', '+7 900 567-89-01', 'Казань, ул. Баумана, 7', 'novikov@mail.ru', '')
");

echo "База данных создана успешно!";
?>