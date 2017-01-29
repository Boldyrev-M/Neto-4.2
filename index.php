<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28.01.2017
 * Time: 20:21
 */

try {
    //$mydb = new PDO("mysql:host=netology.ru;dbname=global;charset=UTF8","root","");
    $mydb = new PDO("mysql:host=127.0.0.1:8889;dbname=global;charset=UTF8","root","root");
} catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
}

echo "<h2>Домашнее задание к лекции 4.1 «Реляционные базы данных и SQL»</h2>";

if(!empty($_GET)){
    //$sql = "SELECT * FROM books WHERE author = :author";
}

$sql = "SELECT * FROM books";
echo "Запрос: \"".$sql."\"<br />";

$html = <<< FormSearch
<form method="GET">
    <input type="text" name="isbn" placeholder="ISBN" value="" />
    <input type="text" name="name" placeholder="Название книги" value="" />
    <input type="text" name="author" placeholder="Автор книги" value="" />
    <input type="submit" value="Поиск" />
</form>
FormSearch;
echo $html;

//  `books` (`id`, `name`, `author`, `year`, `isbn`, `genre`)
$tabhead = <<< TABH
<table border=1>
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Автор</th>
        <th>Год выпуска</th>
        <th>ISBN</th>
        <th>Жанр</th>
    </tr>
<tr>
TABH;

echo $tabhead;
foreach ($mydb->query($sql) as $row) {
    echo "<td>".$row['id'] ."</td><td>".$row['name'] ."</td><td>".$row['author'] ."</td>
    <td>".$row['year'] ."</td><td>".$row['isbn'] ."</td><td>".$row['genre'] ."</td>
    </tr>";
}
echo "</table>";
