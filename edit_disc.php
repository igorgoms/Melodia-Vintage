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

// Obtém o ID do usuário logado da sessão
$user_id = $_SESSION['user_id'];

// Processa o formulário quando enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $disc_id = $_POST['disc_id'];
    $name = $_POST['name'];
    $artist = $_POST['artist'];
    $year = $_POST['year'];
    $price = $_POST['price'];

    // Atualiza os dados do disco no banco de dados
    $sql = "UPDATE discs SET name = ?, artist = ?, year = ?, price = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiii", $name, $artist, $year, $price, $disc_id, $user_id);
    $stmt->execute();

    // Verifica se houve sucesso na atualização
    if ($stmt->affected_rows > 0) {
        // Redireciona para a página 'my_discs.php' após a atualização
        header('Location: my_discs.php');
        exit();
    } else {
        // Caso contrário, exibe uma mensagem de erro
        $error_message = "Não foi possível atualizar o disco. Verifique as informações e tente novamente.";
    }
}

// Verifica se há um ID de disco na query string (GET)
if (isset($_GET['id'])) {
    $disc_id = $_GET['id'];

    // Consulta SQL para selecionar o disco com o ID fornecido, pertencente ao usuário logado
    $sql = "SELECT * FROM discs WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $disc_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o disco foi encontrado e se pertence ao usuário logado
    if ($result->num_rows == 0) {
        // Se não encontrou o disco ou não pertence ao usuário, redireciona para 'my_discs.php'
        header('Location: my_discs.php');
        exit();
    }

    // Obtém os dados do disco encontrado
    $disc = $result->fetch_assoc();
} else {
    // Se não há ID de disco na query string, redireciona para 'my_discs.php'
    header('Location: my_discs.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>💽Melodia Vintage💽</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Barra de navegação -->
    <nav>
        <a href="index.php">Início</a>
        <a href="profile.php">Perfil</a>
        <a href="logout.php">Sair</a>
    </nav>

    <!-- Título da página -->
    <h1>Editar Disco</h1>

    <!-- Formulário de edição do disco -->
    <form action="edit_disc.php" method="post">
        <input type="hidden" name="disc_id" value="<?php echo $disc['id']; ?>">
        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($disc['name']); ?>" required><br>
        <label for="artist">Artista:</label>
        <input type="text" id="artist" name="artist" value="<?php echo htmlspecialchars($disc['artist']); ?>" required><br>
        <label for="year">Ano:</label>
        <input type="text" id="year" name="year" value="<?php echo htmlspecialchars($disc['year']); ?>"><br>
        <label for="price">Preço:</label>
        <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($disc['price']); ?>" required><br>
        <button type="submit">Atualizar Disco</button>
    </form>

    <!-- Exibir mensagem de erro, se houver -->
    <?php if (isset($error_message)): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>

    <footer>
        💽Melodia Vintage💽 | Criado por Igor Gomes, Athisson Mateus, Iarly Peterson e Kauan Gabriel | 2024
    </footer>
</body>
</html>

<?php

/*
    Documentação
    --------------
    Este script lida com a edição de um disco no banco de dados. Ele inclui:

    1. Configuração da conexão com o banco de dados.
    2. Inicialização da sessão e autenticação do usuário.
    3. Processamento do formulário enviado via POST para atualizar os detalhes do disco.
    4. Validação e atualização dos dados do disco no banco de dados.
    5. Exibição de mensagens de erro em caso de falha na atualização.
    6. Verificação do ID do disco na query string (GET) e recuperação dos dados do disco.
    7. Redirecionamento para 'my_discs.php' em caso de erro ou após a atualização bem-sucedida.

    Uso:
    - Certifique-se de que o arquivo 'config.php' contém os detalhes corretos da conexão com o banco de dados.
    - Os usuários devem estar logados para editar um disco.
    - O disco será atualizado apenas se o ID do disco estiver presente na query string e pertencer ao usuário logado.

    Tratamento de Erros:
    - Exibe mensagem de erro se não for possível atualizar o disco.
    - Redireciona para 'my_discs.php' se não houver ID de disco na query string ou se o disco não pertencer ao usuário logado.
*/
?>
