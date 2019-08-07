<?php
include "kapcs.php";
ini_set('error_reporting', E_ALL);
?>
<!DOCTYPE html>
<html lang="hu-HU">
<head>
    <meta charset="UTF-8">
    <title>Risky Jobs - Search</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="hatter">
<div class="container">
    <h1 class="text-center">Risky Jobs</h1>
    <br>
    <p class="text-center">Danger! Your dream job is out there.
        <br>Dou you have the guts to go find it?</p>
    <br>
    <div>
        <fieldset class="border border-dark p-4">
            <div class="first">
                <h2>Risky Jobs - Registration</h2>
                <br>
                <?php
                $mutat = true;
                if (isset($_POST['submit'])) {
                    $fname = mysqli_real_escape_string($kapcs, trim($_POST['fname']));
                    $lname = mysqli_real_escape_string($kapcs, trim($_POST['lname']));
                    $email = mysqli_real_escape_string($kapcs, trim($_POST['email']));
                    $phone = (trim($_POST['phone']));
                    $job = mysqli_real_escape_string($kapcs, trim($_POST['job']));
                    $resume = mysqli_real_escape_string($kapcs, trim($_POST['resume']));

                    /*    /^\d\d\d\d$/  4 számjegyet fogad el egybeírva d= digit;
                          /^\d{4}$/     rövidebb alak;
                          w - betű + szám + _
                          s - szóköz, tabulátor, újsor, kocsivissza
                          ^ - elejétől nézze az egyezést, ne bárhol
                          . - újsor kivételével mindenre
                          $ - végét jelzi  keresett kifejezésnek (ha ez után valamit beírunk nem lesz rá érvényes a láncunk)
                          /70332/  - pontos egyezésre is használhatjuk
                          mennyiségjelzők:  {4} négy karaktert vár
                                            {2,4} 2,3 vagy 4 karaktert vár {min, max}
                                            + - egyszer vagy többször szerepelhet
                                            * - egyszer, többször vagy nem szerepel
                                            ? - egyszer vagy nem szerepel
                                pl.: /^\d{4}(-\d{2})?$/  - a kötőjel és a 2 számjegy ami a zárójelben van vagy szerepel vagy nem a ? jel miatt

                        Karakterosztályok: [0,4] - 1,2,3,4 számokat keresi meg, ezekre illeszkedik
                                           [^0,4] - ezekre nem illeszkedik
                                           /(mas\más|mos)/ | - több lehetsőség megadása
                     */

                    //telefonszám minta
                    $hiba = '';
                    $pattern = '/^([1-9])?\d-\d{3}-\d{3}(\d)?$/';
                    if (preg_match($pattern, $phone)) {
                    } else {
                        $hiba = "A telefonszám helyes formátuma: ##-###-#### \\n";
                    }
                    $phone = preg_replace('/-/', '', $phone);

                    //e-mail ellenőrzés A módszer (tartománynév érvényességgel)
//                    $pattern = '/^[a-zA-Z0-9][a-zA-Z0-9\._\-&!?=#]*@/';
//                    if(preg_match($pattern, $email)){
//                    }else{
//                        $hiba .= "Hibás e-mail cím \\n";
//                    }
//                    $tartomany = (explode('@',$email))[1];
//                    if(checkdnsrr($tartomany)){
//                    } else{ $hiba .= "Érvénytelen tartománynév \\n";}

                    //e-mail ellenőrzés B módszer
//                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                        $hiba .= 'invalid emailaddress';
//                    }

                    //e-mail ellenőrzés C módszer (előre megírt osztállyal)
                    include ('emailvalidator.php');
                    $validator = new \EmailAddressValidator\emailvalidator();
                    if ($validator->check_email_address($email))
                    { } else {$hiba.=  "Email not valid" ;}

                    if ($hiba != "") {
                        echo "<script language='JavaScript'>alert(\"$hiba\");</script>";
                    }
                    else{
                        $name = $fname . ' ' . $lname;
                        $querycv = "INSERT INTO cv (nev, email, telefonszam, kedvenc_munka, oneletrajz)
                              VALUES('$name', '$email', '$phone', '$job', '$resume')";
                        if (!mysqli_query($kapcs, $querycv)) {
                            printf("Error: %s\n", mysqli_error($kapcs));
                        }
                        $mutat=false;
                        echo "<p>$name, thanks for registering with Risky Jobs</p>";
                        echo "<p>Your phone number has been registered as $phone.</p>";
                    }
                }
                    $fname = '';
                    $lname = '';
                    $email = '';
                    $phone = '';
                    $job = '';
                    $resume = '';
                    if($mutat) {
                        ?>
                        <h6>Register with Risky Jobs, and post your resume:</h6>
                        <form action="<?php echo($_SERVER["PHP_SELF"]); ?>" method="post">
                            <label class="col-md-2">First name: </label>
                            <input class="col-md-2" type="text" name="fname" value="<?php echo $fname; ?>" required>
                            <div class="w-100"></div>
                            <label class="col-md-2">Last name: </label>
                            <input class="col-md-2" type="text" name="lname" value="<?php echo $lname; ?>" required>
                            <div class="w-100"></div>
                            <label class="col-md-2">E-mail: </label>
                            <input class="col-md-2" type="email" name="email" value="<?php echo $email; ?>" required>
                            <div class="w-100"></div>
                            <label class="col-md-2">Phone: </label>
                            <input class="col-md-2" type="text" name="phone" value="<?php echo $phone; ?>" required>
                            <div class="w-100"></div>
                            <label class="col-md-2">Desired job: </label>
                            <input class="col-md-2" type="text" name="job" value="<?php echo $job; ?>" required>
                            <div class="w-100"></div>
                            <br>
                            <label class="col-md-2">Paste your resume:</label>
                            <label class="col-md-2"></label>
                            <div class="w-100"></div>
                            <textarea name="resume" rows="3" cols="46" value="<?php echo $resume; ?>"
                                      required></textarea>
                            <div class="w-100"></div>
                            <input type="submit" name="submit" value="Submit"/>
                        </form>
                        <?php
                    }
                    ?>
        </fieldset>
    </div>
</div>
</body>
</html>

<?php
//ha a webkiszolgáló windows rendszerű ez kell
function win_checkdnsrr($tartomany, $recType = '')
{
    if (!empty($tartomany)) {
        if ($recType == '') $recType = "MX";
        exec("nlslookup -type=$recType $tartomany", $output);
        foreach ($output as $line) {
            if (preg_match("/^$tartomany/", $line)) {
                return true;
            }
        }
        return false;
    }
    return false;
}

?>

