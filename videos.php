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

    if(!isset($_GET['filter']))
    {
        $cat = "show_all";
    }
    else
    {
        $cat = $_GET['filter'];
    }

    if(isset($_GET['checkout']))
    {
        $vid_id =  $_GET['checkout'];
        $q = "SELECT rented FROM video where id='$vid_id'";
        $result = $mysqli->query($q);
        $rows = $result->fetch_all();
        $row = $rows[0];
        if ($row[0] == 1)
        {
            echo $update_q = "UPDATE video SET rented=False WHERE id='$vid_id'";
            $mysqli->query($update_q);
        }
        else
        {
            echo $update_q = "UPDATE video SET rented=True WHERE id='$vid_id'";   
            $mysqli->query($update_q);
        }
    }

    if(isset($_GET['remove']))
    {
        $vid_id =  $_GET['remove'];
        $q = "DELETE FROM video where id='$vid_id'";
        $result = $mysqli->query($q);
        echo $q;
    }    

    
    getCatList($mysqli);
    getVids($mysqli,$cat);

    function getVids($sql_handle, $category)
    {
        if($category == "show_all")
        {
            $q = "SELECT * FROM video";
        }
        else
        {
            $q = "SELECT * FROM video WHERE category='$category'";
        }

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
          echo "<td><form action='videos.php' method='GET'><input type='hidden' name='checkout' value='$id'>"."<input type='submit' value='$rent_btn_txt'></form></td>";
          echo "<td><form action='videos.php' method='GET'><input type='hidden' name='remove' value='$id'>".'<input type="submit" value="Delete"></form></td>';          

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
        echo "<form action='videos.php' method='GET'>";
        echo "<select name='filter'>";
        echo "<option value='show_all'>All Movies</option>";
        foreach ($rows as $value)
        {
            $cat = $value[0];
            echo "<option value='$cat'>$cat</option>";
    # code...
        }
        echo "</select>";
        echo '<input type="submit" value="Filter"></form>';



    }
    function filterCats($sql_handle, $category)
    {


    };

    function addVid()
    {

    };

    function deleteVid()
    {


    }
?>