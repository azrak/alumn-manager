<?php

require_once './page_controler.php';
require_once '../lib/dbManager.php';
require_once '../view/View.php';

if (filter_input(INPUT_POST, 'action')) {
    $p = filter_input_array(INPUT_POST);
 
    switch (filter_input(INPUT_POST, 'action')) {

        case 'save':
            db_manager::connect();
            db_manager::updateUser($p['dni'], $p['apellido'], $p['tipo_usuario']);
            header("refresh:0,url=../../process.php");
            break;

        case 'delete':
            db_manager::connect();
            db_manager::deleteUser($p['dni']);
            header("refresh:0,url=../../process.php");
            break;

        case 'show':
            page_controler::init();
            page_controler::shwNotas($p['dni'],$p['apellido']);
            break;

        case 'add_user':
            $empty = (empty($p['dni']) || empty($p['apellido']) || empty($p['tipo_usuario']) );
            if ($empty) {
                echo "<script type='text/javascript'> alert('introduzca todos los datos !'); </script>";
                header("refresh:0,url=../../process.php");
            } else {
                db_manager::connect();
                db_manager::addUser($p['dni'], $p['apellido'], $p['tipo_usuario']);
                header("refresh:0,url=../../process.php");
            }
            break;

        default:
            header("refresh:0,url=../../process.php");
            break;
    }
}



