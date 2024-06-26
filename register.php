<?php
// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Função para limpar e validar os dados do formulário
function sanitize_input($data) {
    return htmlspecialchars(strip_tags($data));
}

// Verifica se o método da requisição é POST (quando o formulário é enviado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coleta e sanitiza os dados enviados pelo formulário
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $nome = sanitize_input($_POST['nome']);
    $sobrenome = sanitize_input($_POST['sobrenome']);
    $endereco_rua = sanitize_input($_POST['endereco_rua']);
    $endereco_cidade = sanitize_input($_POST['endereco_cidade']);
    $endereco_estado = sanitize_input($_POST['endereco_estado']);
    $endereco_cep = sanitize_input($_POST['endereco_cep']);
    $data_nascimento = sanitize_input($_POST['data_nascimento']);
    $telefone = sanitize_input($_POST['telefone']);
    $sexo = sanitize_input($_POST['sexo']);

    try {
        // Prepara a query SQL para inserir os dados na tabela 'users'
        $sql = "INSERT INTO users (username, email, password, nome, sobrenome, endereco_rua, endereco_cidade, endereco_estado, endereco_cep, data_nascimento, telefone, sexo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssss", $username, $email, $password, $nome, $sobrenome, $endereco_rua, $endereco_cidade, $endereco_estado, $endereco_cep, $data_nascimento, $telefone, $sexo);

        // Executa a query para inserir os dados no banco de dados
        if ($stmt->execute()) {
            // Redireciona para a página de login após o registro bem-sucedido
            header('Location: login.php');
            exit();
        } else {
            throw new Exception("Erro ao registrar usuário: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>💽Melodia Vintage💽: Registro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <a href="index.php">Início</a>
        <a href="login.php">Login</a>
    </nav>

    <h1>💽Registro💽</h1>
    <!-- Formulário de registro -->
    <form action="register.php" method="post">
        <input type="text" name="username" placeholder="Nome de usuário" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Senha" required>
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="text" name="sobrenome" placeholder="Sobrenome" required>
        <input type="text" name="endereco_rua" placeholder="Endereço - Rua" required>
        <input type="text" name="endereco_cidade" placeholder="Endereço - Cidade" required>
        <input type="text" name="endereco_estado" placeholder="Endereço - Estado" required>
        <input type="text" name="endereco_cep" placeholder="Endereço - CEP" required>
        <input type="date" name="data_nascimento" placeholder="Data de Nascimento" required>
        <input type="tel" name="telefone" placeholder="Telefone" required>
        <select name="sexo" required>
            <option value="M">Masculino</option>
            <option value="F">Feminino</option>
            <option value="O">Outro</option>
        </select>
        <button type="submit">Registrar</button>
    </form>
    <footer>
        💽Melodia Vintage💽 | Criado por Igor Gomes, Athisson Mateus, Iarly Peterson e Kauan Gabriel | 2024
    </footer>
</body>
</html>

<?php
/*
    * Instrução 'Include' inclui as configurações de outro script php (config.php)

    * função 'sanitize_input': criada para limpa e validar os dados do formulario possuindo um paramentro "$data" na qual
    ira pegar esse parametro e passa pela expressão 'htmlspecialchars(strip_tags)' que é uma combinação de duas função que
    são utilizadas para trarar os dados antes de serem exibidas pela pagina HTML:
        - htmlspecialchars: utilizado para conveter alguns carateries especias em entidades HTML equivalentes, ou seja,
            caracteries que possuem um siginificado especial no HTML como "<",">","&" e aspas duplas ou simples sejam convetidos 
            em representaçãoes que nao serao lidas como tags ou comandos para o HTML 
        - stip_tags: utilizada para remover todas as tags HTML e PHP de uma string
        
    * Estrutura 'if $_SERVER['REQUEST_METHOD'] == 'POST' {})': 
        Verifica  se o metodo da requisição é 'POST' em seguida coleta os dados passando os valores pela função anterior 'sanitize_input'
        e os coloca nas respectivas variaveis
            - $_SERVER: variavel superglobal que contem as infarmações sobre o servidor web e o ambiente em que o script
                esta sendo executado
            - $username: coleta o 'nome de usuario' a ser utilizado para conta
            - $email: coleta o 'email do usuario' 
            - $password: coleta a 'senha do usuario' e passa pela função 'password_hash()' para gerar um hash criptografico
                na senha
            - $nome: coleta o 'nome real do usuario' 
            - $sobrenome: coleta o 'sobrenome do usuario' 
            - $endereço_rua: coleta o registro da 'rua' para o 'Endereço' 
            - $endereço_cidade: coleta o registro da 'cidade' para o 'Endereço' 
            - $endereço_estado: coleta o registro do 'estado' para o 'Endereço' 
            - $endereço_cep: coleta o registro do 'CEP' para o 'Endereço' 
            - $data_nascimento: coleta a 'data de nacimento' 
            - $telefone: coleta o numero de telefone 
            - $sexo: coleta a informação do sexo do usuario 
                * Sendo todas esses valores dessas variaveis inseridos no metodo $_POST inserido no formulario cadastro em seus
                respectivos parametros

    * Estrutura 'try': utilizada para tenta executar um codigo que pode gerar certos erros inesperados durante a execução,
    no caso ele irar tenta perparar a query SQL (consulta SQL) para inserir os dados a tabela 'users'
        - variavel $sql: utiliza a instrução 'INSERT INTO' (insira em) 'users' nos respectivos:
            (username,email,password,nome,sobrenome,endereco_rua,endereco_cidade,endereco_estado,
            endereco_cep,data_nascimento,telefone,sexo) o valores (VALUES) -> 'inseridos nas variaveis do cadastro'

        - variavel $stmt: atribui a a variavel $conn das configurações com o banco de dados e utiliza o metodo 'prepare'
            para preparar uma instrução sql para ser executada posteriormente, no caso, utiliza a varivavel $sql
            * logo isso utiliza o operador seta (->) para utilizar o metodo 'bind_param' que é utilizada para vincular variaveis PHP 
                a paramentos em instrunções sql preparadas

        - Estrutura 'if($stmt -> execute()){}': 'Se a variavel $stmt' executar a query para inserir os dados ao banco, utilize a função 'header'
            para redirecionar para a pagina de Login (login.php), seguido da função 'exit()' para terminar a execução do script 
            automaticamente e evitar possiveis erros
                * metodo 'execute()': utilizada para executar uma instrunção sql preparada

        - Estrutura 'else{}:': 'Se não' utilize a intrunção 'throw new Exception' para lançar uma exceção, ou seja, um objeto que apresenta um erro ou
             problema durante a execução do script e exiba uma mensagem de erro ao tenta registrar o usuario

        - Estrutura 'catch (Exception $e){}': pega a execeção, possuindo uma variavel '$e' que recebe a instancia da execução capturada e logo printa 
            na tela (echo) a mensagem de erro e faz que a varivael '$e' utilize um metodo que retorna a mensagem de erro associada à exceção capturada
                * "echo "Erro: " . $e->getMessage();"
*/
?>