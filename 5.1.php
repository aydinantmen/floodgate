<?php

require_once 'config.php';

// OTURUMUN KULLANILMASI FLOOD TESPITI ICIN GEREKLI
session_start();

// ZIYARETCININ KAYDINI GIR; OTURUM ANAHTARI: IP ADRESI: ILK ISLEM ZAMANI: SON ISLEM ZAMANI: ZIYARET SAYISI - VE HER ZIYARETTE SON ISLEM ZAMANI ILE ISLEM SAYISINI GUNCELLE.
mysql_query("INSERT INTO flood SET id = '" . session_id() . "', ip = '" . $ip . "', first = '" . time() . "', last = '" . time() . "', visit = '1' ON DUPLICATE KEY UPDATE visit = visit + 1, last = '" . time() . "'");

// FLOOD SAYISI VARSAYILAN OLARAK 10 DUR.
// 10 SANIYEDE YAPILAN ISLEM SAYISI 10 VE FAZLASIYSA LAND FLOOD OLARAK ALGILANIR.
$q = mysql_query("SELECT ip FROM flood WHERE ip = '" . $ip . "' AND visit >= '" . $flood . "' AND last - first <= '" . $flood . "'");
if (mysql_affected_rows() > 0) {
    while ($attack = mysql_fetch_row($q)) {
        do_ban($attack[0], 'LAND Flood');
    }
}

// FLOOD SAYISI VARSAYILAN OLARAK 10 DUR.
// 10 SANIYENIN ALTINDA ISLEM SAYISI BIR OLAN: ILK VE SON ISLEM ZAMANI AYNI OLAN SABIT IP ADRESINDEN EN AZ 10 FARKLI OTURUM ACILIRSA HTTP FLOOD OLARAK ALGILANIR.
$q = mysql_query("SELECT ip, COUNT(*) as attacker, MAX(last) - MIN(first) as timing FROM flood WHERE ip = '" . $ip . "' AND visit = " . $flood . " AND last - first < " . $flood . " HAVING attacker > " . $flood . " AND timing < " . $flood . "");
if (mysql_affected_rows() > 0) {
    while ($attack = mysql_fetch_row($q)) {
        do_ban($attack[0], 'HTTP Flood');
    }
}

function do_ban($ip, $reason) {
    $htaccess = $_SERVER['DOCUMENT_ROOT'].'/.htaccess';
    $handler = fopen($htaccess, 'a');
    fwrite($handler,
        PHP_EOL . '# ' . $reason . ' blocked by Floodgate v-5;' .
        PHP_EOL . '# On: '.time() .
        PHP_EOL . 'deny from ' . $ip);
    fclose($handler);
}