<?php

require_once './page_controler.php';
require_once '../lib/dbManager.php';
require_once '../view/View.php';

if (filter_input(INPUT_POST, 'action')) {

    $p = filter_input_array(INPUT_POST);
    
    switch (filter_input(INPUT_POST, 'action')) {

        case 'save':
            db_manager::connect();
            db_manager::updateAsignatura($p['identificador'], $p['nombre']);
            header("refresh:0,url=../../process.php");
            break;

        case 'delete':
            db_manager::connect();
            db_manager::deleteAsignatura($p['identificador']);
            header("refresh:0,url=../../process.php");
            break;

        case 'show':
            page_controler::init();
            page_controler::showAsignatura($p['identificador'],$p['nombre']);
            break;
        
        case 'add_asignatura':
            $empty = (empty($p['identificador']) || empty($p['nombre']) );
            if ($empty) {
                echo "<script type='text/javascript'> alert('introduzca todos los datos !'); </script>";
                header("refresh:0,url=../../process.php");
            } else {
                db_manager::connect();
                db_manager::addAsignatura($p['identificador'], $p['nombre']);
                header("refresh:0,url=../../process.php");
            }
            break;

        default:
            header("refresh:0,url=../../process.php");
            break;
    }
}



