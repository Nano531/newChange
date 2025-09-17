<?php
session_start();
require('presentacion/pagina.class.php');
$pagina = new pagina();
$pagina -> SetTitulo('People Mananger');
$pagina->SetNivel("");
$pagina -> Mostrar();
?>