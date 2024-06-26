<?php
// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Inicia a sessão para acessar as variáveis de sessão
session_start();

// Processa o formulário quando enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta o banco de dados para verificar se o usuário com o email fornecido existe
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifica se o usuário existe e se a senha fornecida corresponde à senha armazenada no banco de dados
    if ($user && password_verify($password, $user['password'])) {
        // Se as credenciais forem válidas, define a variável de sessão 'user_id' com o ID do usuário
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin']; // Adiciona esta linha
        // Redireciona o usuário para a página inicial (index.php)
        header('Location: index.php');
        exit();
    } else {
        // Se as credenciais forem inválidas, exibe uma mensagem de erro
        echo "Credenciais inválidas!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>💽Melodia Vintage💽: Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Barra de navegação -->
    <nav>
        <a href="index.php">Início</a>
        <a href="register.php">Registrar</a>
    </nav>

    <h1>💽Login💽</h1>
    <!-- Formulário de login -->
    <form action="login.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    <footer>
        💽Melodia Vintage💽 | Criado por Igor Gomes, Athisson Mateus, Iarly Peterson e Kauan Gabriel | 2024
    </footer>
</body>
</html>

<?php
/*
    * Instrução 'Include' inclui as configurações de outro script php (config.php)

    * Função 'session_start': utilizada para iniciar ou continuar uma sessão no servidor, verifica se existe
    uma sessao ja iniciada pelo usuario atual

    * Estrutura 'if $_SERVER['REQUEST_METHOD'] == 'POST' {})': 
        Verifica  se o metodo da requisição é 'POST', em seguida faz uma validação dos dados de entrada
        - $email: recebe o valor do email a partir do formulario de login utilizando o metodo 'POST' e utiliza a função 'filter_var'
            que serve para validar e sanitizar os dados, pu seja, servindo para verificar se o valor é correspondente a um
            formato ou tipo espeficico, e Ainda utiliza o Filtro 'FILTER_VALIDATE_EMAIL' que serve para validar se um valor de 
            endereço de email é valido

        - $password: recebe o valor da senha a partir do formulario de login utilizando o metodo 'POST'

    * Estrutura 'if(!$email){}': 
        'Se' negar a variavel $email imprima a mensagem de email invalido e execute função 'exit()' para terminar a execução do script,
         ou seja, serve para confirmar a validação do email

    * Variavel $sql: utiliza a instrução sql 'SELECT' para selecionar  o 'id' e 'email' e 'password' da tabela 'users', e utiliza
    a instrunção 'WHERE' para filtrar os resultados da pesquisa sql utilizando o 'email'

    * Variavel $stmt: utiliza a variavel $conn das configurações com o banco de dados e utiliza o metodo 'prepare'
    para preparar uma instrução sql para ser executada posteriormente, no caso, utiliza a variavel $sql
        - Após isso utilizar o metodo 'bind_param' que é utilizada para vincular variaveis PHP a paramentos em instrunções sql preparadas,
        no caso utilizando a variavel $email
        - Em seguida utiliza o metodo 'execute()' para executar a operação
    
    * Variavel $result: apos a execução da variavel '$stmt',ele utiliza o metodo 'get_result()' para pegar o resultado da execução desta
    mesma variavel

    * Variavel $user: pega a variavel '$result' e utiliza o metodo 'fetch_assoc()' para converter os dados da linha em um array, onde cada chave corresponde
    ao nome de uma coluna na tabela consultada (users)

    * Estrutura 'if ($user && password_verify($password, $user['password'])) {}':
        'Se' a variavel '$user' 'e' a variavel $senha estiverem correspondentes no banco de dados,
        utilizando a função 'password_verify'
         - difinem a variavel superglobal $_SESSION utilizando a chave 'user_id' com o 'id' do usuário fornecido
            pela variavel '$user'

         - Logo apos ele redireiona para pagina do index (index.php) utilizando a função 'header()' seguido da função 'exit()'
            para terminar a execução do script 
    * Estrutura else{}:
        'Se não' imprima (echo) a mensagem de credenciais invalidas
*/
?>