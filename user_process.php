<?php
    require_once("config/globals.php");
    require_once("config/db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);

    $userDao = new UserDAO($conn, $BASE_URL);

    $type = filter_input(INPUT_POST, "type");

    if($type === "update") {
        // Resgata dados do usuário
        $userData = $userDao->verifyToken();

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $bio = filter_input(INPUT_POST, "bio");


        $userData->name = $name;
        $userData->lastname = $lastname;
        $userData->email = $email;
        $userData->bio = $bio;

        //Upload de imagem
        if(iseet($_FILES["images"]) && !empty($_FILES["images"]["tmp_name"])) {
            
            $image = $_FILES["images"];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray = ["image/jpeg", "image/jpg"];
            if(in_array($image["type"], $imageTypes)) {
                if(in_array($image, $jpgArray)) {
                    $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                }
                else {
                    $imageFile = imagecreatefrompng($image["tmp_name"]);
                }

                $imageName = $user->imageGenerateName();

                imagejpeg($imageFile, "./img/users/" . $imageName, 100);

                $userData->image = $imageName;
            }
            else {
                $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
            }
        }


        $userDao->update($userData);
    }
    else if($type === "changepassword") {

    }
    else {
        $message->setMessage("Informações inválidas!", "error", "index.php");
    }