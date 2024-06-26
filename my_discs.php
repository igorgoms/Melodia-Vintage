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

// Consulta o banco de dados para obter os discos pertencentes ao usuário atual
$sql = "SELECT id, name, artist, year, price, cover_image FROM discs WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>💽Melodia Vintage💽: Meus Discos</title>
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
    <h1>Meus Discos</h1>

    <!-- Lista de discos do usuário -->
    <div class="discs">
        <?php while ($disc = $result->fetch_assoc()): ?>
            <div class="disc">
                <!-- Exibe a capa do disco -->
                <img src="uploads/<?php echo htmlspecialchars($disc['cover_image']); ?>" alt="<?php echo htmlspecialchars($disc['name']); ?>">
                <!-- Exibe o nome do disco -->
                <h2><?php echo htmlspecialchars($disc['name']); ?></h2>
                <!-- Exibe o nome do artista -->
                <p><?php echo htmlspecialchars($disc['artist']); ?></p>
                <!-- Exibe o ano de lançamento do disco -->
                <p>Ano: <?php echo htmlspecialchars($disc['year']); ?></p>
                <!-- Exibe o preço do disco -->
                <p>R$ <?php echo htmlspecialchars($disc['price']); ?></p>
                <!-- Links para editar e excluir o disco -->
                <a href="edit_disc.php?id=<?php echo $disc['id']; ?>">Editar</a>
                <a href="delete_disc.php?id=<?php echo $disc['id']; ?>">Excluir</a>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Link para adicionar novo disco -->
    <p><a href="add_disc.php">Adicionar Novo Disco</a></p>
    <footer>
        💽Melodia Vintage💽 | Criado por Igor Gomes, Athisson Mateus, Iarly Peterson e Kauan Gabriel | 2024
    </footer>
</body>
</html>

<?php

/*
    Documentação
    --------------
    Este script lida com a exibição dos discos do usuário logado. Ele inclui:

    1. Configuração da conexão com o banco de dados.
    2. Inicialização da sessão e autenticação do usuário.
    3. Consulta ao banco de dados para obter os discos pertencentes ao usuário logado.
    4. Exibição dos discos em um formato de lista, incluindo capa, nome, artista, ano e preço.
    5. Links para editar e excluir cada disco.
    6. Link para adicionar um novo disco.

    Uso:
    - Certifique-se de que o arquivo 'config.php' contém os detalhes corretos da conexão com o banco de dados.
    - Os usuários devem estar logados para visualizar seus discos.

    Tratamento de Erros:
    - Redireciona para a página de login se o usuário não estiver logado.
*/
?>