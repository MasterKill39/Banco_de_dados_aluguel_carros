<?php
/*
Antes de acessar, execute os comandos do arquivo alcarro.sql
*/
$SERVIDOR = "localhost";
$USUARIO = "root";
$SENHA = "";
$BASE = "alcarro";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "Non";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Unicid Localiza</title>
    <style>
body {
    background: url('fundo2.avif') no-repeat center center fixed;
    background-size: cover;
    color: white;
    font-weight: 300;
    line-height: 1.6;
    height: 100vh;
    overflow: auto;
}

form {
    width: 300px;
    margin: 20px auto;
    background-color: rgba(128, 128, 128, 0.7);
    padding: 15px;
    border-radius: 5px;
}

p {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
}

select, input[type="text"], input[type="date"], input[type="time"] {
    width: 100%;
    padding: 5px 10px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px 15px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #45a049;
}
        p {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        select, input[type="text"], input[type="date"], input[type="time"] {
            width: 100%;
            padding: 5px 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>

<?php
switch ($op) {
    case 'nova':
        ?>
        <form action="controle.php" method="GET">
            <input type="hidden" name="op" value="new">
            <p><label for="cliente">Nome do Cliente:</label>
                <select id="cliente" name="cliente">
                    <?php
                    $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
                    $query = "SELECT id_cli, nome FROM vClientesPorNome";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $id, $nome);
                    while (mysqli_stmt_fetch($stmt)) {
                        echo "<option value=\"$id\">$nome</option>\n";
                    }
                    mysqli_stmt_close($stmt);
                    mysqli_close($con);
                    ?>
                </select></p>
            <p><label for="carro">Nome do Carro:</label>
                <select id="carro" name="carro">
                    <?php
                    $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
                    $query = "SELECT codi, nome, tipo FROM vCarrosPorNome";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $codi, $nome, $tip);
                    while (mysqli_stmt_fetch($stmt)) {
                        echo "<option value=\"$codi\">$nome ($tip)</option>\n";
                    }
                    mysqli_stmt_close($stmt);
                    mysqli_close($con);
                    ?>
                </select></p>
                <p><label for="vendedor">Nome do Vendedor:</label>
                <select id="vendedor" name="vendedor">
                    <?php
                    $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
                    $query = "SELECT id_vendedor, nome FROM vendedor";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $id_vendedor, $nome_vendedor);
                    while (mysqli_stmt_fetch($stmt)) {
                        echo "<option value=\"$id_vendedor\">$nome_vendedor</option>\n";
                    }
                    mysqli_stmt_close($stmt);
                    mysqli_close($con);
                    ?>
                </select></p>
                <p><label for="data">Data do Aluguel:</label>
                <input type="date" id="data" name="data"></p>
                <p><label for="hora">Hora do Aluguel:</label>
                <input type="time" id="hora" name="hora"></p>
                <p><label for="pagamento">Pagamento: </label>
                <select id="pagamento" name="pagamento">
                    <?php
                    $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE); 
                    $query = "SELECT pagamento FROM vPagamentos";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $pagamento);
                    while (mysqli_stmt_fetch($stmt)) {
                        echo "<option value=\"$pagamento\">$pagamento</option>\n";
                    }
                    mysqli_stmt_close($stmt);
                    mysqli_close($con);
                    ?>
                </select></p> 
                <p><input type="submit" value="Agendar Aluguel"></p>
        </form>
        <?php	 
        break;
        case 'new':
            $id_cli = $_GET["cliente"];
            $codi = $_GET["carro"];
            $data = $_GET["data"];
            $hora = $_GET["hora"];
            $datahora = $data . ' ' . $hora;
            $pagamento = $_GET["pagamento"];
            $vendedor_id = $_GET["vendedor"];
            if ($id_cli > 0) {
                $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
                $query = "CALL spIncluiAluguel(?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $query);
    
                if ($stmt === false) {
                    die('Erro na preparação: ' . mysqli_error($con));
                }
    
                $bind_result = mysqli_stmt_bind_param($stmt, "sissi", $datahora, $id_cli, $codi, $pagamento, $vendedor_id);
                if ($bind_result === false) {
                    die('Erro na ligação de parâmetros: ' . mysqli_error($con));
                }
    
                $execute_result = mysqli_stmt_execute($stmt);
                if ($execute_result === false) {
                    die('Erro na execução: ' . mysqli_error($con));
                }
    
                mysqli_stmt_close($stmt);
                mysqli_close($con);
                header("Location: tabela.php");
            } else {
            ?>
            <form action="controle.php" method="GET">
                <input type="hidden" name="op" value="newCl">
                <input type="hidden" name="carro" value="<?php echo $codi ?>">
                <input type="hidden" name="data" value="<?php echo $data ?>">
                <input type="hidden" name="hora" value="<?php echo $hora ?>">
                <input type="hidden" name="pagamento" value="<?php echo $pagamento ?>">
                <p><label for="nome">Nome do Cliente:</label>
                <input type="text" id="cli" name="cli" required></p>  
                <p><input type="submit" value="Incluir e Alugar"></p>
            </form>
            <?php
        }
        break;
    case 'newCl':
        $carro = $_GET["carro"];
        $datahora = $_GET["data"] . ' ' . $_GET["hora"];
        $pagamento = $_GET["pagamento"];
        $cliente = $_GET["cli"];
        $vendedor_id = $_GET["vendedor"];
        $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
        $query = "CALL spIncluiCliente(?, @id)";
        $stmt = mysqli_prepare($con, $query);
        if ($stmt === false) {
            die('Erro na preparação: ' . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt, "s", $cliente);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $query = "SELECT @id";
        $stmt = mysqli_prepare($con, $query);
        if ($stmt === false) {
            die('Erro na preparação: ' . mysqli_error($con));
        }
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $cli);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $query = "CALL spIncluiAluguel(?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        if ($stmt === false) {
            die('Erro na preparação: ' . mysqli_error($con));
        }
        mysqli_stmt_bind_param($stmt, "sissi", $datahora, $cli, $carro, $pagamento, $vendedor_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_close($con);
        header("Location: tabela.php");
        break;

        case 'newCar':
            $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
            $query = "SELECT nome FROM marca"; // Consulta para buscar as marcas cadastradas
            $result = mysqli_query($con, $query);
        
            if (!$result) {
                die("Erro ao buscar marcas: " . mysqli_error($con));
            }
        
            ?>
            <form action="controle.php" method="GET">
                <input type="hidden" name="op" value="nCar">
                <p><label for="codi">Codigo do Carro:</label>
                <input type="text" id="codi" name="codi"></p>
                <p><label for="nome">Nome do Carro:</label>
                <input type="text" id="nome" name="nome"></p>
                <p><label for="tip">Tipo:</label>
                <input type="text" id="tip" name="tip"></p>
                <p><label for="marca">Marca:</label>
                <select id="marca" name="marca">
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['nome'] . "'>" . $row['nome'] . "</option>";
                    }
                    ?>
                </select>
                </p>
                <p><input type="submit" value="Incluir"></p>
            </form>
            <?php
            mysqli_close($con);
            break;
    case 'nCar':
        $codi =  $_GET["codi"];
        $nome =  $_GET["nome"];
        $tip = $_GET["tip"];
        $marca = $_GET["marca"];
        $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
        $query = "CALL spIncluiCarro(?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $codi, $nome, $tip, $marca);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        header("Location: tabela.php");
        break; 
    case 'canc':
        $id = $_GET["id"];
        $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
        $query = "CALL spCancelaAluguel(?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        header("Location: tabela.php");
        break; 
    case 'alt':
        $id = $_GET["id"];
        $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
        $query = "CALL spAluguelPorId(?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $linha = mysqli_fetch_assoc($result);
        $cliente = $linha["cliente"];
        $carro = $linha["carro"];
        ?>
        <form action="controle.php" method="GET">
            <input type="hidden" name="op" value="altAlug">
            <input type="hidden" name="id" value="<?php echo $id ?>">
            <p><label for="cliente">Nome do Cliente:</label>
            <input type="text" name="cliente" id="cliente" value="<?php echo $cliente ?>" disabled></p>
            <p><label for="carro">Nome do Carro:</label>
            <input type="text" name="carro" id="carro" value="<?php echo $carro ?>" disabled></p>
            <p><label for="data">Data do Aluguel:</label>
            <input type="date" id="data" name="data"></p>
            <p><label for="hora">Hora do Aluguel:</label>
            <input type="time" id="hora" name="hora"></p>
            <p><input type="submit" value="Alterar Aluguel"></p>
        </form>
        <?php
        break; 
    case 'altAlug':
        $id = $_GET["id"];
        $datahora = $_GET["data"] . ' ' . $_GET["hora"];
        $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
        $query = "CALL spAlteraAluguel (?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "is", $id, $datahora);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        header("Location: tabela.php");
        break; 
    case 'novoVendedor':
        ?>
        <form action="controle.php" method="GET">
            <input type="hidden" name="op" value="inserirVendedor">
            <p><label for="nomeVendedor">Nome do Vendedor:</label>
            <input type="text" id="nomeVendedor" name="nomeVendedor" required></p> 
            <p><input type="submit" value="Cadastrar Vendedor"></p>
        </form>
        <?php
        break;
    case 'inserirVendedor':
        $nomeVendedor = $_GET["nomeVendedor"];
        $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
        $query = "INSERT INTO vendedor (nome) VALUES (?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $nomeVendedor);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        header("Location: tabela.php");
        break;
            default:
        die("Operação desconhecida");
        break;
    case 'cadastrarCliente':
            ?>
            <form action="controle.php" method="GET">
                <input type="hidden" name="op" value="inserirCliente">
                <p>
                    <label for="nome">Nome do Cliente:</label>
                    <input type="text" id="nome" name="nome" required>
                </p>
                <input type="submit" value="Cadastrar Cliente">
                </p>
            </form>
            <?php
            break;
    
    case 'inserirCliente':
            $nome = $_GET['nome'];
    
            $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
    
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }
    
            $query = "INSERT INTO cliente (nome) VALUES (?)";
            $stmt = mysqli_prepare($con, $query);
    
            if ($stmt === false) {
                die('Erro na preparação: ' . mysqli_error($con));
            }
    
            $bind_result = mysqli_stmt_bind_param($stmt, "s", $nome);
            if ($bind_result === false) {
                die('Erro na ligação de parâmetros: ' . mysqli_error($con));
            }
    
            $execute_result = mysqli_stmt_execute($stmt);
            if ($execute_result === false) {
                die('Erro na execução: ' . mysqli_error($con));
            }
    
            mysqli_stmt_close($stmt);
            mysqli_close($con);
    
            header("Location: tabela.php");
            break;
        case 'cadastrarMarca':
                ?>
                <form action="controle.php" method="GET">
                    <input type="hidden" name="op" value="inserirMarca">
                    <p>
                        <label for="nome">Nome da Marca:</label>
                        <input type="text" id="nome" name="nome" required>
                    </p>
                    <input type="submit" value="Cadastrar Marca">
                    </p>
                </form>
                <?php
                break;
        
        case 'inserirMarca':
                $nome = $_GET['nome'];
        
                $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);
        
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }
        
                $query = "INSERT INTO marca (nome) VALUES (?)";
                $stmt = mysqli_prepare($con, $query);
        
                if ($stmt === false) {
                    die('Erro na preparação: ' . mysqli_error($con));
                }
        
                $bind_result = mysqli_stmt_bind_param($stmt, "s", $nome);
                if ($bind_result === false) {
                    die('Erro na ligação de parâmetros: ' . mysqli_error($con));
                }
        
                $execute_result = mysqli_stmt_execute($stmt);
                if ($execute_result === false) {
                    die('Erro na execução: ' . mysqli_error($con));
                }
        
                mysqli_stmt_close($stmt);
                mysqli_close($con);
        
                header("Location: tabela.php");
                break;
            
}
?>
</body>
</html>
