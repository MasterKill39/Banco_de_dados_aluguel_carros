<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
       body {
            background: url('Aluguel-de-carros.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: white;
            margin-bottom: 20px; 
            font-weight: 500;
            background: rgba(0, 0, 0, 0.9); 
            padding: 10px; 
            border-radius: 5px;
        }
        form {
            background-color: rgba(0, 0, 0, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 350px;
            max-width: 90%;
        }

        label {
            color: white;
            font-weight: 500;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"] {
            padding: 12px;
            width: 100%;
            border: none;
            border-bottom: 1px solid #ccc;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            font-size: 14px;
            margin-bottom: 16px;
            color: white;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #4CAF50;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        button[type="button"] {
            background-color: transparent;
            color: #4CAF50;
            border: none;
            cursor: pointer;
            transition: text-decoration 0.3s;
        }

        button[type="button"]:hover {
            text-decoration: underline;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #registerPrompt {
            color: white;
            margin-top: 20px;
        }
    </style>
   <h1>Faça Seu Login Aqui</h1>
</head>
<body>
    <form action="index.php" method="POST">
        <p><label for="username">Nome de Usuário:</label>
        <input type="text" id="username" name="username" required></p>
        
        <p><label for="password">Senha:</label>
        <input type="password" id="password" name="password" required></p>
        
        <p><input type="submit" value="Login"></p>

        <p id="registerPrompt">Não tem uma conta? <a href="cadastro.php" color"white"><button type="button">Cadastre-se</button></a></p>
    </form>
</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $SERVIDOR = "localhost";
    $USUARIO = "root";
    $SENHA = "";
    $BASE = "alcarro";

    $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);

    if (!$con) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $query = "SELECT password FROM usuario WHERE username = ?";  // Corrigido para 'usuario'
    $stmt = mysqli_prepare($con, $query);

    if ($stmt === false) {
        die("Falha na preparação: " . mysqli_error($con));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $hashed_password);
    mysqli_stmt_fetch($stmt);

    if (password_verify($password, $hashed_password)) {
        header("Location: tabela.php");
        exit();
    } else {
        echo "Erro no login: credenciais inválidas.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
?>
</html>