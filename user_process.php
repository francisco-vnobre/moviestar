<?php

        require_once("config/globals.php");
        require_once("config/db.php");
        require_once("models/User.php");
        require_once("models/Message.php");
        require_once("dao/UserDAO.php");

        $message = new Message($BASE_URL);

        $userDao = new UserDAO($conn, $BASE_URL);

        // Resgata o tipo do formulário
        $type = filter_input(INPUT_POST, "type");

        // Atualizar usuário
        if($type === "update") {

        // Resgata dados do usuário
        $userData = $userDao->verifyToken();

        // Receber dados do post
        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $bio = filter_input(INPUT_POST, "bio");

        // Criar um novo objeto de usuário
        $user = new User();

        // Preencher os dados do usuário
        $userData->name = $name;
        $userData->lastname = $lastname;
        $userData->email = $email;
        $userData->bio = $bio;

            // Upload da imagem
        if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
          
            $image = $_FILES["image"];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif" , "image/bmp"];
            $jpgArray = ["image/jpeg", "image/jpg"];
      
            //PEGANDO EXTENSÃO DO ARQUIVO
            $ext = strtolower(substr($image['name'],-4));
     
            // Checagem de tipo de imagem
            if(in_array($image["type"], $imageTypes)) {
      
              if($ext == ".jpg") {
      
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);
      
              } else if($ext == ".png") {
      
                $imageFile = imagecreatefrompng($image["tmp_name"]);
      
              } else {
     
                $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
     
              }
      
              $imageName = $user->imageGenerateName($ext);
              move_uploaded_file($_FILES["image"]["tmp_name"], "img/users/" . basename($_FILES["image"]["name"]));
              $userData->image = $imageName;
      
            } else {
      
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