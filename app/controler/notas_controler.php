<?php

require_once './page_controler.php';
require_once '../lib/dbManager.php';
require_once '../view/View.php';

function get_dni_ByNombre($nombre) {
    db_manager::connect();
    $users = db_manager::getAllData('usuario');
    foreach ($users as $usr) {
        if ($usr->apellido === $nombre) {
            $_dni = $usr->dni;
        }
    }
    return $_dni;
}

function get_Asignatura_ById($id) {
    db_manager::connect();
    $asigns = db_manager::getAllData('asignatura');
    foreach ($asigns as $as) {
        if ($as->identificador === $id) {
            $_name = $as->nombre;
        }
    }
    return $_name;
}

if (filter_input(INPUT_POST, 'action')) {
    $p = filter_input_array(INPUT_POST);

    switch (filter_input(INPUT_POST, 'action')) {

        case 'save':
            db_manager::connect();
            db_manager::updateNota(get_dni_ByNombre($p['nombre']), $p['nota'], $p['asignatura']);
            page_controler::init();
            page_controler::showAsignatura($p['asignatura'], get_Asignatura_ById($p['asignatura']));
            break;

        case 'delete':
            db_manager::connect();
            db_manager::deleteNota(get_dni_ByNombre($p['nombre']));
            page_controler::init();
            page_controler::showAsignatura($p['asignatura'], get_Asignatura_ById($p['asignatura']));
            break;


        case 'add_nota':
            if (empty($p['dni']) || empty($p['asignatura'])) {
                echo "<script type='text/javascript'> alert('introduzca todos los datos !'); </script>";
                page_controler::init();
                page_controler::showAsignatura($p['asignatura'], get_Asignatura_ById($p['asignatura']));
            }
            db_manager::connect();
            db_manager::addNota($p['dni'], $p['nota'], $p['asignatura']);
            page_controler::init();
            page_controler::showAsignatura($p['asignatura'], '');
            break;

        default:
            header("refresh:0,url=../../process.php");
            break;
    }
}



