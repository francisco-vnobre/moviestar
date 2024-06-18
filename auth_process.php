<?php
    require_once("config/globals.php");
    require_once("config/db.php");
    require_once("models/User.php");
    require_once("models/Message.php");
    require_once("dao/UserDAO.php");

    $message = new Message($BASE_URL);

    $userDao = new UserDAO($conn, $BASE_URL);

    //Verifica o tipo do formulário
    $type = filter_input(INPUT_POST, "type");

    //Verificação do tipo de formulário
    if($type === "register") {
        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        // Verificação de dados mínimos
        if($name && $lastname && $email && $password) {
            // VErificar se as senhas batem
            if($password === $confirmpassword){
                // Verificar se o email já está cadastrado no sistema
                if($userDao->findByEmail($email) === false) {
                    $user = new User();

                    $userToken = $user->generateToken();
                    $finalPassword = $user->generatePassword($password);

                    $user->name = $name;
                    $user->lastname = $lastname;
                    $user->email = $email;
                    $user->password = $finalPassword;
                    $user->token = $userToken;

                    $auth = true;
                    $userDao->create($user, $auth);
                }
                else {
                    $message->setMessage("Usuário já cadastrado, tente outro email", "error", "back");
                }
            }
            else {
                $message->setMessage("As senhas não são iguais", "error", "back");
            }
        }
        else {
            //Enviar uma msg de erro, de dados faltantes
            $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
        }
    }
    else if($type === "login") {

    }