<?php

echo json_encode('tudo certo');
die;

// $fp = fsockopen("IP_DO_UNM", 3337, $errno, $errstr, 100);
$fp = fsockopen("172.33.255.50", 3337, $errno, $errstr, 100);
if (!$fp) {
    die("$errstr ($errno)\n");
} else {
    // fwrite($fp, "LOGIN:::CTAG::UN=USUARIO_DO_UNM,PWD=SENHA_DO_UNM;");
    fwrite($fp, "LOGIN:::CTAG::UN=admin,PWD=10l15p130A@;");
    while (true) {
        $c = fread($fp, 1);
        if ($c == ";") {
            break;
        }
        $lin = trim($c . fgets($fp));
        $ret[] = $lin;
    }
}
fwrite($fp, "LST-ONU::OLTID=192.168.216.2:CTAG::;");
while (true) {
    $c = fread($fp, true);
    if ($c == ";") {
        break;
    }
    $lin = trim($c . fgets($fp));
    $rett[] = $lin;
}
$onus = [];
foreach ($rett as $linha) {
    $explode = explode("\t", $linha);
    array_push($onus, $explode);
}
$final = [];
foreach ($onus as $onu) {
    if (sizeof($onu) == 12 && $onu[3] <> "NAME" && $onu[8] <> "MAC") {
        array_push($final, array(
            "pon" => utf8_encode($onu[1]),
            "index" => utf8_encode($onu[2]),
            "nome" => utf8_encode($onu[3]),
            "modelo" => utf8_encode($onu[5]),
            "mac" => utf8_encode($onu[8]),
            "firmware" => utf8_encode($onu[11]),
        ));
    };
}
echo json_encode($final);
