<?php
$db = new PDO('sqlite:' . __DIR__ . '/phonebook.db');

$db->exec("CREATE TABLE IF NOT EXISTS contacts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    surname TEXT NOT NULL,
    name TEXT NOT NULL,
    lastname TEXT,
    gender TEXT,
    date TEXT,
    phone TEXT,
    location TEXT,
    email TEXT,
    comment TEXT
)");

$db->exec("INSERT INTO contacts (surname, name, lastname, gender, date, phone, location, email, comment) VALUES
('Иванов', 'Иван', 'Иванович', 'мужской', '1990-01-15', '+7 900 123-45-67', 'Москва, ул. Ленина, 1', 'ivanov@mail.ru', 'Тестовая запись'),
('Петрова', 'Анна', 'Сергеевна', 'женский', '1985-05-20', '+7 900 234-56-78', 'Москва, ул. Мира, 5', 'petrova@mail.ru', ''),
('Сидоров', 'Алексей', 'Петрович', 'мужской', '1995-11-03', '+7 900 345-67-89', 'СПб, пр. Невский, 10', 'sidorov@mail.ru', ''),
('Козлова', 'Мария', 'Андреевна', 'женский', '1992-07-18', '+7 900 456-78-90', 'Москва, ул. Садовая, 3', 'kozlova@mail.ru', ''),
('Новиков', 'Дмитрий', 'Олегович', 'мужской', '1988-03-25', '+7 900 567-89-01', 'Казань, ул. Баумана, 7', 'novikov@mail.ru', '')
");

echo "База данных создана успешно!";
?>