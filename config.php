<?php
// Configurações de conexão com o banco de dados

// Definir constantes para informações de conexão
define('DB_SERVER', '');                                #Add o servidor do banco de dados
define('DB_USERNAME', '');                              #Add o usuario admin do banco de dados
define('DB_PASSWORD', '');                              #Add a senha do banco de dados
define('DB_NAME', 'disco_vendas');                      #Add o nome do schema do banco de dados

// Cria uma nova conexão utilizando o objeto mysqli
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    // Em caso de erro, registrar um log e exibir uma mensagem de erro amigável
    error_log("Falha na conexão com o banco de dados: " . $conn->connect_error);
    die("Erro ao conectar ao banco de dados. Por favor, tente novamente mais tarde.");
}

// Configurar o conjunto de caracteres para UTF-8 (opcional, dependendo da configuração do seu banco de dados)
$conn->set_charset("utf8");

// Agora a variável $conn contém a conexão ativa com o banco de dados
// Todos os scripts que incluírem este arquivo podem utilizar a variável $conn para executar consultas SQL

/*
- Arquivo de Configuração com o Banco de dados Mysql -
    * função 'define': define a configuração de um determinado "paramentro" com o banco de dados 
    com a configuração do mesmo:
        - DB_SERVER (Data Base Servidor -> localhost (servidor local))
        - DB_USERNAME (Data Base Usuario -> root (usuario raiz [Superusuario]))
        - DB_PASSWORD (Senha do Data Base -> 123456)
        - DB_NAME (Nome do Data Base -> 'disco_vendas')

    * Varivel de conexão '$conn': estabele conexão com o Banco de dados  utilizando as 
    definições das função define anteriores
        - possui uma estrutura de condição "if" para caso a variavel de conexão 
        possua algum erro informe o 'error_log' e que mostre a intrução 'die' para finalizar
        a execução do script para futuramente tenta outra vez

    * função 'set_charset' utilizada para definir o conjunto de caracteries que ira ser utilizado
    na comunicação entre o php e o banco de dados
        - Configuração Escolhida 'UTF-8': conjunto de caracteries utilizado pois suporta uma grande 
        variedade de idiomas e símbolos, alem de garantir que os dados sejam trocados corretamente evitando problemas de
        codificiacao e exibição incorretra de caracteries
*/
?>
