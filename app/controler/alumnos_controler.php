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

function getId_Asignatura_ByName($name) {
    $_id = '';
    db_manager::connect();
    $asigns = db_manager::getAllData('asignatura');
    foreach ($asigns as $as) {
        if ($as->nombre === $name) {
            $_id = $as->identificador;
        }
    }
    return $_id;
}

if (filter_input(INPUT_POST, 'action')) {
    $p = filter_input_array(INPUT_POST);

    switch (filter_input(INPUT_POST, 'action')) {

        case 'save':
            db_manager::connect();
            db_manager::update_alumn_nota($p['dni'], $p['nota'], getId_Asignatura_ByName($p['asignatura']) );
            page_controler::init();
            page_controler::shwNotas($p['dni'],$p['alumno']);
            break;

        case 'delete':
            db_manager::connect();
            db_manager::delete_alumn_nota($p['dni'], $p['nota'], getId_Asignatura_ByName($p['asignatura']));
            page_controler::init();
            page_controler::shwNotas($p['dni'],$p['alumno']);
            break;


        case 'add_nota_toAlumno':
            if (empty($p['dni']) || empty($p['asignatura'])) {
                echo "<script type='text/javascript'> alert('introduzca todos los datos !'); </script>";
                page_controler::init();
                page_controler::shwNotas($p['dni'],$p['alumno']);
            }
            db_manager::connect();
            db_manager::addNota($p['dni'], $p['nota'], $p['asignatura']);
            page_controler::init();
            page_controler::shwNotas($p['dni'],$p['alumno']);
            break;

        default:
            header("refresh:0,url=../../process.php");
            break;
    }
}



