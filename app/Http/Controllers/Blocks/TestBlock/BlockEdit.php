<?php 

$dane = (!empty($edit->liczba_danych)) ? $edit->liczba_danych : '';
$ff->addText('liczba_danych','Liczba danych', $dane);