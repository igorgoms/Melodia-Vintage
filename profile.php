<?php
// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Inicia a sessão para acessar as variáveis de sessão
session_start();

// Verifica se o usuário está logado, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtém o ID do usuário a partir da sessão
$user_id = $_SESSION['user_id'];

// Processa o formulário quando enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Valida e sanitiza os dados do formulário
    $username = htmlspecialchars(strip_tags($_POST['username']));
    $profile_pic = $_FILES['profile_pic'];

    // Verifica se um arquivo de imagem foi enviado
    if ($profile_pic['size'] > 0) {
        // Verifica se é uma imagem válida (opcional: pode adicionar mais verificações)
        $allowed_types = ['image/jpeg', 'image/png'];
        if (!in_array($profile_pic['type'], $allowed_types)) {
            die('Tipo de arquivo não suportado. Apenas JPEG e PNG são permitidos.');
        }

        // Move o arquivo para a pasta de uploads com um nome único
        $upload_dir = "uploads/";
        $upload_file = $upload_dir . basename($profile_pic['name']);
        if (!move_uploaded_file($profile_pic['tmp_name'], $upload_file)) {
            die('Erro ao fazer upload do arquivo.');
        }
    }

    // Atualiza o perfil do usuário no banco de dados
    if (!empty($profile_pic)) {
        $sql = "UPDATE users SET username = ?, profile_pic = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $username, $profile_pic['name'], $user_id);
    } else {
        $sql = "UPDATE users SET username = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $username, $user_id);
    }

    try {
        $stmt->execute();
    } catch (Exception $e) {
        die('Erro ao atualizar perfil: ' . $e->getMessage());
    }
}

