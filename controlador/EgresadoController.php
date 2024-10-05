<?php
include_once '../modelo/Egresado.php';
$egresado = new Egresado();

//-------------------------------------------------------------------
// Función para listar egresados
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'listar') {
    $json = Array();
    $egresado->BuscarTodos('');
    foreach ($egresado->objetos as $objeto) {
        $json[] = array(
            'identificacion' => $objeto->identificacion,
            'nombreCompleto' => $objeto->nombrecompleto,
            'dirResidencia' => $objeto->dirresidencia,
            'telResidencia' => $objeto->telresidencia,
            'telAlternativo' => $objeto->telalternativo,
            'correoPrincipal' => $objeto->correoprincipal,
            'correoSecundario' => $objeto->correosecundario,
            'carnet' => $objeto->carnet,
            'sexo' => $objeto->sexo,
            'fallecido' => $objeto->fallecido,
            'nombreGestion' => $objeto->nombre_gestion,
            'titulo' => $objeto->titulo,
            'avatar' => $objeto->avatar
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

//-------------------------------------------------------------------
// Función para buscar un egresado
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'buscar') {
    $json = Array();
    $egresado->Buscar($_POST['dato']);
    foreach ($egresado->objetos as $objeto) {
        $json[] = array(
            'identificacion' => $objeto->identificacion,
            'nombreCompleto' => $objeto->nombrecompleto,
            'dirResidencia' => $objeto->dirresidencia,
            'telResidencia' => $objeto->telresidencia,
            'telAlternativo' => $objeto->telalternativo,
            'correoPrincipal' => $objeto->correoprincipal,
            'correoSecundario' => $objeto->correosecundario,
            'carnet' => $objeto->carnet,
            'sexo' => $objeto->sexo,
            'fallecido' => $objeto->fallecido,
            'idGestion' => $objeto->idgestion,
            'nombreGestion' => $objeto->nombre_gestion,
            'avatar' => $objeto->avatar
        );
    }
    $jsonstring = json_encode($json[0]);
    echo $jsonstring;
}

//-------------------------------------------------------------------
// Función para crear
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'crear') {
    $respuesta = $egresado->Crear($_POST['identificacion'], $_POST['nombreCompleto'], $_POST['dirResidencia'], $_POST['telResidencia'],
        $_POST['telAlternativo'], $_POST['correoPrincipal'], $_POST['correoSecundario'], $_POST['carnet'],
        $_POST['sexo'], $_POST['fallecido'], $_POST['idGestion'], $_POST['avatar']);
    
    echo $respuesta; // Este echo debe retornar 'add' si fue añadido y 'noadd' si ya existía
}


//-------------------------------------------------------------------
// Función para editar
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'editar') {
    $egresado->Editar($_POST['identificacion'], $_POST['nombreCompleto'], $_POST['dirResidencia'], $_POST['telResidencia'],
        $_POST['telAlternativo'], $_POST['correoPrincipal'], $_POST['correoSecundario'], $_POST['carnet'],
        $_POST['sexo'], $_POST['fallecido'], $_POST['idGestion']);
    echo 'update';    
}

//-------------------------------------------------------------------
// Función para eliminar
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'eliminar') {
    $egresado->Eliminar($_POST['id']);
}

//-------------------------------------------------------------------
// Función para cargar select
//-------------------------------------------------------------------
if ($_POST['funcion'] == 'seleccionar') {
    $json = Array();
    $egresado->Seleccionar();
    foreach ($egresado->objetos as $objeto) {
        $json[] = array(
            'id' => $objeto->identificacion,
            'nombre' => $objeto->nombrecompleto
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

//----------------------------------------------------------------------------------
//Este tipo de funcion solo aplica para el formData (envio de archivos e imagenes)
//----------------------------------------------------------------------------------
if ($_POST['funcion'] == 'cambiar_logo') {
    if (($_FILES['photo']['type'] == 'image/jpeg') || ($_FILES['photo']['type'] == 'image/png') || ($_FILES['photo']['type'] == 'image/gif')) {
        //Se obtiene el nombre del archivo
        $nombre = uniqid() . '-' . $_FILES['photo']['name'];
        //Concatena el directorio con el nombre del archivo
        $ruta = '../assets/img/prod/' . $nombre;
        //Funcion PHP que sube la imagen al servidor
        move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
        $egresado->CambiarLogo($_POST['id_avatar'], $nombre);

        foreach ($egresado->objetos as $objeto) {
            if ($objeto->avatar != 'default.png')
                unlink('../assets/img/prod/' . $objeto->avatar);
        }
        //Retorno de un Json con dos valores
        $json = array();
        $json[] = array(
            'ruta' => $ruta,
            'alert' => 'editalogo'
        );
    }
    //En caso de una imagen con formato incorrecto
    else {
        $json = array();
        $json[] = array(
            'alert' => 'noeditalogo'
        );
    }
    $jsonstring = json_encode($json[0]);
    echo $jsonstring;
}
?>
