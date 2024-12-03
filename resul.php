<?php
    //PHP 8
    //MySQLi
    //

    /* REMEMBER TO REMOVE THIS FOR SECURITY! */
    ###########################################
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "student";
    ##########################################


    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        $servername = $username = $password = $database = NULL;
        exit("<b>Sorry, something went wrong</b>");
    }
    //If success, remove variable values
    $servername = $username = $password = $database = NULL;

    function containsOnlyNumbers($string) {
        if(preg_match('/^\d+$/', $string)) {
            $string = (int) $string;
            if($string > 0) {
                return true;
            }
        }
        return false;
    }
    if(isset($_POST["tt1"]) && isset($_POST["tt2"])) {
        $student_id = $_POST["tt1"];
        $student_mark = $_POST["tt2"];
        $student_id = trim($student_id);
        $student_mark = trim($student_mark);
        if(!empty($student_id) && !empty($student_mark)) {

            //check if data is valid
            if(containsOnlyNumbers($student_id) && containsOnlyNumbers($student_mark)) {

                $conn->begin_transaction();
                try {
                    //use prepared statement to prevent SQL injection
                    $stmt = $conn->prepare("UPDATE results SET mark = ? WHERE student_id = ?");
                    $stmt->bind_param("ii", $student_mark, $student_id);
                    $stmt->execute();
                    $conn->commit();
                    $stmt->close();
                    exit("Marks entered successfully");

                } catch (Exception $e) {
                    $conn->rollback();
                    exit("Sorry something went wrong");
                }
            } else {
                echo "Please enter valid information";
                exit();
            }
        } else {
            echo "Please enter valid information";
            exit();
        }
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Results</title>
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="css/normalize.css">


    <link rel="stylesheet" href="css/style.css">
</head>
    
        <div class="form">

            <div class="">

                <div id="login">
                    <h1>ADD STUDENT MARK</h1>

                    <form method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">

                        <div class="field-wrap">
                            <label>
                                Student ID<span class="req">*</span>
                            </label>
                            <input name="tt1" type="number" required />
                        </div>

                        <div class="field-wrap">
                            <label>
                                Mark<span class="req">*</span>
                            </label>
                            <input name="tt2" type="number" required />
                        </div>

                        <input type="submit" value="Submit" name="s2" class="button button-block" />

                    </form>

                </div>

            </div>
            <!-- tab-content -->

        </div>
        <!-- /form -->

        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

        <script src="js/results.js"></script>

        </body>

</html>