// Consulta o banco de dados para obter os dados do usuário atualizado
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se há resultados na consulta
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc(); // Obtém os dados do usuário
} else {
    // Se não houver resultados, redireciona para a página de login
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>💽Melodia Vintage💽: Perfil do Usuário</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Barra de navegação -->
    <nav>
        <a href="index.php">Início</a>
        <a href="my_discs.php">Meus Discos</a>
        <a href="logout.php">Sair</a>
    </nav>

    <!-- Exibição do perfil do usuário -->
    <h1>Perfil de <?php echo htmlspecialchars($user['username']); ?></h1>
    <div class="profile">
        <?php if (!empty($user['profile_pic'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Foto de Perfil">
        <?php else: ?>
            <img src="default_profile.png" alt="Foto de Perfil Padrão">
        <?php endif; ?>
        <h2><?php echo htmlspecialchars($user['username']); ?></h2>
        <p>Nome: <?php echo htmlspecialchars($user['nome']); ?></p>
        <p>Sobrenome: <?php echo htmlspecialchars($user['sobrenome']); ?></p>
        <p>Endereço: <?php echo htmlspecialchars($user['endereco_rua']) . ', ' . htmlspecialchars($user['endereco_cidade']) . ', ' . htmlspecialchars($user['endereco_estado']) . ', ' . htmlspecialchars($user['endereco_cep']); ?></p>
        <p>Data de Nascimento: <?php echo htmlspecialchars($user['data_nascimento']); ?></p>
        <p>Telefone: <?php echo htmlspecialchars($user['telefone']); ?></p>
        <p>Sexo: <?php echo htmlspecialchars($user['sexo']); ?></p>
    </div>

    <!-- Formulário para atualizar perfil -->
    <form action="profile.php" method="post" enctype="multipart/form-data">
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <input type="file" name="profile_pic">
        <button type="submit">Atualizar Perfil</button>
    </form>

    <!-- Opções do usuário -->
    <h1>Opções do Usuário</h1>
    <ul>
        <li><a href="my_discs.php">Meus Discos</a></li>
        <li><a href="add_disc.php">Adicionar Novo Disco</a></li>
    </ul>
    <footer>
        💽Melodia Vintage💽 | Criado por Igor Gomes, Athisson Mateus, Iarly Peterson e Kauan Gabriel | 2024
    </footer>
</body>
</html>

<?php
/*
    * Instrução 'Include' inclui as configurações de outro script php (config.php)

    * Função 'session_start()': utilizada para iniciar ou continuar uma sessão no servidor, verifica se existe
    uma sessao ja iniciada pelo usuario atual

    * Estrutura 'if (!isset($_SESSION['user_id'])) {}':
        Utiliza a estrutura if e negação(!) com a função 'isset()' que checa se o usuario esta logado, se não,
        redireciona para a pagina de login (login.php) utilizando a função 'header('Location: ')' em seguida 
        utiliza a função 'exit()' para terminar a execução do script

        - isset(): checa se a varivael existe e se não é nula retornando um valor boleano, logo, se existe é 'true'
        se não, 'false'
    
    * Variavel $user_id: obtem o id do usuario (user_id) a partir da variavel superglobal $_SESSION
    
    * Estrutura 'if ($_SERVER['REQUEST_METHOD'] == 'POST') {}':
        Verifica  se o metodo da requisição é 'POST', em seguida faz uma validação dos dados enviados do formulario
        - $username: recebe o valor do 'nome do usuario' a partir do formulario utilizando o metodo 'POST' e utiliza o
        conjunto de funções 'htmlspecialchars(strip_tags())'
            * htmlspecialchars: utilizado para conveter alguns carateries especias em entidades HTML equivalentes, ou seja,
            caracteries que possuem um siginificado especial no HTML como "<",">","&" e aspas duplas ou simples sejam convetidos 
            em representaçãoes que nao serao lidas como tags ou comandos para o HTML 

            * stip_tags: utilizada para remover todas as tags HTML e PHP de uma string

        - $profile_pic: recebe o valor 'foto de perfil' da variavel superglobal '$_FILES'
            * $_FILES: armazena informações sobre arquivos enviados (uploads)

    * Estrutura 'if ($profile_pic['size'] > 0) {}':
        'Se' a chave 'size'(tamanho) da varaivel '$profile_pic' for maior que 0, então prossiga:
        - variavel $allowed_types: Variavel que verifica os tipos aceitos de imagem para o upload (jpeg/png) utilizando
        uma lista: "['image/jpeg', 'image/png']"

        - Estrutura: 'if (!in_array($profile_pic['type'], $allowed_types))':
            Se o tipo de imagem for diferentes dos listados na estrutura da lista na variavel $allowed_types mostre a intrução 
            'die' para finalizar a execução e mostrar a mensagem de tipo não suportado
    
    * Variavel $upload_dir: move o arquivo para a pasta uploads ("uploads/") 

    * Variavel $upload_file: utiliza o valor da variavel '$upload_dir' e função 'basename()' que reconhe a lociadade para obter o nome correto,
    pega o valor da a variavel '$profile_pic['name']'

    * Estrutura 'if (!move_uploaded_file($profile_pic['tmp_name'], $upload_file)){}':
        'Se' não conseguir mover o valor do arquivo enviado da variavel '$profile_pic['temp_name']' e $upload_file então, a intrução 'die' para 
        finalizar a execução do script e mostrar mensagem de erro no upload

    * Estrutura 'if (!empty($profile_pic)) {}':
        'Se' a variavel $profile_pic for diferente de 'vazia' prossiga:
            - $sql: utiliza a instrução sql 'UPDATE' para atualizar a tabela 'users', Colocando os valores do  'nome de usuario'(username),
            'foto de perfil'(profile_pic) com a instrução 'SET',e utiliza a instrução 'WHERE' para filtrar e refinar a recuperação de dados da database
            usando o 'id'

            - $stmt: utiliza a variavel $conn das configurações com o banco de dados e utiliza o metodo 'prepare' para preparar uma instrução sql para
            ser executada posteriormente, no caso, utiliza a variavel $sql
                * Após isso utilizar o metodo 'bind_param' que é utilizada para vincular variaveis PHP a paramentos em instrunções sql preparadas, no caso
                 utilizando as variaveis '$username','$profile_pic' e '$user_id'

        - else{}: 'Se não' utilize as funções anteriores excluindo a 'foto de perfil' (profile_pic)
    
    * Estrutura 'try': utilizada para tenta executar um codigo que pode gerar certos erros inesperados durante a execução, no caso ele irar executar  a variavel
    $stmt com o metodo 'execute()' para executar a operação, e caso possua alguma exeção utiliza:
            - Estrutura 'catch (Exception $e){}': pega a execeção, possuindo uma variavel '$e' que recebe a instancia da execução capturada e logo printa 
            na tela (echo) a mensagem de erro e faz que a varivael '$e' utilize um metodo que retorna a mensagem de erro associada à exceção capturada

    * Variavel $sql: utiliza a instrução sql "SELECT * ALL", ou seja, seleciona tudo da tabela 'users',e utiliza a instrução 'WHERE usando o 'id' para filtrar

    * Variavel $stmt: utiliza a variavel $conn das configurações com o banco de dados e utiliza o metodo 'prepare' para preparar uma instrução sql para ser 
    executada posteriormente, no caso, utiliza a variavel $sql
                * Após isso utilizar o metodo 'bind_param' com a variavel '$user_id' e em seguida Utiliza o metodo 'execute()' para executar a operação
    
    * Variavel $result: apos a execução da variavel '$stmt',ele utiliza o metodo 'get_result()' para pegar o resultado da execução desta mesma variavel

    * Estrutura 'if ($result->num_rows > 0) {}':
        'Se' o numero de linhas na variavel '$result' for maior que 0, então prossiga:
            - $user: utiliza o resultado de '$result' para obter os dados do usuario utilizando o metodo fetch_assoc()
        
        - else{}: 'Se não' houver resultados redirecione pra a pagina de login(login.php)utilizando a função 'header()' seguido da função 'exit()'
        para terminar a execução do script
*/
?>