<?php

class akvarko {

    var $maxx, $maxy;
    var $max_ryb = 200;
    var $pole = array();

    /** Stručný popis funkce : Funkce construct načítající hodnoty X a Y
     * @param Sestavení konstruktora
     * @return X, Y 
     */
    function __construct($maxx, $maxy, $ryby) {
        $this->maxx = $maxx;
        $this->maxy = $maxy;
        foreach ($ryby as $k => $ryba)
            $this->pole[rand(1, $maxx)][rand(1, $maxy)][$ryba->id] = $ryba->zivot;
    }

    /** Stručný popis funkce : Zobrazuje ryby a volá je pomocí tabulky
     * @param Obrázek ryby a její zobrazení v tabulce 
     * @name $Ryby
     * @return Zobrazení rybek
     */
    function zobraz($ryby) {//zobrazení akvária
        echo '<table class="akvarko">';
        echo '<tr>';
        for ($x = 0; $x <= $this->maxx; $x++) {
            if ($x > 0)
                echo '<td class="popisx">' . $x . '</td>';
            else
                echo '<td class="popis"> </td>';
        }
        echo '</tr>';
        for ($y = 1; $y <= $this->maxy; $y++) {
            echo '<tr>';
            for ($x = 0; $x <= $this->maxx; $x++) {
                if ($x == 0)
                    echo '<td class="popisy">' . $y . '</td>';
                else {
                    echo '<td>';
                    if (is_array($this->pole[$x][$y]))
                        foreach ($this->pole[$x][$y] as $k => $v) {
                            $popis = $k . '(vek:' . $ryby[$k]->vek . ', zivot=' . $ryby[$k]->zivot . ', 
              pohlaví=' . $ryby[$k]->pohlavi . ')';
                            if ($ryby[$k]->zivot > 0) {
                                if ($ryby[$k]->vek >= $ryby[$k]->vek_dospela)
                                    echo '<img src="ryba-' . $ryby[$k]->barva . '-' . $ryby[$k]->pohlavi . '.png"
                   alt="' . $popis . '"  title="' . $popis . '" />';
                                else
                                    echo '<img src="jikra-' . $ryby[$k]->barva . '.png" 
                  alt="' . $popis . '" title="' . $popis . '" />';
                            }else {
                                if ($ryby[$k]->vek >= $ryby[$k]->vek_dospela) {
                                    echo '<img src="kostra.png" alt="' . $popis . '" title="' . $popis . '" />';
                                } else {
                                    echo '<img src="jikra.png" alt="' . $popis . '" title="' . $popis . '" />';
                                }
                            }
                        }
                    echo '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    /** Stručný popis funkce : Projíždí řádek po řádku a určuje pohyb ryb
     * @param Pozice X a Y jsou kontrolovány a přídávají se hodnoty pro posun více ryb
     * @return X, Y 
     */
    function pohyb($ryby) {
        $pompole = array();
        for ($y = 1; $y <= $this->maxy; $y++) { //projíždíme akvárko - řádky
            for ($x = 1; $x <= $this->maxx; $x++) { // projíždíme akvárko - sloupce
                if (is_array($this->pole[$x][$y])) // jsou-li ryby na dané pozici
                    foreach ($this->pole[$x][$y] as $k => $v) { // pro každou rybu na dané pozici
                        if ($ryby[$k]->zivot > 0) {// hejbou se jen živé ryby 
                            if ($ryby[$k]->vek >= $ryby[$k]->vek_dospela) { //živá dospělá ryba se hejbe osmy směry
                                $new_x = $x + rand(-1, 1);
                                $new_y = $y + rand(-1, 1);
                            } else {//živá vajíčka se nehejbou vůbec
                                $new_x = $x;
                                $new_y = $y;
                            }
                        } else {// to co je mrtvé padá dolů
                            $new_x = $x;
                            $new_y = $y + 1; //začátek je vlevo nahoře, tak padání znamená zvyšování souřadnice Y
                        }
                        if ($new_x > $this->maxx || $new_x < 1)
                            $new_x = $x; // ošetření mezí akvárka
                        if ($new_y > $this->maxy || $new_y < 1)
                            $new_y = $y; // ošetření mezí akvárka
                        $pompole[$new_x][$new_y][$k] = $v; // do pomocného pole nahrajeme nové souřadnice ryby
                    }
            }
        }
        $this->pole = array();
        $this->pole = $pompole; // pomocné pole nahrajeme do standardního pole pro ryby 
    }

    function boj($ryby) {
        $info = '';
        for ($y = 1; $y <= $this->maxy; $y++) { //projíždíme akvárko - řádky
            for ($x = 1; $x <= $this->maxx; $x++) { // projíždíme akvárko - sloupce
                if (is_array($this->pole[$x][$y])) // jsou-li ryby na dané pozici
                    foreach ($this->pole[$x][$y] as $id1 => $v1) { //pro všechny ryby na dané pozici
                        foreach ($this->pole[$x][$y] as $id2 => $v2) { //hledáme další ryby na dané pozici
                            if ($id1 != $id2) { //různé ryby
                                if ($ryby[$id1]->zivot > 0 && $ryby[$id2]->zivot > 0) {//obě živé
                                    if ($ryby[$id1]->rasa != $ryby[$id2]->rasa) {//různé rasy
                                        if ($ryby[$id1]->vek >= $ryby[$id1]->vek_dospela &&
                                                $ryby[$id2]->vek >= $ryby[$id2]->vek_dospela) {// utok dospělé na dospělou
                                            $sila = rand(1, $ryby[$id1]->zivot);
                                            $ryby[$id2]->zivot-=$sila;
                                            if ($ryby[$id2]->zivot < 0)
                                                $ryby[$id2]->zivot = 0;
                                            if ($ryby[$id2]->zivot == 0)
                                                $info.=$id1 . ' zabila ' . $id2 . ' (síla=' . $sila . ') [' . $x . ',' . $y . ']<br />';
                                            else
                                                $info.=$id1 . ' kousla ' . $id2 . ' (síla=' . $sila . ') [' . $x . ',' . $y . ']<br />';
                                        }
                                        if ($ryby[$id1]->vek >= $ryby[$id1]->vek_dospela &&
                                                $ryby[$id2]->vek < $ryby[$id2]->vek_dospela) {// utok dospělé na vejce
                                            $ryby[$id1]->zivot+=rand(1, $ryby[$id2]->zivot);
                                            $ryby[$id2]->zivot = 0;
                                            $info.=$id1 . ' sežrala jikru ' . $id2 . ' [' . $x . ',' . $y . ']<br />';
                                        }
                                    }
                                }
                            }
                        }
                    }
            }
        }
        return $info;
    }

    function starnuti($ryby) {
        foreach ($ryby as $key => $ryba) {
            $ryba->starnuti();
        }
    }

}

?>
