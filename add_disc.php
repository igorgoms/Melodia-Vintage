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

// Define variáveis para armazenar os dados do formulário
$name = $artist = $year = $price = '';
$cover_image = $track_file = '';

// Verifica se o método da requisição é POST (ou seja, se o formulário foi enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $name = $_POST['name'];
    $artist = $_POST['artist'];
    $year = $_POST['year'];
    $price = $_POST['price'];

    // Validação básica dos campos obrigatórios
    if (empty($name) || empty($artist) || empty($price)) {
        echo "Por favor, preencha todos os campos obrigatórios.";
        exit();
    }

    // Verifica se foi enviado um arquivo de imagem (capa do disco)
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        // Define o diretório de upload
        $upload_dir = 'uploads/';

        // Gera um nome único para o arquivo de imagem
        $cover_image = uniqid('cover_') . '_' . $_FILES['cover_image']['name'];

        // Move o arquivo enviado para o diretório de upload
        if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $upload_dir . $cover_image)) {
            echo "Erro ao realizar o upload da capa do disco.";
            exit();
        }
    } else {
        echo "Erro: É necessário enviar uma capa de disco.";
        exit();
    }

    // Verifica se foi enviado um arquivo de áudio (faixa do disco)
    if (isset($_FILES['track_file']) && $_FILES['track_file']['error'] === UPLOAD_ERR_OK) {
        // Define o diretório de upload
        $upload_dir = 'uploads/';

        // Gera um nome único para o arquivo de áudio
        $track_file = uniqid('track_') . '_' . $_FILES['track_file']['name'];

        // Move o arquivo enviado para o diretório de upload
        if (!move_uploaded_file($_FILES['track_file']['tmp_name'], $upload_dir . $track_file)) {
            echo "Erro ao realizar o upload da faixa do disco.";
            exit();
        }
    }

    // Insere os dados do disco no banco de dados
    $sql = "INSERT INTO discs (user_id, name, artist, year, price, cover_image, track_file) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $user_id, $name, $artist, $year, $price, $cover_image, $track_file);

    if ($stmt->execute()) {
        // Redireciona para a página de discos do usuário após a inserção
        header('Location: my_discs.php');
        exit();
    } else {
        echo "Erro ao inserir o disco no banco de dados.";
    }
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
    <nav>
        <a href="index.php">Início</a>
        <a href="profile.php">Perfil</a>
        <a href="logout.php">Sair</a>
    </nav>

    <h1>Adicionar Novo Disco</h1>

    <form action="add_disc.php" method="post" enctype="multipart/form-data">
        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="artist">Artista:</label>
        <input type="text" id="artist" name="artist" required><br>
        <label for="year">Ano:</label>
        <input type="text" id="year" name="year"><br>
        <label for="price">Preço:</label>
        <input type="text" id="price" name="price" required><br>
        <label for="cover_image">Capa do Disco:</label>
        <input type="file" id="cover_image" name="cover_image" accept="image/*" required><br>
        <label for="track_file">Faixa do Disco:</label>
        <input type="file" id="track_file" name="track_file" accept="audio/*"><br>
        <button type="submit">Adicionar Disco</button>
    </form>
    <footer>
        💽Melodia Vintage💽 | Criado por Igor Gomes, Athisson Mateus, Iarly Peterson e Kauan Gabriel | 2024
    </footer>
</body>
</html>

<?php
/*
    Documentação
    --------------
    Este script lida com a adição de um novo disco ao banco de dados. Ele inclui:

    1. Configuração da conexão com o banco de dados.
    2. Inicialização da sessão e autenticação do usuário.
    3. Manipulação do formulário para capturar detalhes do disco como nome, artista, ano, preço, capa do disco e arquivo de faixa.
    4. Validação básica dos campos obrigatórios.
    5. Manipulação do upload de arquivos para a capa do disco e a faixa do disco.
    6. Inserção dos dados do disco no banco de dados.
    7. Redirecionamento para a página de discos do usuário após a inserção bem-sucedida.

    Uso:
    - Certifique-se de que o arquivo 'config.php' contém os detalhes corretos da conexão com o banco de dados.
    - Os usuários devem estar logados para adicionar um novo disco.
    - Campos obrigatórios: Nome, Artista, Preço, Capa do Disco.
    - Campos opcionais: Ano, Faixa do Disco.
    - Os arquivos enviados são armazenados no diretório 'uploads/'.

    Tratamento de Erros:
    - Exibe mensagens de erro para campos obrigatórios ausentes, falhas no upload de arquivos e erros de inserção no banco de dados.
*/
?>
