<?php

$appDir = $_SERVER['DOCUMENT_ROOT'] . '/alumne-manager/';
require_once $appDir . '/app/lib/dbManager.php';
require_once $appDir . '/app/view/View.php';

class page_controler {

    private static $usuario;
    private static $appDir;
    private static $head_data;

    function __construct() {
        
    }

    public static function init() {

        if (!(session_status() === PHP_SESSION_ACTIVE)) {
            session_start();
        }
        self::$appDir = 'http://' . $_SERVER['SERVER_NAME'] . '/alumne-manager/';
        self::$head_data = '<title>Alumno</title>
            <link href="' . self::$appDir . 'bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css">
            <link href="' . self::$appDir . 'stylec.css" rel="stylesheet" type="text/css">';
    }

    public static function showPage() {
        self::init();
        switch ($_SESSION['tipo']) {
            case 0:
                self::showAdmin();
                break;

            case 1:
                self::shwNotas($_SESSION['loged'], $_SESSION['apellido']);
                break;

            default:
                echo "<script type='text/javascript'> alert('Usuario no existe '); </script>";
                header("refresh:0,url= " . $appDir);
                break;
        }
    }

    public static function shwNotas($user_dni, $user_apellido) {
        $data2 = array();
        db_manager::connect();
        $notas = db_manager::getNotas($user_dni);

        $data2['dni'] = $user_dni;
        $data2['user'] = $_SESSION['loged'];
        $data2['alumno'] = $user_apellido;
        $data2['head_data'] = self::$head_data;
        $data2['asignatura_list'] = self::get_asignaturas_list($notas);
        $data2['notas_tr'] = self::get_NotasTRow($notas, $user_apellido, $user_dni);

        View::setData($data2);
        $appDir = $_SERVER['DOCUMENT_ROOT'] . '/alumne-manager/';
        if ($_SESSION['tipo'] == 0) {
            View::showTemplate($appDir . '/app/view/templates/show_alumno_admin.html');
        } else {
            View::showTemplate($appDir . '/app/view/templates/show_alumno.html');
        }
    }

    private static function showAdmin() {

        $data = array();

        $head_data = self::$head_data;
        db_manager::connect();
        $alumnos = db_manager::getAllData('usuario');
        $asignaturas = db_manager::getAllData('asignatura');
        $data['head_data'] = $head_data;
        $data['user'] = $_SESSION['apellido'];
        $data['alumno_tr'] = self::get_AlumnTRows($alumnos);
        $data['asignatura_tr'] = self::get_AsignaturasTRows($asignaturas);

        View::setData($data);
        $appDir = $_SERVER['DOCUMENT_ROOT'] . '/alumne-manager/';
        View::showTemplate($appDir . '/app/view/templates/show_admin.html');
    }

    private static function get_tablButtons() {

        $tdButtons = ' <td>
                <div class="btn-group">
                    <button name="action" value="save"  class="btn btn-default btn-sm"><span class="glyphicon glyphicon-floppy-save"></span>
                    </button>
                    <button name="action" value="delete" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> 
                    </button>
                   <button name="action" value="show" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-eye-open"></span>
                    </button>
                </div>

            </td>';
        return $tdButtons;
    }

    private static function get_tablButtons2() {

        $tdButtons = ' <td>
                <div class="btn-group">
                    <button name="action" value="save"  class="btn btn-default btn-sm"><span class="glyphicon glyphicon-floppy-save"></span>
                    </button>
                    <button name="action" value="delete" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> 
                    </button>
                   
                </div>

            </td>';
        return $tdButtons;
    }

    private static function get_AlumnTRows($alumnData) {
        $tr = '';
        foreach ($alumnData as $alumno) {
            $tr.= '<tr>  <form action="app/controler/usuario_controler.php" method="POST">';
            $tr.= '<td>
                <input type="text" class="form-control input-sm"
                name="dni" value="' . $alumno->dni . '" size="13">
            </td>';
            $tr.='<td>

                <input type="text" class="form-control input-sm"
                name="apellido" value="' . $alumno->apellido . '" size="13">
            </td>';
            
            $selected0 = '';
            $selected1 = '';
            if ($alumno->tipo_usuario == 0) {
                $selected0 = "selected='selected'";
            } elseif ($alumno->tipo_usuario == 1) {
                $selected1 = "selected='selected'";
            }

            $tr.=' <td>
                <select name="tipo_usuario" >
                <option value="0" ' . $selected0 . ' >0</option>
                <option value="1" ' . $selected1 . ' >1</option>
                </select>
                
            </td>';
            $tr.= self::get_tablButtons();
            $tr .= '</form> </tr>';
        }
        return $tr;
    }

