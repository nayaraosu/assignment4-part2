<?php
    ini_set('display_errors', 'On');
    include 'dbinfo.php';
    
    $mysqli = new mysqli($db_host, $db_user, $db_pwd, $db_database);
    if(!$mysqli || $mysqli->connect_errno)
    {

        echo "Connection error: " . $mysqli->connect_errno ." ".$mysqli->connect_error;
    }
    else
    {
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
                $update_q = "UPDATE video SET rented=False WHERE id='$vid_id'";
                $mysqli->query($update_q);
            }
            else
            {
                $update_q = "UPDATE video SET rented=True WHERE id='$vid_id'";   
                $mysqli->query($update_q);
            }
        }

        if(isset($_GET['remove']))
        {
            $vid_id =  $_GET['remove'];
            $q = "DELETE FROM video where id='$vid_id'";
            $result = $mysqli->query($q);
        }    

        
        if(isset($_GET['add']) && $_GET['add'] === "Add Movie")
        {   
            addVid($mysqli);
        }

        getCatList($mysqli);
        getVids($mysqli,$cat);

        
        echo "<form action='videos.php' method='GET'>";
        echo '<label>Video Title: <input type="text" name="name"></label>';
        echo '<label>Category: <input type="text" name="category"></label>';
        echo '<label>Running Time: <input type="number" name="length"></label>';
        echo '<input type="submit" name="add" value="Add Movie">';
        echo '</form>';


    }



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
        echo "<tr><th>Title</th><th>Category</th><th>Runtime</th><th>Status</th>";
        echo "<tr>";
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
          echo"<td>$name</td><td>$cat</td><td>$len</td><td>$rent_status</td>";
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
        }
        echo "</select>";
        echo '<input type="submit" value="Filter"></form>';



    }
    function filterCats($sql_handle, $category)
    {


    };

    function addVid($sql_handle)
    {
        $valid = True;
        if (!isset($_GET['name']) || $_GET['name'] == "")
        {
            $valid = False;
            echo "<h1>Please enter a name!</h1><br>";
        }
        if (!isset($_GET['category']) || $_GET['category'] == "")
        {
            $valid = False;
            echo "<h1>Please enter a category!</h1><br>";
        }

        if (!isset($_GET['length']) || !is_numeric($_GET['length']) || strpos($_GET['length'], "."))
        {

            $valid = False;
            echo "<h1>Please enter a valid runtime!</h1><br>";
        }


        
        if($valid)
        {
            $name = $_GET['name'];
            $category = $_GET['category'];
            $length = $_GET['length'];
            $q = "INSERT INTO video (name, category, length) VALUES (?,?,?)";
            $stmnt = $sql_handle->prepare($q);
            $stmnt->bind_param('ssi', $name, $category, $length);
            $stmnt->execute();
            $stmnt->close();
        }


    };

    function deleteVid()
    {


    }
?>