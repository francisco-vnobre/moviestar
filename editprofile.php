<?php
    require_once("templates/header.php");

    $userData = $userDao($conn, $BASE_URL);
?>
    <div id="main-container" class="container-fluid">
        <h1>Edição do perfil</h1>
    </div>
<?php
    require_once("templates/footer.php");
?>