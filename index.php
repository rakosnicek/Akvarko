<?php
/*
 * @author: Kamila Tomková
 * @licence opensource
 * @param nahrání třídy pro rybu a akvárko
 */
require 'ryba.php'; //nahrání třídy pro rybu
require 'akvarko.php'; //nahrání třídy pro akvárium
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <link rel="StyleSheet" type="text/css" href="styl.css" />
        <title>Akvárium</title>
    </head>
    <body>
        <?php
        $pocet_kol = 20;
        $pocet_ryb = 50;
        $max_x = 10;
        $max_y = 10;

        echo '<h2>Akvárko</h2>';

//vytváření ryb do pole - pole objektů
        $ryby = array();
        for ($i = 1; $i <= $pocet_ryb; $i++) {
            $ryby[ryba1::$stid] = new ryba1(rand(1, 2), rand(1, 15)); //vytvořme rybu  (barva, vek)
        }

//vytvoření akvária a vložení ryb do něj  
        $akvarko = new akvarko($max_x, $max_y, $ryby);
        $akvarko->zobraz($ryby); // prvotní nakreslení akvaria
//základní cyklus
        echo '<table>';
        for ($kolo = 1; $kolo <= $pocet_kol; $kolo++) {
            $akvarko->stari($ryby); //starnutí ryb v akvariu;
            $akvarko->pohyb($ryby); //pohyb ryb v akvariu;
            $info = '';
            $info.=$akvarko->rozmnozeni($ryby); //tření ryb v akvariu;
            $info.=$akvarko->kousnuti($ryby); //boj ryb v akvariu;
            echo '<tr><td colspan="2">';
            echo '</td></tr><tr><td>';
            $akvarko->zobraz($ryby); //nakreslení akvaria
            echo '</td><td class="info">';
            echo $info;
            echo '</td></tr>';
        }
        echo '</table>';
        ?>
    </body>
</html>
