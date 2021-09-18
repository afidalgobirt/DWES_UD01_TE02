<?php
    $idLetters = array(
        0 => "T", 1 => "R", 2 => "W", 3 => "A", 4 => "G", 5 => "M",
        6 => "Y", 7 => "F", 8 => "P", 9 => "D", 10 => "X", 11 => "B",
        12 => "N", 13 => "J", 14 => "Z", 15 => "S", 16 => "Q", 17 => "V",
        18 => "H", 19 => "L", 20 => "C", 21 => "K", 22 => "E",
    );

    $emailErrorMsg = "";
    $hireDateErrorMsg = "";
    $idErrorMsg = "";
    $phoneNumberErrorMsg = "";

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (isset($_GET["email"])) {
            $email = $_GET["email"];

            if ($email == "") {
                $error = true;
                $emailErrorMsg = "<span style='color:#FF0000'>* The field 'Email' must be filled out.</span>";
            } elseif (!str_contains($email, "@")) {
                $error = true;
                $emailErrorMsg = "<span style='color:#FF0000'>* Email addresses must contain the character '@'.</span>";
            }
        }

        if (isset($_GET["hireDate"])) {
            $hireDate = $_GET["hireDate"];

            try {
                if ($hireDate == "") {
                    $error = true;
                    $hireDateErrorMsg = "<span style='color:#FF0000'>* The field 'Hire Date' mustbe filled out.</span>";
                } else {
                    $handOutDate = date_format(date_add(new DateTime($hireDate), new DateInterval("P10D")), "Y-m-d");
                }
            } catch (Exception $e) { // Assume any error is caused because of an incorrect date format.
                $error = true;
                $hireDateErrorMsg = "<span style='color:#FF0000'>* The format must be 'YYYY-mm-dd'.</span>";
            }
        }

        if (isset($_GET["id"])) {
            $id = $_GET["id"];

            if ($id == "") {
                $error = true;
                $idErrorMsg = "<span style='color:#FF0000'>* The field 'ID' must be filled out.</span>";
            } else {
                try {
                    if (strLen($id) != 9) {
                        throw new Exception();
                    }
                    
                    $idNumber = subStr($id, 0, 8);

                    // The substring should only contain numbers.
                    for ($i = 0; $i < strLen($idNumber); $i++) {
                        if (!ctype_digit($idNumber[$i])) {
                            throw new Exception();
                        }
                    }

                    $correctLetter = $idLetters[intval($idNumber) % 23];

                    if ($correctLetter != subStr($id, 8, 1)) {
                        $error = true;
                        $idErrorMsg = "<span style='color:#FF0000'>* The letter should be $correctLetter.</span>";
                    }
                } catch (Exception $e) { // Assume any error is caused because of an incorrect ID format.
                    $error = true;
                    $idErrorMsg = "<span style='color:#FF0000'>* The format must be '12345678A'.</span>";
                }
            }
        }

        if (isset($_GET["phoneNumber"])) {
            $phoneNumber = $_GET["phoneNumber"];

            if ($phoneNumber == "") {
                $error = true;
                $phoneNumberErrorMsg = "<span style='color:#FF0000'>* The field 'Phone number' must be filled out.</span>";
            } else {
                if (strLen($phoneNumber) != 9) {
                    $error = true;
                    $phoneNumberErrorMsg = "<span style='color:#FF0000'>* The field 'Phone number' must be 9 characters long.</span>";
                } else {
                    for ($i = 0; $i < strLen($phoneNumber); $i++) {
                        if (!ctype_digit($phoneNumber[$i])) {
                            $error = true;
                            $phoneNumberErrorMsg = "<span style='color:#FF0000'>* The field 'Phone number' must only contain numbers.</span>";
                        }
                    }
                }
            }
        }
    }
?>

<html lang="en">
    <head>
        <title>DWES01 Tarea Evaluativa 02</title>
    </head>

    <body>
        <h1>DWES01 Tarea Evaluativa 02</h1>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="GET">
            <label for="name">Name</label>
            <input name="name" type="text"><br>
            <label for="surname">Surname</label>
            <input name="surname" type="text"><br>
            <label for="book">Book</label>
            <input name="book" type="text"><br>
            <label for="email">Email</label>
            <input name="email" type="text">
            <?php
                echo "$emailErrorMsg<br>"; // If validation is passed error message will be "".
            ?>
            <label for="hireDate">Hire date</label>
            <input name="hireDate" type="text" placeholder="YYYY-mm-dd">
            <?php
                echo "$hireDateErrorMsg<br>"; // If validation is passed error message will be "".
            ?>
            <label for="id">ID</label>
            <input name="id" type="text">
            <?php 
                echo "$idErrorMsg<br>"; // If validation is passed error message will be "".
            ?>
            <label for="phoneNumber">Phone number</label>
            <input name="phoneNumber" type="text">
            <?php 
                echo "$phoneNumberErrorMsg<br><br>";
            ?>

            <input type="submit">
        </form>

        <?php 
            // Avoid showing submitted data on first GET request by checking if name is set.
            if (isset($_GET["name"]) && !isset($error)) {
                echo "<h2>Sent Data:</h2>";
                echo "Name: $_GET[name]<br>";
                echo "Surname: $_GET[surname]<br>";
                echo "Book: $_GET[book]<br>";
                echo "Email: $_GET[email]<br>";
                echo "Hire Date: $_GET[hireDate] (hand out date: $handOutDate)<br>";
                echo "ID: $_GET[id]<br>";
                echo "Phone Number: $_GET[phoneNumber]<br>";
            }
        ?>
    </body>
</html>