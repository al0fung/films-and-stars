<!DOCTYPE html>
<html>
 <head>
  <title>Film &amp; Star Database</title>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
 </head>
 <?php
  $dbServerName = "localhost";
  $dbUsername = "root";
  $dbPassword = "abcdefg";
  $dbName = "sakila";
  $conn = new mysqli($dbServerName, $dbUsername, $dbPassword, $dbName);
 ?>
 <body class="container-fluid">
  <header>
   <h1>Film &amp; Star Database</h1>
   <span></span>
  </header>
  <div class="row">
   <div id="browseWrapper" class="col-md-4">
    <div id="browse">
     <div id="title">Browse Films by Title:
      <?php
       foreach(range('A', 'Z') as $letter){
        echo "<a>$letter</a>";
       }
      ?>
     </div>
     <hr>
     <div id="len">Browse Films by Length (minutes):
      <?php
       foreach(range(0,180,30) as $minute) {
        echo "<a>$minute - " . ($minute + 30) . "</a>";
       }
      ?>
     </div>
     <hr>
     <div id="rating">Browse Films by Rating:
      <?php
       $statement = "select distinct rating from film order by rating";
       $result = $conn->query($statement);
       if ($result->num_rows > 0) {
        while($output = $result->fetch_assoc()){
         echo "<a>" . $output["rating"] . "</a>";
        }
       }
      ?>
     </div>
     <hr>
     <div id="genre">Browse Films by Genre:
      <?php
       $statement = "select distinct name from category order by name";
       $result = $conn->query($statement);
       if ($result->num_rows > 0) {
        while($output = $result->fetch_assoc()){
         echo "<a>" . $output["name"] . "</a>";
        }
       }
      ?>
     </div>
     <hr>
     <div id="star">Browse Stars by Name:
      <?php
       foreach(range('A', 'Z') as $letter){
        echo "<a>$letter</a>";
       }
      ?>
     </div>
    </div>
   </div>
   <div id="outputWrapper" class="col-md-8"></div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script>
   $(document).ready(function(){
    $("#browse :not(#star) a").click(function(){
     var that = $(this);
     $("#outputWrapper").fadeOut(500, function() {
      $("#outputWrapper").html("<table id='output'><thead><tr><th>Title</th><th>Year</th><th>Length</th><th>Rating</th><th>Genre</th></tr></thead><tbody></tbody></table>");
      var getParam = {};
      getParam[that.parent().attr("id")] = that.html();
      $.getJSON("getData.php", getParam, function(data, status){
       for (var i = 0; i < data.length ; i++) {
        $("#output tbody").append("<tr><td>" + data[i].title + "</td><td>" + data[i].year + "</td><td>" + data[i].len + "</td><td>" + data[i].rating + "</td><td>" + data[i].genre + "</td></tr>");
       }
      })
      $("#outputWrapper").fadeIn(500);
     })
    })
    $("#browse #star a").click(function(){
     var that = $(this);
     $("#outputWrapper").fadeOut(500, function() {
      $("#outputWrapper").html("<table id='output'><thead><tr><th>Name</th></tr></thead><tbody></tbody></table>");
      var getParam = {};
      getParam[that.parent().attr("id")] = that.html();
      $.getJSON("getData.php", getParam, function(data, status){
       for (var i = 0; i < data.length ; i++) {
        $("#output tbody").append("<tr><td>" + data[i].name + "</td></tr>");
       }
      })
      $("#outputWrapper").fadeIn(500);
     })
    })
   })
  </script>
 </body>
</html>