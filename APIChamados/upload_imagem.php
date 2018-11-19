<?php

//  sleep(10);

  //Pegando a imagem enviada em formato de string
  $recievedImage = $_REQUEST['image'];

  //convertendo a string em objeto binario
  $binary=base64_decode($recievedImage);

  header('Content-Type: bitmap; charset=utf-8');

  //gerando um ID unico para a imagem
  $img_name = uniqid().".jpg";

  //criando o arquivo na pasta img
  $file = fopen('img/'.$img_name, 'wb');
  fwrite($file, $binary);
  fclose($file);

  //devolvendo o nome do arquivo que foi salvo
  echo $img_name;

 ?>
