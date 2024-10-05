<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}
$titulo_pag = 'Egresados';
include_once './layouts/header.php';
include_once './layouts/nav.php';
?>

<!------------------------------------------------------>
<!--   Ventana Modal para CREAR Y EDITAR              -->
<!------------------------------------------------------>
<div class="modal fade" id="crear" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="tit_ven">Crear Egresado</span> </h5>
                <button data-dismiss="modal" arial-label="close" class="close">
                    <span arial-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success text-center" id="add" style='display:none;'>
                    <i class="fa fa-check-circle m-1"> Operación realizada correctamente</i>
                </div>
                <div class="alert alert-danger text-center" id="noadd" style='display:none;'>
                    <i class="fa fa-times-circle m-1"> El egresado ya existe</i>
                </div>
                <form id="form-crear">
                    <div class="form-group">
                        <label for="identificacion">Identificación</label>
                        <input type="text" class="form-control" id="identificacion" name="identificacion" required>
                    </div>
                    <div class="form-group">
                        <label for="nombreCompleto">Nombre Completo</label>
                        <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto" required>
                    </div>
                    <div class="form-group">
                        <label for="dirResidencia">Dir Residencia</label>
                        <textarea class="form-control" id="dirResidencia" name="dirResidencia" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="telResidencia">Tel Residencia</label>
                        <input type="text" class="form-control" id="telResidencia" name="telResidencia" required>
                    </div>
                    <div class="form-group">
                        <label for="telAlternativo">Tel Alternativo</label>
                        <input type="text" class="form-control" id="telAlternativo" name="telAlternativo" required>
                    </div>
                    <div class="form-group">
                        <label for="correoPrincipal">Correo Principal</label>
                        <input type="email" class="form-control" id="correoPrincipal" name="correoPrincipal" required>
                    </div>
                    <div class="form-group">
                        <label for="correoSecundario">Correo Secundario</label>
                        <input type="email" class="form-control" id="correoSecundario" name="correoSecundario" required>
                    </div>
                    <div class="form-group">
                        <label for="carnet">Carnet</label>
                        <input type="text" class="form-control" id="carnet" name="carnet" required>
                    </div>
                    <div class="form-group">
                        <label for="sexo">Sexo</label>
                        <select class="form-control" id="sexo" name="sexo" required>
                            <option value="">Seleccione...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fallecido">Fallecido</label>
                        <select class="form-control" id="fallecido" name="fallecido" required>
                            <option value="">Seleccione...</option>
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="idGestion">ID Gestión</label>
                        <select name="idGestion" id="idGestion" class="form-control select2"
                            style="width: 100%"></select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn bg-gradient-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------->
<!-- FIN Ventana Modal para el crear              -->
<!-------------------------------------------------->

<!-------------------------------------------------->
<!--   Ventana Modal para el cambio de logo     -->
<!-------------------------------------------------->
<div class="modal fade" id="cambiaravatar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cambiar Imagen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img id="avataractual" src="" class="profile-user-img img-fluid img-circle">
                </div>
                <div class="text-center">
                    <b id="nombre_avatar"></b>
                </div>
                <div class="alert alert-success text-center" id="updatelogo" style='display:none;'>
                    <i class="fa fa-check-circle m-1"> Se cambio la imagen</i>
                </div>
                <div class="alert alert-danger text-center" id="noupdatelogo" style='display:none;'>
                    <i class="fa fa-times-circle m-1"> Formato de imagen incorrecto</i>
                </div>
                <form id="form-logo" enctype="multipart/form-data">
                    <div class="input-group mb-3 ml-5">
                        <input type="file" name="photo" class="input-group">
                        <input type="hidden" name="funcion" id="funcion">
                        <input type="hidden" name="id_avatar" id="id_avatar">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn bg-gradient-primary">Cambiar imagen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------->
<!-- FIN Ventana Modal para el cambio de logo   -->
<!-------------------------------------------------->

<!-------------------------------------------------->
<!-------- Modal para agregar Titulo Egresado ------>
<!-------------------------------------------------->
<div class="modal fade" id="modalTituloEgresado" tabindex="-1" role="dialog" aria-labelledby="tituloEgresadoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloEgresadoModalLabel">Agregar Título Egresado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="existe" class="alert alert-warning" role="alert" style="display:none;">
                    <strong>El título ya existe para este egresado.</strong>
                </div>
                <form id="formTituloEgresado">
                    <div class="form-group">
                        <label for="egresado">Egresado</label>
                        <select class="form-control" id="egresado" name="egresado"></select>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <select class="form-control" id="titulo" name="titulo"></select>
                    </div>
                    <div class="form-group">
                        <label for="fechaGrado">Fecha de Graduación</label>
                        <input type="date" class="form-control" id="fechaGrado" name="fechaGrado">
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-------------------------------------------------->
<!-------- Modal para agregar Titulo Egresado ------>
<!-------------------------------------------------->


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo $titulo_pag; ?>
                        <button class="btn-crear btn bg-gradient-primary btn-sm m-2" data-toggle="modal"
                            data-target="#crear">Crear Egresado</button>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="./inicio.php">Inicio</a></li>
                        <li class="breadcrumb-item active"><?php echo $titulo_pag; ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!------------------ Main content ------------------------------>
    <!-- ----------------------------------------------------------->
    <!------------------ Main content ------------------------------>
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Egresados</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped table-hover dataTable dtr-inline">
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include_once 'layouts/footer.php';
?>

<script src="../assets/js/egresado.js"></script>