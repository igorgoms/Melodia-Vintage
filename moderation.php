<?php
// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Inicia a sessão para acessar as variáveis de sessão
session_start();

// Verifica se o usuário está logado e se é um administrador, caso contrário, redireciona para a página de login
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}

// Consulta SQL para selecionar todos os usuários
$sql = "SELECT id, username, email, is_admin FROM users";
$result = $conn->query($sql);

// Função para excluir um usuário
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    $sql_delete = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    header('Location: moderation.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Moderação de Usuários</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Barra de navegação -->
    <nav>
        <a href="index.php">Início</a>
        <a href="profile.php">Perfil</a>
        <a href="logout.php">Sair</a>
    </nav>

    <h1>Moderação de Usuários</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome de Usuário</th>
            <th>Email</th>
            <th>Admin</th>
            <th>Ações</th>
        </tr>
        <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['is_admin'] ? 'Sim' : 'Não'; ?></td>
                <td>
                    <form action="moderation.php" method="post" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete_user">Excluir</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <footer>
        💽Melodia Vintage💽 | Criado por Igor Gomes, Athisson Mateus, Iarly Peterson e Kauan Gabriel | 2024
    </footer>
</body>
</html>

<?php

/*
    Documentação
    --------------
    Este script lida com a moderação de usuários. Ele inclui:

    1. Configuração da conexão com o banco de dados.
    2. Inicialização da sessão e autenticação do usuário como administrador.
    3. Consulta ao banco de dados para obter a lista de todos os usuários.
    4. Função para excluir um usuário, acessível via formulário POST.
    5. Exibição da lista de usuários em uma tabela, com opções para excluir cada usuário.

    Uso:
    - Certifique-se de que o arquivo 'config.php' contém os detalhes corretos da conexão com o banco de dados.
    - O usuário deve estar logado como administrador para acessar esta página.

    Tratamento de Erros:
    - Redireciona para a página de login se o usuário não estiver logado ou não for um administrador.
*/
?>