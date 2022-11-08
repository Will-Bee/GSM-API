<! DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="yiewport" content="width=device-width, initial- Scale=1.0">
    <title></title>
    <link rel="stylesheet!" href=" ">
</head>

<body>

    <form action="index.php" method="post" autocomplete="off">

        <input type="text" name="number" placeholder="Number"> <br>

        <input type="text" name="text" placeholder="Text"> <br>

        <input type="password" name="password" placeholder="Password"> <br>

        <input type= "submit" name="submit" value="Submit!">


    </form>

</body>
</html>
<style>

* {
    font-family: consolas;
}

</style>

<!-- HTML Form -->





<?php

function getUserIP()
{
    // Get visitor IP address (Source: Stackoverflow)
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}


// read password from var/secret.json
$passHash = json_decode(file_get_contents('var/secret.json'), true);
$password = $passHash['password'];


if (isset($_POST['submit'])) {

    // Extract data from post request
    $number = $_POST['number'];
    $text = $_POST['text'];
    $_password = $_POST['password'];

    $_password = md5($_password);

    // Check if password is correct
    if ($_password == $password) {



        //? write number and text into api/data.json in json format, log changes

        // create array with data, convert to json and write to file api/var/data.json
        $data = array(
            'number' => $number,
            'text' => $text
        );

        $json = json_encode($data);
        file_put_contents('var/data.json', $json);

        echo "Data has been saved!";




        // log change in var/log.txt
        $ip = getUserIP();
        $date = date('d/m/Y H:i:s');

        $data = "CHANGE!: Date: $date | IP Address> $ip | Changed to -> Number: $number Text: $text \n";

        file_put_contents('var/log.txt', $data, FILE_APPEND);




    } else {
        // Wrong password entered
        echo "Wrong password!";
    }

}

?>
