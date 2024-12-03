<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Student Registration Form</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="css/default.css" />
</head>
<?php

session_start();
$password = $_SESSION['password'];
?>
<body>
    <form action="upload.php" method="post" enctype="multipart/form-data" class="register">
        <h1>Other details</h1>
        <fieldset class="row1">
            <legend>Enrollment Details
            </legend>
            <p>


                <label>Email Address *
                    </label>
                <input type="email" name="email" required/>
                <label>Upload image * (Only .jpg file)
                    </label>

                <input type="file" name="fileToUpload" id="fileToUpload" required onchange="check(this)">
                <script language='javascript' type='text/javascript'>
                    function check(input) {
                        var _validFileExtensions = [".jpg"];
                        if (input.type == "file") {
                            var sFileName = input.value;
                            if (sFileName.length > 0) {
                                var blnValid = false;
                                for (var j = 0; j < _validFileExtensions.length; j++) {
                                    var sCurExtension = _validFileExtensions[j];
                                    if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                                        blnValid = true;
                                        break;
                                    }
                                }

                                if (!blnValid) {
                                    alert("Sorry, " + sFileName + " is invalid, allowed extension is: " + _validFileExtensions.join(", "));
                                    input.value = '';
                                    return false;
                                }
                            }

                        }
                    }
                </script>


            </p>
            <p>
                <label>Roll number*
                    </label>
                <input type="number" name="rno" required/>


            </p>
            <p>
                <label>Hostel ID *
                    </label>
                <input type="text" maxlength="10" name="hostelid" required/>
            </p>
        </fieldset>
        <br>
        <fieldset class="row2">
            <legend>Personal Details
            </legend>
            <p>
                <label>Father's/ Guardian's Name *
                    </label>
                <input type="text" class="long" name="guardianname" required/>
            </p>

            <p>
                <label>Address *
                    </label>
                <input type="text" class="long" name="addr" required/>
            </p>

        </fieldset>
        <br>
        <fieldset class="row3">
            <legend>Further Information
            </legend>
            <p>
                <label>Gender *</label>
                <input type="radio" value="male" name="gender" />
                <label class="gender">Male</label>
                <input type="radio" value="female" name="gender" />
                <label class="gender">Female</label>
            </p>
            <p>
                <label>Birthdate *
                    </label>
                <input type="date" name="dob" required>
            </p>
            <p>
                <label>Nationality *
                    </label>
                <select name="nationality">
                        <option value="Zambian">Zambian
                        </option>
                        <option value="NRI">NRI
                        </option>
                    </select>
            </p>


        </fieldset>
        <br>
        <fieldset class="row4">
            <legend>Course details
            </legend>
            <p>
                <label>Select course *
                    </label>
                <select name="course" required>
					<?php
                                            /*
                                            $conn=mysqli_connect("localhost","root","","student");
                        if(!isset($conn))
                        {
                        echo"sorry!database connection error";
                        }
                        else
                        {
                        $query = "SELECT * FROM course";
                        $res = $conn->query($query);
                        while (($row=$res->fetch_row()) != null)
                        {
                            echo "<option value = '{$row[0]}'>{$row[1]}.{$row[2]}</option>";
                        }

                        }*/


                            // Establish connection to the database
                            $conn = mysqli_connect("localhost", "root", "", "student");

                            // Check connection
                            if (!$conn) {
                                // Connection failed, display an error message
                                die("Sorry! Database connection error");
                            } else {
                                // Query to select all records from the 'course' table
                                $query = "SELECT * FROM course";
                                
                                // Execute the query
                                $res = mysqli_query($conn, $query);

                                // Check if the query was successful
                                if ($res) {
                                    // Fetch and display each row as an option in the dropdown
                                    while ($row = mysqli_fetch_row($res)) {
                                        // Ensure proper escaping for HTML output to prevent XSS
                                        $value = htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8');
                                        $label = htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8') . '.' . htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8');
                                        echo "<option value='{$value}'>{$label}</option>";
                                    }
                                } else {
                                    // Query failed, display an error message
                                    echo "Sorry! There was an error fetching the data.";
                                }
                            }

                            // Close the database connection
                            mysqli_close($conn);

                        ?>

                    </select>
            </p>
            <p>
                <label>Semester *
                    </label>
                <select name="sem" required>
                    <option value="first semester">first semester</option>
                    <option value="second semester">second semester</option>
                </select>
            </p>
            <p class="agreement">
                <input type="checkbox" value="" required/>
                <label>*  I accept the <a href="#">Rules and Regulations</a> of being a student at Lusaka Health Institute</label>
            </p>

        </fieldset>

        <div> <input type="submit" class="button" value="Complete Registration" name="submit">

            <div>
			
                <?php
			        if($password=="amu_reg"){?>
                    <button class="button" onClick="document.location.href='admin_page.php'">Back</button>
                    <?php }else{?>
                    <button class="button" onClick="document.location.href='index.php'">Back</button>
                    <?php } ?>
                   
					</div>
        </div>
    </form>
</body>

</html>