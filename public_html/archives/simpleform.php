<form name="mainForm" action="simpleform.php" method="post">   
  <div class="row mb-3 mx-3">
    Name
    <input type="text" class="form-control" name="name" required />        
  </div>  
  <div class="row mb-3 mx-3">
    Major
    <input type="text" class="form-control" name="major" required />        
  </div>  
  <div class="row mb-3 mx-3">
    Year
    <input type="text" class="form-control" name="year" required />        
  </div>  
  <button type="submit" class="btn btn-primary d-block mx-auto">Add</button>

</form>     

<?php
  if (isset($_POST['name']) && isset($_POST['major']) && isset($_POST['year'])) { 
  insert();
}


function insert() {
    $name = $_POST['name'];
    $major = $_POST['major'];
    $year = $_POST['year'];
    if (isset($db)) {
      echo "<p>db set!</p>";
      return;
    }
    echo "<p>Error on insert function. \$db is not set </p>";
    
}

?>
