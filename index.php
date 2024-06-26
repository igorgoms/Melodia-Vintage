<?php
// Inclui o arquivo de configuração do banco de dados
include 'config.php';

// Inicia a sessão para acessar as variáveis de sessão
session_start();

// Verifica se o usuário está logado verificando a existência da variável de sessão 'user_id'
$user_logged_in = isset($_SESSION['user_id']);

// Consulta SQL para selecionar todos os discos
$sql = "SELECT * FROM discs";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>💽Melodia Vintage💽: Discos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Barra de navegação -->
    <nav>
        <?php if ($user_logged_in): ?>
            <!-- Links para usuários logados -->
            <a href="add_disc.php">Adicionar Disco</a>
            <a href="profile.php">Perfil</a>
            <a href="logout.php">Sair</a>
            <?php if ($_SESSION['is_admin'] == 1): ?>
                <a href="moderation.php">Moderação</a>
            <?php endif; ?>
        <?php else: ?>
            <!-- Links para usuários não logados -->
            <a href="register.php">Registrar</a>
            <a href="login.php">Login</a>
        <?php endif; ?>

    </nav>
    
    <!-- Título principal -->
    <h1>💽Melodia Vintage💽</h1>
    <h2>Discos disponíveis</h2>
    
    <!-- Lista de discos -->
    <div class="discs">
        <?php while ($disc = $result->fetch_assoc()): ?>
            <div class="disc">
                <img src="uploads/<?php echo $disc['cover_image']; ?>" alt="<?php echo $disc['name']; ?>">
                <h2><?php echo $disc['name']; ?></h2>
                <p>Artista: <?php echo $disc['artist']; ?></p>
                <p>Ano: <?php echo $disc['year']; ?></p>
                <p>R$ <?php echo $disc['price']; ?></p>

                <!-- Exibe o elemento de áudio se houver um arquivo de faixa de áudio associado -->
                <?php if (!empty($disc['track_file'])): ?>
                    <audio controls>
                        <source src="uploads/<?php echo $disc['track_file']; ?>" type="audio/mpeg">
                        Seu navegador não suporta o elemento de áudio.
                    </audio>
                <?php endif; ?>
            </div> <!-- Fecha a div do disco -->
        <?php endwhile; ?>
    </div> <!-- Fecha a div de discos -->
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

    * Variavel $user_logged_in: utiliza a função 'issue' para verifica se o usuario esta logado utilizado a 
    chave ['user_id'] da variavel superglobal '$_SESSION'
        - $_SESSION: permite armazenar e recuperar dados durante uma sessão do usuário
    
    * Variavel $sql: Utiliza a instrução sql 'SELECT * FROM' para selecionar todos os discos da tabela 'discs'

    * Variavel $stml: utiliza a variavel $conn das configurações com o banco de dados e utiliza o metodo 'prepare'
    para preparar uma instrução sql para ser executada posteriormente, no caso, utiliza a variavel $sql
        - Em seguida utiliza o metodo 'execute()' para executar a operação
    
    * Variavel $result: apos a execução da variavel '$stmt',ele utiliza o metodo 'get_result()' para pegar o resultado da execução desta
    mesma variavel

    *Estrutura 'if ($user_logged_in):{}':
        'Se' a condição da Varivavel $user_logged (usuario logado) for real, mostre estes resultados de link da pagina:
            - Adicionar Disco/ Perfil / Logout
        
        * else: 'Se não' mostre estes resultados de link da pagina:
            - Registrar/ Login
    
        * endif: Estrutura utilizada para marca o fim da estrutura if-else
    
    *Estrutura 'while ($disc = $result->fetch_assoc()):':
        Utilizando a Estrutura de Repetição 'while', enquanto a Variavel '$disc' for igual a variavel '$result' que utiliza o metodo 
        'fetch_assoc()' para converter os dados da linha em um array, onde cada chave corresponde:
            - Capa do album -> $disc['cover_image']
            - Artista -> $disc['name']
            - Ano -> $disc['year']
            - Preço(R$) -> $disc['price']
    
    *Estrutura 'if(!empty($disc['track_file'])):'
        'Se' a variavel $disc['track_file'] não for vazia importe a faixa utilizando essa mesma variavel 
        * endwhile:  Estrutura utilizada para marca o fim da estrutura de repetição while
*/
?>