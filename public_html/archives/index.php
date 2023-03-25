<!DOCTYPE html>
<!-- saved from url=(0087)https://www.cs.virginia.edu/~up3f/cs4750/inclass/get-started-db-pgm/template/index.html -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
  <!-- https://www.cs.virginia.edu/~lta9vw/index.html -->
  <!-- 2. include meta tag to ensure proper rendering and touch zooming -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- 
  Bootstrap is designed to be responsive to mobile.
  Mobile-first styles are part of the core framework.
   
  width=device-width sets the width of the page to follow the screen-width
  initial-scale=1 sets the initial zoom level when the page is first loaded   
  -->
  
  <meta name="author" content="your name">
  <meta name="description" content="include some description about your page">  
    
  <title>POTD 5</title>
  
  <!-- 3. link bootstrap -->
  <!-- if you choose to use CDN for CSS bootstrap -->  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  
  <!-- you may also use W3's formats -->
  <!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
  
  <!-- 
  Use a link tag to link an external resource.
  A rel (relationship) specifies relationship between the current document and the linked resource. 
  -->
  
  <!-- If you choose to use a favicon, specify the destination of the resource in href -->
  <link rel="icon" type="image/png" href="https://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png">
  
  <!-- if you choose to download bootstrap and host it locally -->
  <!-- <link rel="stylesheet" href="path-to-your-file/bootstrap.min.css" /> --> 
  
  <!-- include your CSS -->
  <!-- <link rel="stylesheet" href="custom.css" />  -->
       
<meta id="Reverso_extension___elForCheckedInstallExtension" name="Reverso extension" content="2.2.203"></head>

<body>
<div class="container">
  <h1>Friend book</h1>   
  <!-- CDN for JS bootstrap -->
  <!-- you may also use JS bootstrap to make the page dynamic -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <form name="mainForm" action="index.php" method="post">   
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
    /** S23, PHP (on GCP, local XAMPP, or CS server) connect to MySQL (on CS server) **/
    $username = 'lta9vw'; 
    $password = 'UpsornWinter2023';
    $host = 'mysql01.cs.virginia.edu';
    $dbname = 'lta9vw';
    $dsn = "mysql:host=$host;dbname=$dbname";
    $db = null; 
    try 
    {
      $db = new PDO($dsn, $username, $password);
      echo "<p>You are connected to the database: $dsn</p>";
    }
    catch (PDOException $e)     // handle a PDO exception (errors thrown by the PDO library)
    {
      $error_message = $e->getMessage();        
      echo "<p>An error occurred while connecting to the database: $error_message </p>";
    }
    catch (Exception $e)       // handle any type of exception
    {
      $error_message = $e->getMessage();
      echo "<p>Error message: $error_message </p>";
    }
    if (isset($_POST['name']) && isset($_POST['major']) && isset($_POST['year']) && isset($db)) { 
      insert($db,  $_POST['name'], $_POST['major'], $_POST['year']);
    }

    function insert($db, $name, $major, $year) {
        $stmt = $db -> query ("INSERT IGNORE INTO friends (name, major, year)
        VALUES ('$name', '$major', '$year');");
        $stmt -> execute();
    }

    function delete($db, $name, $major, $year) {
      $stmt = $db -> query ("DELETE FROM friends WHERE name='$name' AND major='$major' AND year='$year';");
      $stmt -> execute();
    }

    function getTable($db) {
      // todo 
      $stmt = $db -> prepare ('SELECT * FROM friends');
      $stmt -> execute();
      $table = $stmt->fetchAll(PDO::FETCH_BOTH);
      return $table; 
    }

?>
  <h1>List of Friends</h1>
  <div class="row justify-content-center">
    <h2>Double click buttons to get the table to reload</h2>
  <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
    <thead>
      <tr style="background-color:#B0B0B0">
        <th width="30%">Name</th>
        <th width="30%">Major</th>
        <th width="30%">Year</th>
        <th width="30%">Delete ?</th>
      </tr>
    </thead>
    

    <?php 
    $table = getTable($db); $i = 0; foreach ($table as $row): ?>
      <tr>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['major']; ?></td>
        <td><?php echo $row['year']; ?></td>
        <td>
          <form method="post" action="index.php">
            <button type="submit" name="<?php echo "delete" . strval($i)?>" class="btn btn-danger" value="<?php echo $i ?>"><?php echo $i ?></button>
        </form>
        </td>
      </tr>
    <?php $i += 1; endforeach; ?>

    <?php
    foreach ($table as $key => $row):
      if (isset($_POST['delete' . $key])) {
        $row_idx = $key; // i.e the first, second, third row...
        $row = $table[$key]; // table[1]
        $name = $row['name'];
        $major = $row['major'];
        $year = $row['year'];
        delete($db, $name, $major, $year); 

      }
      
    endforeach;
    // header("Location: https://www.cs.virginia.edu/~lta9vw/index.php");
    ?>
  </table>
<h3>Update form - fill out and submit to update a row. <b>REFRESH PAGE FOR CHANGE</b></h3>
<form method="post" action="index.php">
  <div class="form-group">
    <label for="row">Row Number:</label>
    <input type="number" class="form-control" id="row" name="urow" required>
  </div>
  <div class="form-group">
    <label for="name">Name:</label>
    <input type="text" class="form-control" id="name" name="uname" required>
  </div>
  <div class="form-group">
    <label for="major">Major:</label>
    <input type="text" class="form-control" id="major" name="umajor" required>
  </div>
  <div class="form-group">
    <label for="year">Year:</label>
    <input type="number" class="form-control" id="year" name="uyear" required>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php
if (isset($_POST['urow'])) {
  $row = $_POST['urow']; 
  $name = $_POST['uname'];
  $major = $_POST['umajor']; 
  $year = $_POST['uyear']; 
  $oname = $table[$row]['name'];
  $omajor = $table[$row]['major'];
  $oyear = $table[$row]['year'];
  delete($db, $oname, $omajor, $oyear); 
  insert($db, $name, $major, $year);
}
?>



</div>
</div>    

</body></html>