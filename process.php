<?php
//header("Expires: Sat, 04 Oct 2014 00:00:00 GMT");
//header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
//header("Cache-Control: post-check=0, pre-check=0",false);
//session_cache_limiter("must-revalidate");

require_once './app/lib/dbManager.php';
require_once './app/controler/page_controler.php';

function authenticate($_dni, $_apellido) {


    $res = NULL;
    try {

        db_manager::connect();
        $sql = "SELECT * FROM usuario WHERE dni = :dni";
        $query = db_manager::$db->prepare($sql);
        $query->execute(array(':dni' => $_dni));
        $res = $query->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
        $query = null;
    }
    if ($res) {

        if ($res[0]->apellido == $_apellido) {
            //setcookie($_dni, $_apellido, time()+3600);
            //if (!(session_status() === PHP_SESSION_ACTIVE)) {
            session_start();
            //}
            $_SESSION['loged'] = $_dni;
            $_SESSION['tipo'] = $res[0]->tipo_usuario;
            $_SESSION['apellido'] = $res[0]->apellido;
            page_controler::showPage();
        } else {
            echo "<script type='text/javascript'> alert('apellido incorrecto'); </script>";
            header("refresh:0,url=index.html");
        }
    } else {
        echo "<script type='text/javascript'> alert('no existe tal usuario'); </script>";
        header("refresh:0,url=index.html");
    }
}

if (filter_input(INPUT_POST, 'login')) {
    authenticate(filter_input(INPUT_POST, 'dni'), filter_input(INPUT_POST, 'apellido'));
}  


if (filter_input(INPUT_GET, 'logout')) {
    echo 'cerrando session ...';
    session_start();
    session_destroy();
    echo "<script type='text/javascript'> alert('Has cerrado session ,adios.!'); </script>";
    header("refresh:0,url=index.html");
}

if (!(session_status() === PHP_SESSION_ACTIVE)) {
    session_unset();
    session_start();
    if (!empty($_SESSION['loged'])) {
        page_controler::init();
        page_controler::showPage();
        //authenticate($_SESSION['loged'], $_SESSION['apellido']);
    } else {
        header("refresh:0,url=index.html");
        
    }
}






