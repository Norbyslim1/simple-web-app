<?php
  require_once "config.php";

?>
<!DOCTYPE html>
<html>
   <head>
    <link rel="stylesheet" type="text/css" href="style.css">
      <h1>Simple Webapp</h1>
      <title>Title of the document</title>
      <style>
         a.button{
  display:inline-block;
  text-decoration:none;
  color:yellow;
  font-weight:bold;
  min-width:160px;
  min-height:37px;
  border:1px solid black;
  border-radius:12px;
  text-align:center;
  background-color:gray;
  box-shadow:8px 8px 8px gray;
  margin:10px;
  
  }
.button:active{
  background-color:blue;
position:relative;
  top:8px;
  left:8px;

}
      </style>
   </head>
    <body>
<a href="verify.php" class="button" target="_blank">pay new supplier</a>
<a href="transfer.php" class="button" target="_blank">Select from existing list of suppliers</a>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    </body>
</html>
