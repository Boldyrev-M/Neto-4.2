<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 28.01.2017
 * Time: 20:21
 */

function clearing ($strclr) {
    //return (string) preg_replace("/[^\wа-яА-ЯёЁ\x7F-\xFF\s]/", " ", substr($strclr,0,30));
    $trimmed = substr($strclr, 0, 30);
    $matched = preg_match("/(.*)[^ a-zA-Z0-9а-яА-ЯёЁ]*/", $trimmed, $matches);
    if ($matched === false) {
        return ""; //prgmatcherror
    }
    else {
        if ($matched == 1) {
            return (string)$matches[1];
        }
        else {
            return $trimmed;
        }

    }
}


try {
    $mydb = new PDO("mysql:host=localhost;dbname=global;charset=UTF8","mboldyrev","neto0801");
    //$mydb = new PDO("mysql:host=127.0.0.1:8889;dbname=global;charset=UTF8","root","root");
} catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
}

echo "<h2>Домашнее задание к лекции 4.1 «Реляционные базы данных и SQL»</h2>";
$dbtabl = 'books';
$sql = "SELECT id, name, author, year, isbn, genre FROM books";

$gotisbn = (!empty($_GET["isbn"])) ? clearing($_GET["isbn"]) : "";
$gotname = (!empty($_GET["name"])) ?  clearing($_GET["name"]) : "";
$gotauthor = (!empty($_GET["author"])) ?  clearing($_GET["author"]) : "";

$clause1 = (strlen($gotisbn)) ? "isbn LIKE \"%" . $gotisbn."%\"" : "";
$clause2 = (strlen($gotname)) ? "name LIKE \"%" . $gotname."%\"" : "";
$clause3 = (strlen($gotauthor)) ? "author LIKE \"%" . $gotauthor."%\"" : "";

//echo "1: ".$clause1. "<br />2: ".$clause2. "<br />3: ".$clause3. "<br />";

$two1and2 = (strlen($clause1)*strlen($clause2)) ? ($clause1." AND ".$clause2) : ($clause1.$clause2) ;

//echo "1 и 2: ".$two1and2. "<br />";
$allthree = (strlen($two1and2)*strlen($clause3)) ? ($two1and2." AND ".$clause3) : ($two1and2.$clause3) ;

//echo "all: ".$allthree. "<br />";

if ( strlen($clause1.$clause2.$clause3)) {
    $sql .= " WHERE ".$allthree;
}


//echo "Запрос: \"".$sql."\"<br />";

$html = <<< FormSearch
<form method="GET">
    <input type="text" name="isbn" placeholder="ISBN" value="$gotisbn" />
    <input type="text" name="name" placeholder="Название книги" value="$gotname" />
    <input type="text" name="author" placeholder="Автор книги" value="$gotauthor" />
    <input type="submit" value="Поиск" />
</form>
FormSearch;
echo $html;

//  `books` (`id`, `name`, `author`, `year`, `isbn`, `genre`)
$tabhead = <<< TABH
<table border=1 bgcolor = #eeeeee>
    <tr bgcolor = #c0c0c0>
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
