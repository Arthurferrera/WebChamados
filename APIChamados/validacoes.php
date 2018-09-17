<?php
// função que valida o cnpj
function validarCnpj($cnpj) {
    $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
    // Valida tamanho
    if (strlen($cnpj) != 14)
        return false;
    // Valida primeiro dígito verificador
    for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
    {
        $soma += $cnpj{$i} * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }
    $resto = $soma % 11;
    if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
        return false;
    // Valida segundo dígito verificador
    for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
    {
        $soma += $cnpj{$i} * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }
    $resto = $soma % 11;
    return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
}

// função que exige um certo padrão para a senha
function validarSenha($senha){

    // filtra só os valores inteiros
    $temNumeros = filter_var($senha, FILTER_SANITIZE_NUMBER_INT) !== '';
    // 1 (true, tem maiusculas) ou 0 (false, não tem)
    $temLetras = preg_match('/[a-z-A-Z]/', $senha);
    // 1 (true, tem maiusculas) ou 0 (false, não tem)
    // $temMaiusculas = preg_match('/[A-Z]/', $senha);

    if ($temNumeros && $temLetras) {
        return true;
    } else {
        return false;
    }
}
 ?>
