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

// Verifica se há um ID de disco na query string (GET)
if (isset($_GET['id'])) {
    $disc_id = $_GET['id'];

    // Exclui o disco apenas se pertencer ao usuário logado
    $sql = "DELETE FROM discs WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $disc_id, $user_id);
    $stmt->execute();

    // Após excluir o disco, redireciona para a página 'my_discs.php'
    header('Location: my_discs.php');
    exit();
} else {
    // Se não houver ID na query string, redireciona para 'my_discs.php'
    header('Location: my_discs.php');
    exit();
}

/*
    Documentação
    --------------
    Este script lida com a exclusão de um disco do banco de dados. Ele inclui:

    1. Configuração da conexão com o banco de dados.
    2. Inicialização da sessão e autenticação do usuário.
    3. Verificação se há um ID de disco na query string.
    4. Exclusão do disco do banco de dados apenas se ele pertencer ao usuário logado.
    5. Redirecionamento para a página 'my_discs.php' após a exclusão bem-sucedida ou se não houver ID de disco na query string.

    Uso:
    - Certifique-se de que o arquivo 'config.php' contém os detalhes corretos da conexão com o banco de dados.
    - Os usuários devem estar logados para excluir um disco.
    - O disco será excluído apenas se o ID do disco estiver presente na query string e pertencer ao usuário logado.

    Tratamento de Erros:
    - Redireciona para 'my_discs.php' se não houver ID de disco na query string ou após a exclusão bem-sucedida.
*/
?>
