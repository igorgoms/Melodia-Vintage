<?php
// Inicia a sessão para acessar as variáveis de sessão
session_start();

// Limpa todas as variáveis de sessão
session_unset();

// Destrói a sessão atual
session_destroy();

// Redireciona o usuário de volta para a página inicial (index.php)
header('Location: index.php');
exit();

/*
    * Função 'session_start()': utilizada para iniciar ou continuar uma sessão no servidor, verifica se existe
    uma sessao ja iniciada pelo usuario atual

    * Função 'session_unset()': utilizada para remover todas as variaveis da sessão associadas com a sessão atual

    * Função 'session_destroy()': Serve para finalizar e destruir a sessão atual do usuario

    *Logo apos utiliza a função header('Location':) para redirecionar pagina inicial(index.php) e utiliza a função
    'exit()' para terminar a execução do script
*/
?>
