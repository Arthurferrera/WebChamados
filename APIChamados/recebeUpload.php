<html>
<body>
<form> 
<input type="button" value="Voltar" onClick="history.go(-1)">
<br>

<?php

// Excelsior

// verifica se foi enviado um arquivo
if ( isset( $_FILES[ 'arquivo1' ][ 'name' ]  ) && $_FILES[ 'arquivo1' ][ 'error' ] == 0 ) {
    echo 'Você enviou o arquivo: <strong>' . $_FILES[ 'arquivo1' ][ 'name' ] . '</strong><br />';
    echo 'Você enviou o arquivo: <strong>' . $_FILES[ 'arquivo2' ][ 'name' ] . '</strong><br />';
    echo 'Você enviou o arquivo: <strong>' . $_FILES[ 'arquivo3' ][ 'name' ] . '</strong><br />';
    echo 'Este arquivo é do tipo: <strong > ' . $_FILES[ 'arquivo1' ][ 'type' ] . ' </strong ><br />';
    echo 'Este arquivo é do tipo: <strong > ' . $_FILES[ 'arquivo2' ][ 'type' ] . ' </strong ><br />';
    echo 'Este arquivo é do tipo: <strong > ' . $_FILES[ 'arquivo3' ][ 'type' ] . ' </strong ><br />';
    echo 'Temporariamente foi salvo em: <strong>' . $_FILES[ 'arquivo1' ][ 'tmp_name' ] . '</strong><br />';
    echo 'Seu tamanho é: <strong>' . $_FILES[ 'arquivo1' ][ 'size' ] . '</strong> Bytes<br /><br />';
    

    foreach ($_FILES as $file) {
        move_file($file);
    }
}
else{
    echo 'Você não enviou nenhum arquivo!';
}

function move_file($file)
{

    $arquivo_tmp = $file[ 'tmp_name' ];
    $nome = $file[ 'name' ];

    // Pega a extensão
    $extensao = pathinfo ( $nome, PATHINFO_EXTENSION );

    // Converte a extensão para minúsculo
    $extensao = strtolower ( $extensao );

    // Somente imagens, .jpg;.jpeg;.gif;.png
    // Aqui eu enfileiro as extensões permitidas e separo por ';'
    // Isso serve apenas para eu poder pesquisar dentro desta String
    if ( strstr ( '.jpg;.jpeg;.gif;.png', $extensao ) ) {
        // Cria um nome único para esta imagem
        // Evita que duplique as imagens no servidor.
        // Evita nomes com acentos, espaços e caracteres não alfanuméricos
        $novoNome = uniqid ( time () ) . '.' . $extensao;

        // Concatena a pasta com o nome
        $destino = 'C:/xampp/htdocs/webchamados/APIChamados/img/'.$novoNome;

        // tenta mover o arquivo para o destino
        if ( @move_uploaded_file ( $arquivo_tmp, $destino ) ) {
            echo 'Arquivo salvo com sucesso em : <strong>' . $destino . '</strong><br />';
        }
        else{
            echo 'Erro ao salvar o arquivo. Aparentemente você não tem permissão de escrita.<br />';
        }

    }
    else{
        echo 'Você poderá enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png"<br />';

    }
}

?>

</form>
</body> 
</html>
