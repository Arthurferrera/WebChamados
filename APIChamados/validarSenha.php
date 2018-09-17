<?php
    $senha = "";
    validarSenha($senha);

    function validarSenha($senha){
        $temNumeros = filter_var($senha, FILTER_SANITIZE_NUMBER_INT) !== '';
        if ($temNumeros) {
            echo "Tem numero";
        }

        $temMaiusculas = preg_match('/[A-Z]/', $senha); // 1 (true, tem maiusculas) ou 0 (false, não tem
        if ($temMaiusculas) {
            echo "<br>Tem maiusculas";
        }

        $temMinusculas = preg_match('/[a-z]/', $senha); // 1 (true, tem maiusculas) ou 0 (false, não tem
        if ($temMinusculas) {
            echo "<br>Tem minuscilas";
        }
        if ($temNumeros && $temMaiusculas && $temMinusculas) {
            echo "<br>Tem tudo";
        }
    }
 ?>
