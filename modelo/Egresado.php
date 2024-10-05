<?php
include_once 'Conexion.php';

class Egresado {
    var $objetos;

    public function __construct() {
        $db = new Conexion();
        $this->acceso = $db->pdo;
    }

    //-----------------------------------------------------------
    // Buscar los registros segun criterio de busqueda en consulta
    //-----------------------------------------------------------
    function BuscarTodos($consulta = '') {
        if (!empty($consulta)) {
            $sql = "SELECT e.identificacion, e.nombreCompleto, e.dirResidencia, e.telResidencia, e.telAlternativo, e.correoPrincipal,
                            e.correoSecundario, e.carnet, e.sexo, e.fallecido, e.idGestion, g.nombre as nombre_gestion, t.nombre as titulo, e.avatar
                    FROM egresado e
                    JOIN gestion g ON e.idGestion = g.idGestion
                    LEFT JOIN tituloegresado te ON e.identificacion = te.identificacion
                    LEFT JOIN titulo t ON te.id = t.id
                    WHERE e.nombreCompleto LIKE :consulta OR g.nombre LIKE :consulta";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':consulta' => "%$consulta%"));
            $this->objetos = $query->fetchall();
        } else {
            $sql = "SELECT e.identificacion, e.nombreCompleto, e.dirResidencia, e.telResidencia, e.telAlternativo, e.correoPrincipal,
                            e.correoSecundario, e.carnet, e.sexo, e.fallecido, e.idGestion, g.nombre as nombre_gestion, t.nombre as titulo, e.avatar
                    FROM egresado e
                    JOIN gestion g ON e.idGestion = g.idGestion
                    LEFT JOIN tituloegresado te ON e.identificacion = te.identificacion
                    LEFT JOIN titulo t ON te.id = t.id
                    ORDER BY e.identificacion";
            $query = $this->acceso->prepare($sql);
            $query->execute();
            $this->objetos = $query->fetchall();
        }
        return $this->objetos;
    }

    //-----------------------------------------------------------
    // Buscar un registro por ID
    //-----------------------------------------------------------
    function Buscar($id) {
        $sql = "SELECT e.identificacion, e.nombreCompleto, e.dirResidencia, e.telResidencia, e.telAlternativo, e.correoPrincipal,
                        e.correoSecundario, e.carnet, e.sexo, e.fallecido, e.idGestion, g.nombre as nombre_gestion, e.avatar
                FROM egresado e
                JOIN gestion g ON e.idGestion = g.idGestion
                WHERE e.identificacion = :id";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id));
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }

