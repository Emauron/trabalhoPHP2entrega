<?php
    include 'conn.php';
    echo (isset($_REQUEST['error'])) ? "<script>window.alert('Erro no cadastro do usuário!');</script>" : '' ;
    $email    = (isset($_REQUEST['email']) && $_REQUEST['email'] != "") ? $_REQUEST['email'] : "";;
    $password = (isset($_REQUEST['password']) && $_REQUEST['password'] != "") ? $_REQUEST['password'] : "";;
    if(!isset($_REQUEST['register']) && !empty($email) && !empty($password)){
        $sqlLogin    = "SELECT id, email, senha, nome FROM usuarios WHERE email = '$email' AND senha = '$password';";
        $resultLogin = $PDO->query($sqlLogin);
        $numResult   = $resultLogin->rowCount();

        if($numResult == 1) {
            $consultaLogin = $resultLogin->fetch(PDO::FETCH_OBJ);
            session_start();
            $_SESSION['id'] = $consultaLogin->id;
            $_SESSION['nome'] = $consultaLogin->nome;
            header("Location: index.php");
            exit(); 
        }else {
            header("location: login_register.php?error");
        }
    }
    if(isset($_REQUEST['password1']) && isset($_REQUEST['password2'])){
        $nome      = $_REQUEST['nome'];
        $password1 = $_REQUEST['password1'];
        $password2 = $_REQUEST['password2'];

        if($password1 != $password2){
            echo "<script>window.alert('As senhas devem ser iguais!');</script>";
        }else{
            $sqlLogin    = "SELECT id FROM usuarios WHERE email = '$email';";
            $resultLogin = $PDO->query($sqlLogin);
            $numResult   = $resultLogin->rowCount();
            if($numResult == 0) {
                $sqlData = "INSERT INTO `usuarios` (nome, email, senha) VALUES ('$nome','$email','$password1');";
                actionDB($sqlData);
            }else {
                header("location: login_register.php?error");
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-black">
    <main class="d-flex justify-content-center align-items-center" style="height: 80vh;">
        <form action="login_register.php" method="post" class="col col-lg-3 bg-dark form-group" id="login_register_form">
            <h1 class="text-center">Login</h1>
            <?php
                if(isset($_REQUEST['register'])){
            ?>
                <div class="form-group mb-3">
                    <label for="nome">Nome</label>
                    <input class="form-control" type="nome" name="nome" id="nome" required>
                </div>
            <?php
                }
            ?>
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input class="form-control" type="email" name="email" id="email" required>
            </div>
            <?php
                if(isset($_REQUEST['register'])){
            ?>
                <div class="form-group mb-3">
                    <label for="password1">Senha</label>
                    <input class="form-control" type="password" name="password1" id="password1" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password2">Senha</label>
                    <input class="form-control" type="password" name="password2" id="password2" required>
                </div>
            <?php
                }else{
            ?>
                <div class="form-group mb-3">
                    <label for="password">Senha</label>
                    <input class="form-control" type="password" name="password" id="password" required>
                </div>
            <?php
                }
            ?>
            <button type="submit" class="btn btn-primary">Entrar</button>
            <?php
                if(isset($_REQUEST['register'])){
            ?>
                <a href="login_register.php?login" class="btn btn-outline-light float-end">Login</a>
            <?php
                }else {
            ?>
                <a href="login_register.php?register" class="btn btn-outline-light float-end">Registrar-se</a>
            <?php
                }
            ?>
        </form>
    </main>
</body>
</html>