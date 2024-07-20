<?php

function retrive_post_request()
{
    //$full_name = 'hobaGamed';
//$username = 'hobaGamed';
//$birthdate = '1970-01-01';
//$phone = 'hobaGamed';
//$address = 'hobaGamed';
//$password = 'hobaGamed';
//$email = 'hobaGamed';
//$user_image = 'hobaGamed';

    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $birthdate = $_POST['birthdate'];
    $phone = $_POST['mobile_number'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $user_image = $_FILES['user_image']['tmp_name'];

    return [['full_name', 'username', 'birthdate', 'mobile_number', 'address', 'password', 'user_image', 'email'],
            [$full_name, $username, $birthdate, $phone, $address, $password, $user_image, $email]];
}