    private static function get_AsignaturasTRows($asignData) {
        $tr2 = '';
        foreach ($asignData as $asignatura) {
            $tr2.= '<tr> <form action="app/controler/asignatura_controler.php" method="POST">';
            $tr2.= '<td>' . $asignatura->identificador . '</td>';
            $tr2.= '<input type="hidden" name="identificador" value="' . $asignatura->identificador . '">';
            $tr2.='<td>

                <input type="text" class="form-control input-sm"
                name="nombre" value="' . $asignatura->nombre . '" size="13">
            </td>';

            $tr2.= self::get_tablButtons();
            $tr2.='</form></tr>';
        }
        return $tr2;
    }

    private static function get_Notas_By_AsignaTRows($asData, $_asId) {
        $tr4 = '';
        foreach ($asData as $as) {
            $tr4.= '<tr> <form action="notas_controler.php" method="POST">';
            $tr4.= '<td>
                ' . $as->apellido . '
            </td>';
            $tr4.='<td>

                <input type="text" class="form-control input-sm"
                name="nota" value="' . $as->nota . '" size="13">
                <input type="hidden" name="nombre" value="' . $as->apellido . '">
                <input type="hidden" name="asignatura" value="' . $_asId . '">
            </td>';

            $tr4.= self::get_tablButtons2();
            $tr4.='</form></tr>';
        }
        return $tr4;
    }

    private static function get_NotasTRow($quertData, $user_apellido, $user_dni) {
        $tr3 = '';
        if (!(session_status() === PHP_SESSION_ACTIVE)) {
            session_start();
        }
        switch ($_SESSION['tipo']) {
            case 0:
                foreach ($quertData as $nota) {
                    $tr3.= '<tr> <form action="alumnos_controler.php" method="POST">';
                    $tr3.= ' <input type="hidden" value="' . $nota->nombre . '" name="asignatura" >';
                    $tr3.= ' <input type="hidden" value="' . $user_dni . '" name="dni" >';
                    $tr3.= ' <input type="hidden" value="' . $user_apellido . '" name="alumno" >';
                    $tr3.= '<td>' . $nota->nombre . '</td>';
                    $tr3.='<td>

                        <input type="text" class="form-control input-sm"
                        name="nota" value="' . $nota->nota . '" size="13">
                        </td>';

                    $tr3.= self::get_tablButtons2();
                    $tr3.='</form></tr>';
                }
                break;

            case 1:
                foreach ($quertData as $nota) {
                    $tr3.= '<tr>';
                    $tr3.= '<td>' . $nota->nombre . '</td>';
                    $tr3.='<td>' . $nota->nota . '</td>';
                    $tr3.= '</tr>';
                }
                break;

            default:
                echo "<script type='text/javascript'> alert('Usuario no existe '); </script>";
                header("refresh:0,url= " . $appDir);
                break;
        }
        return $tr3;
    }

    public static function showAsignatura($p, $asig_nom) {
        $data3 = array();

        db_manager::connect();
        $asig = db_manager::getNotas_ByAsignatura($p);

        $data3['user'] = $_SESSION['loged'];
        $data3['asignatura'] = $asig_nom;
        $data3['asignatura_id'] = $p;
        $data3['head_data'] = self::$head_data;
        $data3['user_list'] = self::get_user_list($asig);
        $data3['notas_tr'] = self::get_Notas_By_AsignaTRows($asig, $p);

        View::setData($data3);
        $appDir = $_SERVER['DOCUMENT_ROOT'] . '/alumne-manager/';
        View::showTemplate($appDir . '/app/view/templates/show_asignatura.html');
    }

    public static function get_user_list($data) {
        db_manager::connect();
        $data1 = db_manager::getAllData('usuario');
// eliminar existentes
        foreach ($data1 as $d1) {

            foreach ($data as $d2) {
                if ($d1->apellido === $d2->apellido) {
                    $d1->apellido = null;
                    $d1->dni = null;
                }
            }
        }
        $ls = '';

        foreach ($data1 as $d) {
            if ($d->apellido) {
                $ls.= '<option value="' . $d->dni . '" >' . $d->apellido . '</option>';
            }
        }
        return $ls;
    }

    public static function get_asignaturas_list($data) {
        db_manager::connect();
        $data1 = db_manager::getAllData('asignatura');
// eliminar existentes
        foreach ($data1 as $d1) {

            foreach ($data as $d2) {
                if ($d1->nombre === $d2->nombre) {
                    $d1->identificador = null;
                    $d1->nombre = null;
                }
            }
        }
        $ls = '';

        foreach ($data1 as $d) {
            if ($d->nombre) {
                $ls.= '<option value="' . $d->identificador . '" >' . $d->nombre . '</option>';
            }
        }
        return $ls;
    }

}
