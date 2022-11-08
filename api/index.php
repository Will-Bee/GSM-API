

<?php


// read secretHash from ../var/secret.json
$data = json_decode(file_get_contents('../var/secret.json'), true);
$secretHash = $data['secretHash'];



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


function apiResponse()
{
    // read data.json in variables $number and $text
    $data = json_decode(file_get_contents('../var/data.json'), true);
    $number = $data['number'];
    $text = $data['text'];

    // create array with data
    $data = array(
        'number' => $number,
        'text' => $text
    );

    // return data in JSON format
    echo json_encode($data);



    // log request
    $date = date('d/m/Y H:i:s');
    $number = $data['number'];
    $text = $data['text'];
    $ip = getUserIP();

    $data = "REQUEST: Date: $date | IP Address> $ip | Number: $number Text: $text \n";

    file_put_contents('../var/log.txt', $data, FILE_APPEND);
}





// compares md5 of parameter secret with hash
if (md5($_GET['secret']) == $secretHash) {

    // if secret is correct, call apiResponse function
    apiResponse();


} else {

    // if secret is incorrect, return error
    echo "Error: Secret is incorrect";

}



?>
