<!DOCTYPE html>
<html>

<head>
    <title>Unicid Localiza Agendamento</title>
    <meta charset="UTF-8">
    <style>
        * {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: url('fundo.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-weight: 300;
            line-height: 1.6;
            height: 100vh;
            overflow: auto;
            align: right;
        }

        #cont {
            max-width: 1000px;
            margin: 40px auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
        }

        h1, h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #82FA91;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: rgba(128, 91, 68, 0.8);
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        a {
            color: #82FA91;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: white;
        }

        .botao {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            background-color: #FFB486;
            color: #252525;
            border-radius: 5px;
            border: 2px solid white;
            transition: background-color 0.3s ease;
        }

        .botao:hover {
            background-color: #FF9256;
        }

        #rodape {
            margin-top: 30px;
            text-align: center;
            font-size: 0.8rem;
            border-top: 1px solid white;
            padding-top: 20px;
        }

        #cadastroForm {
    display: flex;
    flex-direction: column;
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 10px;
}

.input-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
}

.input-group label {
    margin-bottom: 10px;
    font-weight: 400;
    color: #82FA91;
}

.input-group input {
    padding: 10px;
    border: 1px solid #82FA91;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    transition: background-color 0.3s ease;
}

.input-group input:focus {
    background: rgba(255, 255, 255, 0.2);
    outline: none;
}

.botao {
    align-self: center;
}
    </style>
</head>
<body>
    <div id="cont">
        <div id="cab">
            <div id="logo">
                <img src="logo.png">
            </div>
            <div id="topo">
                <h1>Agendamento de Aluguéis</h1>
            </div>
        </div>
        <div id="area">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Cliente<a href="controle.php?op=cadastrarCliente">&oplus;</a></th>
                        <th>Carro</th>
                        <th>Marca</th>
                        <th>Pagamento</th>
                        <th>Vendedor <a href="controle.php?op=novoVendedor">&oplus;</a></th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $SERVIDOR = "localhost";
                $USUARIO = "root";
                $SENHA = "";
                $BASE = "alcarro";
                $sql = "select * from vProxAluguel";
                $con = mysqli_connect($SERVIDOR, $USUARIO, $SENHA, $BASE);

                mysqli_set_charset($con, "utf8");

                $dados = mysqli_query($con, $sql);
                mysqli_close($con);
                while ($linha = mysqli_fetch_assoc($dados)) {
                    $dia = $linha["data_c"];
                    $hora = $linha["hora"];
                    $cli = utf8_encode($linha["cliente"]);
                    $car = utf8_encode($linha["carro"]);
                    $carMarca = utf8_encode($linha["marca_nome"]);
                    $pag = utf8_encode($linha["pagamento"]);
                    $vendedor = utf8_encode($linha["nome_vendedor"]);
                    $ida = $linha["id_aluguel"];
                    $cancela = $linha["dif"] > 48 ? "<a href=controle.php?op=canc&id=$ida>&#x1F5D1;</a>&nbsp;" : "&nbsp;&nbsp;";
                    echo "<tr><td>$dia</td><td>$hora</td><td>$cli</td><td>$car</td><td>$carMarca</td><td>$pag</td><td>$vendedor</td><td>$cancela&nbsp;<a href=\"controle.php?op=alt&id=$ida\">&#9842;</a></td></tr>";
                }
                ?>
                </tbody>
            </table>
            <p id="bot">
                <a href="controle.php?op=nova" class="botao">Novo aluguel</a>
            </p>
        </div>
        <div id="rodape">
            <h2>Não Trabalhamos com Marcas/Modelos que estão fora do nosso catalogo de aluguel!!!</h2>
        </div>
        <div id="rodape">
            <p>Unicid Localiza</p>
            <p>Rua Cesario Galero, 475</p>
            <p>Tatuape, Zona Leste, São Paulo</p>
        </div>
    </div>
</body>
</html>