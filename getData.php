<?php
 class Film
 {
  public $title = "";
  public $year = 0;
  public $len = 0;
  public $rating = "";
  public $genre = "";
 }
 class Actor
 {
  public $name = "";
 }
 if (!empty($_GET)) {
  $dbServerName = "localhost";
  $dbUsername = "root";
  $dbPassword = "abcdefg";
  $dbName = "sakila";
  $conn = new mysqli($dbServerName, $dbUsername, $dbPassword, $dbName);
  $statement = "";
  $keys = array_keys($_GET);
  $key = $keys[0];
  switch ($key) {
   case "title":
    $statement = "select title, release_year, length, rating, name from film f join film_category fc on f.film_id = fc.film_id join category c on fc.category_id = c.category_id where title like '" . $_GET[$key] . "%'";
    break;
   case "len":
    $minLen = [];
    preg_match("/^\d+/", $_GET[$key], $minLen);
    $statement = "select title, release_year, length, rating, name from film f join film_category fc on f.film_id = fc.film_id join category c on fc.category_id = c.category_id where length >= " . $minLen[0] . " and length <= " . ($minLen[0] + 30) . " order by length";
    break;
   case "rating":
    $statement = "select title, release_year, length, rating, name from film f join film_category fc on f.film_id = fc.film_id join category c on fc.category_id = c.category_id where rating = '" . $_GET[$key] . "'";
    break;
   case "genre":
    $statement = "select title, release_year, length, rating, name from film f join film_category fc on f.film_id = fc.film_id join category c on fc.category_id = c.category_id where name = '" . $_GET[$key] . "'";
    break;
  }
  if ($key !== "star") {
   $films = [];
   $result = $conn->query($statement);
   if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
     $film = new Film();
     $film->title = $row["title"];
     $film->year = $row["release_year"];
     $film->len = $row["length"];
     $film->rating = $row["rating"];
     $film->genre = $row["name"];
     $films[] = $film;
    }
   }
   echo json_encode($films);
  } else {
   $statement = "select concat(first_name, ' ', last_name) as name from actor where last_name like '$_GET[$key]%'";
   $actors = [];
   $result = $conn->query($statement);
   if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
     $actor = new Actor();
     $actor->name = $row["name"];
     $actors[] = $actor;
    }
   }
   echo json_encode($actors);
  }
 } else {
  echo "no get parameters passed";
 }
?>