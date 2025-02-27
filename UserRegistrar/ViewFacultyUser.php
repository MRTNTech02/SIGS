<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (empty($_SESSION["id_number"]) && empty ($_SESSION["user_password"])) {
    header("location: index.php");
  }
  if (!empty($_SESSION["id_number"])){
    $user_id = $_SESSION["user_id"];

    $sql = "SELECT * FROM users_tbl WHERE user_id=$user_id";
    try{
      $result = $conn->prepare($sql);
      $result->execute();

      if($result->rowCount()>0){
        $data = $result->fetch(PDO::FETCH_ASSOC);
        $registrar_name = $data['u_fname'] . ' ' . $data['u_lname'] . ' ' . $data['u_suffix'] ;
        $id_number = $data['id_number'];
      }
    }catch(Exception $e){
      echo "Error" . $e;
    }
  };
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['id_number']);
    header("location: index.php");
    }
?>
<!-- fetching faculty record -->
<?php
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];

        $sql = "SELECT * FROM users_tbl WHERE user_id='$user_id'";
        try{
        $result = $conn->prepare($sql);
        $result->execute();

        if($result->rowCount()>0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $id_number = $data["id_number"];
            $u_fname = $data["u_fname"];
            $u_mname = $data["u_mname"];
            $u_lname = $data["u_lname"];
            $u_suffix = $data["u_suffix"];
            $user_fullname = $data["u_fname"] . " " . $data["u_lname"];
            $u_sex = $data["u_sex"];
            $u_birthdate = $data["u_birthdate"];
            $user_email = $data["user_email"];
            $role = $data["role"];
            $user_password = $data["user_password"];
            $user_profile = $data["user_profile"];
            $user_status = $data["user_status"];
        }
        }catch(Exception $e){
        echo "Error" . $e;
        }
    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
</head>
<body>
    <?php
        include '../Assets/components/RegistrarNavbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/RegistrarSidebar.php';
        ?>
        <!-- Main content -->
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">User Information</h4>
            <div class="main-content">
                <div class="container">
                        <div class="main">
                            <div class="profile-info-container">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="box2" style="margin-top: 10px; margin-bottom: 10px; height:auto; width: 100%">
                                            <?php 
                                                if (empty($user_profile)){
                                                    echo " 
                                                        <img src='../Assets/img/profile_pictures/userdefaultprofile.jpg' alt='Huhu' class='avatar  mx-auto d-block'>   
                                                    ";
                                                }else{
                                                    echo " 
                                                        <img src='../Assets/img/profile_pictures/$user_profile' alt='else' class='avatar  mx-auto d-block'>
                                                    ";
                                                }
                                            ?>
                                        </div> 
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Full Name: <?php echo $user_fullname ?> </label>
                                            </div>
                                            <div class="col-12">
                                                <label>ID Number: <?php echo $id_number ?> </label>
                                            </div>
                                            <div class="col-12">
                                                <label>Role: <?php echo $role ?> </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="assigned-subjects">
                                <h5 class="text-success mb-4">Assigned Sections</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="studentsTable">
                                        <thead align="center">
                                            <tr>
                                                <th>No.</th>
                                                <th>Subject</th>
                                                <th>Grade Level</th>
                                                <th>Strand</th>
                                                <th>Section</th>
                                                <th>Progress</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody" align="center">
                                            <?php
                                                $sql = "SELECT FA.f_assignment_id, A.subject_name, B.yl_name, C.strand_nn, D.section_name FROM
                                                faculty_assignments_tbl AS FA INNER JOIN subjects_tbl AS A ON FA.fk_subject_id=A.subject_id
                                                INNER JOIN year_levels_tbl AS B ON FA.fk_year_id=B.year_level_id 
                                                INNER JOIN strands_tbl AS C ON FA.fk_strand_id=C.strand_id 
                                                INNER JOIN sections_tbl AS D ON FA.fk_section_id=section_id 
                                                INNER JOIN users_tbl As E ON FA.fk_user_id=E.user_id WHERE FA.fk_user_id='$user_id'";
                                                try
                                                {
                                                    $result=$conn->prepare($sql);
                                                    $result->execute();
                                                    if($result->rowcount()>0)
                                                    {
                                                        $i=1;
                                                        while($row=$result->fetch(PDO::FETCH_ASSOC))
                                                        {
                                                            echo "
                                                                <tr>
                                                                    <td class='text-center'>{$i} </td>
                                                                    <td class='text-center'>{$row["subject_name"]}</td>
                                                                    <td>{$row["yl_name"]}</td>
                                                                    <td>{$row["strand_nn"]} </td>
                                                                    <td>{$row["section_name"]} </td>
                                                                    <td>In Progress (not dynamic yet)</td>";
                                                                    ?>
                                                                    <td class='text-center'>
                                                                        <?php 
                                                                        echo "
                                                                            <a href='ViewAssignedSubject.php?f_assignment_id={$row["f_assignment_id"]}' class='btn btn-info btn-sm'>
                                                                                <i class='fas fa-eye'></i>
                                                                            </a>
                                                                        "; 
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            $i++;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        echo "<tr><tdv colspan = '7'> No records found. </td></tr>";
                                                    }
                                                }
                                                catch(Exception $e)
                                                {
                                                    echo "Unexpected error has been occured!" . $e ->getMessage();
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .table-container { 
            margin-top: 20px; 
            min-width: 100%; 
        }
        .table-responsive {
            height: 375px;
        }
        .content { 
            transition: margin-left 0.3s ease; 
        }
        @media (max-width: 768px) {
            .d-flex {
                flex-wrap: wrap;  
            }
            .d-flex > * {
                margin-bottom: 5px; 
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
