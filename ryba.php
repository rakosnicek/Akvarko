<?php

class ryba {

    var $pohlavi; //jaké ma ryba pohlaví
    var $zivot; //jak je ryba silná
    var $barva; //rasa ryby 
    var $id; //aktuální unikátní id ryby
    var $vek; //aktuální věk ryby
    var $vek_dospela = 5; //věk kdy se z vejce stane ryba
    var $vek_umrti = 15; //věk kdy začíná ryba chřadnout
    var $vek_max_rozmnozeni = 15; //do kdy se může ryba třít
    var $doba_neplodnosti = 5; //jak dlouho nebude moci se ryba třít, byla-li aktuálně vytřena
    public static $stid = 1;

    function __construct($barva, $vek = 1) {
        $this->id = self::$stid++;
        $this->vek = $vek;
        $this->pohlavi = ((rand(1, 50) >= 25) ? 'zena' : 'muz'); //pohlaví buď m nebo f 
        $this->zivot = rand(50, 100);
        $this->barva = $barva;
    }

    function info() {
        echo 'Id=' . $this->id;
        echo ', věk=' . $this->vek;
        echo ', pohlaví=' . $this->pohlavi;
        echo ', zivot=' . $this->zivot;
        echo ', barva=' . $this->barva;
        echo '<br />';
    }

    function starnuti() {
        if ($this->zivot > 0) {//stárnou jen zivé
            $this->vek++;
            //umrti = nastavit zivot na 0
            if ($this->vek >= $this->vek_umrti)
                $this->zivot-=rand(10, 15);
            if ($this->zivot < 0)
                $this->zivot = 0;
        }
    }

}

?>
