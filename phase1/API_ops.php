<?php

$curl = curl_init();

$birthdate = $_GET['birthdate'] ?? '';
//$birthdate = '09-25';


curl_setopt_array($curl, [
    CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/actors/v2/get-born-today?today=$birthdate&first=20",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "X-RapidAPI-Host: online-movie-database.p.rapidapi.com",
        "X-RapidAPI-Key: 4d58f4bf56mshd3f02e5d74fdb1ep13ddd3jsn22293ebb7178"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);


if ($err) {
    echo "cURL Error #:" . $err;
    exit();
}


//    $curl = curl_init();
$response = json_decode($response, true)['data']['bornToday']['edges'];
//echo $response;
//exit();
$actorsIds = [];
foreach ($response as $res) {
    $actorsIds[] = $res['node']['id'];
}

//echo($actorsIds[0]);

$actorsNames = [];

foreach ($actorsIds as $id) {
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://online-movie-database.p.rapidapi.com/actors/v2/get-bio?nconst=$id",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "X-RapidAPI-Host: online-movie-database.p.rapidapi.com",
            "X-RapidAPI-Key: 4d58f4bf56mshd3f02e5d74fdb1ep13ddd3jsn22293ebb7178"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        exit();
    } else {
        $actorsNames[] = array(
            'name' => json_decode($response, true)['data']['name']['nameText']['text'],
            'imageUrl' => json_decode($response, true)['data']['name']['primaryImage']['url']
        );
    }
}

header('Content-Type: application/json');
echo json_encode($actorsNames);


curl_close($curl);


