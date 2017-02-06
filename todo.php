<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 05.02.2017
 * Time: 15:15
 */

error_reporting(E_ALL);

try {
    $mydb = new PDO("mysql:host=localhost;dbname=mboldyrev;charset=UTF8","mboldyrev","neto0801");
} catch (PDOException $e) {
    echo 'Подключение не удалось: ' . $e->getMessage();
}

echo "<h3>Домашнее задание к лекции 4.2 «Запросы SELECT, INSERT, UPDATE и DELETE»</h3>";
echo "<h2>Список дел</h2>";
$dbtabl = 'tasks';
$sql = "SELECT id,	description, is_done, date_added  FROM ".$dbtabl;

if(!empty($_POST["add_new"])) {
    $addnewtask = $mydb->prepare("INSERT INTO " . $dbtabl . " (description, is_done, date_added) VALUES ( ?, false, NOW())");
    $newtaskdescription = (string)$_POST["add_new"];
    $addnewtask->execute([$newtaskdescription]);
}
if (!empty($_POST["taskID"])) {
    $getid = (int) $_POST["taskID"];
    if (isset($_POST["butdel"])) {
        $sqlbutton = $mydb->prepare("DELETE FROM ".$dbtabl." WHERE id = ?");
        $sqlbutton->execute([$getid]);
    } // удалить задачу
    if (isset($_POST["butdone"])) {
        $sqlbutton = $mydb->prepare("UPDATE ".$dbtabl." SET is_done = !(is_done) WHERE id = ?");
        $sqlbutton->execute([$getid]);
    } // изменяем статус
    if (isset($_POST["butsave"])) {
        $sqlbutton = $mydb->prepare("UPDATE ".$dbtabl." SET description = ? WHERE id = ?");
        $sqlbutton->execute([$_POST["newdescr"],$getid]);
        unset($_POST["butsave"]);
        unset($_POST["newdescr"]);

    } // изменить  задачу

} // выбрана одна из трех кнопок


$html = <<< FormSearch
<form method="POST">
    <input type="text" name="add_new" placeholder="Описание" />
    <input type="submit" value="Добавить" />
</form>
FormSearch;
echo $html;

//  `tasks`
//  1	id,	description, is_done, date_added	datetime
$tabhead = <<< TABH
<table border=1>
    <tr>
        <th>ID</th>
        <th>Описание</th>
        <th>Статус</th>
        <th>Когда добавлена</th>
        <th>Сделать</th>
    </tr>
<tr>
TABH;

echo $tabhead;
foreach ($mydb->query($sql) as $row) {
    $eta = false;
    if ( isset($_POST["butedit"]) && $getid==$row['id']) {
        $eta = true;
    }
    echo "<tr><form name=adr" . $row['id'] . " method=\"POST\"><td>".$row['id'] ."</td>
    <td>". ( $eta
            ? "<input type=text name=\"newdescr\" value=\"" . $row['description'] . "\" autofocus>"
            : $row['description'] ) ."</td>
    <td><span style='color: ".($row['is_done']?"green;'>Выполнена":"red;'>В процессе") ."</span></td>
    <td>".$row['date_added'] ."</td><td>
    <input type=hidden name=\"taskID\" value=" . $row['id'] . ">
    <button name=". ($eta? ("\"butsave\" <span style='color: red;'>Сохранить</span>" ):"\"butedit\">Изменить")."</button>";
    if ($row['is_done']==false) {
        echo "<button name=\"butdone\">Выполнить</button>";
    } // эта задача выполнена
    else {
        echo "<button name=\"butdone\">Переделать</button>";
    } // эта задача не выполнена
    echo "<button name=\"butdel\">Удалить</button>";
    echo "</form></td></tr>\r\n";
}
echo "</table>";
