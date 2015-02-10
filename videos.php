<?php
    ini_set('display_errors', 'On');
    include 'dbinfo.php';
    
    $mysqli = new mysqli($db_host, $db_user, $db_pwd, $db_database);
    if(!$mysqli || $mysqli->connect_errno)
    {

        echo "Connection error: " . $mysqli->connect_errno ." ".$mysqli->connect_error;
    }

    //$stmnt = $mysqli.prepare("Select where id=?")
    // $stmnt.bind_param("s", $id)
    // $stmnt.execute()
    // $stmnt.bind_result($result)
    
    $q = 'INSERT INTO video (name,category,length,rented) VALUES ("Frank", "Comedy", 90,True)';
    //echo $mysqli->query($q);

    allVids($mysqli);
    getCatList($mysqli);

    function allVids($sql_handle)
    {
        $q = "SELECT * FROM video";
        $result = $sql_handle->query($q);
        $rows = $result->fetch_all();
        //$stmnt->execute();
        //$stmnt->bind_result($result);
        echo '<table border="1">';
        echo "<tr><th>id</th><th>Title</th><th>Category</th><th>Runtime</th><th>Status</th>";
        echo "<tr>";
        //echo $rows[0];
        foreach ($rows as $value) {
          $rent_status = "Available";
          $rent_btn_txt = "Check out";
          $id = $value[0];
          $name = $value[1];
          $cat = $value[2];
          $len = $value[3];
          $rented = $value[4];
          if($rented)
          {
            $rent_status = "Checked Out";
            $rent_btn_txt = "Check in";
          }
          echo"<td>$id</td><td>$name</td><td>$cat</td><td>$len</td><td>$rent_status</td>";
          echo "<td><form action='checkout.php' method='GET'><input type='hidden' name='vid_id' value='$id'>"."<input type='submit' value='$rent_btn_txt'></form></td>";
          echo "<td><form action='remove.php' method='GET'><input type='hidden' name='vid_id' value='$id'>".'<input type="submit" value="Delete"></form></td>';          

          echo "<tr>";
        }  
        echo "</table>";
        echo "<form action='remove.php' method='GET'><input type='hidden' name='delete_all' value='True'>"."<input type='submit' value='Delete All Videos'></form>";
    };

    function getCatList($sql_handle)
    {
        $q = "SELECT DISTINCT category FROM video ORDER BY category ASC" ;
        $result = $sql_handle->query($q);
        $rows = $result->fetch_all();

        echo "<select>";
        foreach ($rows as $value)
        {
            $cat = $value[0];
            echo "<option value='$cat'>$cat</option>";
    # code...
        }
        echo "</select>";


    }
    function filterCats()
    {


    };

    function addVid()
    {

    };

    function deleteVid()
    {


    }
?>