    //-------------------------------------------
    // Crear
    //-------------------------------------------
    function Crear($identificacion, $nombreCompleto, $dirResidencia, $telResidencia, $telAlternativo, $correoPrincipal, $correoSecundario, $carnet, $sexo, $fallecido, $idGestion, $avatar) {
        $sql = "SELECT identificacion FROM egresado WHERE identificacion = :identificacion";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':identificacion' => $identificacion));
        $this->objetos = $query->fetchall();
        if (!empty($this->objetos)) {
            return 'noadd'; // Retorna 'noadd' si el egresado ya existe
        } else {
            $sql = "INSERT INTO egresado (identificacion, nombreCompleto, dirResidencia, telResidencia, telAlternativo, correoPrincipal, correoSecundario, carnet, sexo, fallecido, idGestion, avatar)
                    VALUES (:identificacion, :nombreCompleto, :dirResidencia, :telResidencia, :telAlternativo, :correoPrincipal, :correoSecundario, :carnet, :sexo, :fallecido, :idGestion, :avatar)";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':identificacion' => $identificacion, ':nombreCompleto' => $nombreCompleto, ':dirResidencia' => $dirResidencia, ':telResidencia' => $telResidencia, ':telAlternativo' => $telAlternativo, ':correoPrincipal' => $correoPrincipal, ':correoSecundario' => $correoSecundario, ':carnet' => $carnet, ':sexo' => $sexo, ':fallecido' => $fallecido, ':idGestion' => $idGestion, ':avatar' => $avatar));
            return 'add'; // Retorna 'add' si el egresado fue añadido
        }
    }
    

    //-----------------------------------------------------------
    // Editar
    //-----------------------------------------------------------
    function Editar($identificacion, $nombreCompleto, $dirResidencia, $telResidencia, $telAlternativo, $correoPrincipal, $correoSecundario, $carnet, $sexo, $fallecido, $idGestion) {
        $sql = "UPDATE egresado SET nombreCompleto = :nombreCompleto, dirResidencia = :dirResidencia, telResidencia = :telResidencia, telAlternativo = :telAlternativo, correoPrincipal = :correoPrincipal, correoSecundario = :correoSecundario, carnet = :carnet, sexo = :sexo, fallecido = :fallecido, idGestion = :idGestion 
                WHERE identificacion = :identificacion";
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':identificacion' => $identificacion, ':nombreCompleto' => $nombreCompleto, ':dirResidencia' => $dirResidencia, ':telResidencia' => $telResidencia, ':telAlternativo' => $telAlternativo, ':correoPrincipal' => $correoPrincipal, ':correoSecundario' => $correoSecundario, ':carnet' => $carnet, ':sexo' => $sexo, ':fallecido' => $fallecido, ':idGestion' => $idGestion));
    }

    //-----------------------------------------------------------
    // Eliminar
    //-----------------------------------------------------------
    function Eliminar($id) {
        try {
            // Iniciar transacción
            $this->acceso->beginTransaction();

            // Eliminar títulos asociados
            $sql = "DELETE FROM tituloegresado WHERE identificacion = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));

            // Eliminar egresado
            $sql = "DELETE FROM egresado WHERE identificacion = :id";
            $query = $this->acceso->prepare($sql);
            $query->execute(array(':id' => $id));

            // Confirmar transacción
            $this->acceso->commit();

            echo 'eliminado';
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->acceso->rollBack();
            echo 'noeliminado';
        }
    }

    //-----------------------------------------------------------
    // Funcion para cargar un ComboBox
    //-----------------------------------------------------------
    function Seleccionar() {
        $sql = "SELECT * FROM egresado ORDER BY nombreCompleto asc";
        $query = $this->acceso->prepare($sql);
        $query->execute();
        $this->objetos = $query->fetchall();
        return $this->objetos;
    }

    //--------------------------------
    // Cambiar Avatar
    //--------------------------------
    function CambiarLogo($id, $img) {
        // Consulta el nombre de la imagen antes de borrarla
        $sql = 'SELECT avatar from egresado WHERE identificacion = :id';
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id));
        $this->objetos = $query->fetchall();

        // Actualiza la imagen
        $sql = 'UPDATE egresado SET avatar = :img WHERE identificacion = :id';
        $query = $this->acceso->prepare($sql);
        $query->execute(array(':id' => $id, ':img' => $img));

        return $this->objetos;
    }

    // Obtener datos de género
    public function obtenerDatosGenero() {
        $sql = "SELECT sexo, COUNT(*) AS cantidad FROM gestion_egresados.egresado GROUP BY sexo";
        $stmt = $this->acceso->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['sexo']] = $row['cantidad'];
        }
        return $result;
    }

    // Obtener datos de títulos
    public function obtenerDatosTitulo() {
        $sql = "SELECT titulo.nombre, COUNT(*) AS cantidad 
                FROM gestion_egresados.tituloegresado 
                JOIN gestion_egresados.titulo 
                ON tituloegresado.id = titulo.id 
                GROUP BY titulo.nombre";
        $stmt = $this->acceso->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['nombre']] = $row['cantidad'];
        }
        return $result;
    }

    // Obtener datos de gestión
    public function obtenerDatosGestion() {
        $sql = "SELECT gestion.nombre, COUNT(*) AS cantidad 
                FROM gestion_egresados.egresado 
                JOIN gestion_egresados.gestion 
                ON egresado.idGestion = gestion.idGestion 
                GROUP BY gestion.nombre";
        $stmt = $this->acceso->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['nombre']] = $row['cantidad'];
        }
        return $result;
    }

    // Obtener datos de fallecidos
    public function obtenerDatosFallecidos() {
        $sql = "SELECT fallecido, COUNT(*) AS cantidad 
                FROM gestion_egresados.egresado 
                GROUP BY fallecido";
        $stmt = $this->acceso->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $estado = $row['fallecido'] == 'Si' ? 'Fallecido' : 'No Fallecido';
            $result[$estado] = $row['cantidad'];
        }
        return $result;
    }

    // Obtener datos de año de graduación
    public function obtenerDatosGraduacion() {
        $sql = "SELECT YEAR(fechaGrado) AS anio, COUNT(*) AS cantidad 
                FROM gestion_egresados.tituloegresado 
                GROUP BY anio 
                ORDER BY anio";
        $stmt = $this->acceso->query($sql);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[$row['anio']] = $row['cantidad'];
        }
        return $result;
    }
}
?>
