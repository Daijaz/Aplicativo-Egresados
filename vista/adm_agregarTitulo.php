<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: ../index.php");
  exit();
}
$_pag = 'Agregar Titulos';
include_once 'layouts/header.php';
include_once 'layouts/nav.php';
?>

<!------------------------------------------------------>
<!--   Ventana Modal para CREAR Y EDITAR              -->
<!------------------------------------------------------>
<div class="modal fade" id="crear" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><span id="tit_ven">Crear Titulo</span> </h5>
        <button data-dismiss="modal" arial-label="close" class="close">
          <span arial-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-crear">
          <div class="form-group">
            <label for="id_">ID</label>
            <input type="number" id="id" class="form-control" placeholder="Ingrese ID" required>
          </div>
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" class="form-control" placeholder="Ingrese el nombre del titulo" required>
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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1><?php echo $_pag; ?>
            <button class="btn-crear btn bg-gradient-primary btn-sm m-2" data-toggle="modal"
              data-target="#crear">Insertar Titulo</button>
          </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="./inicio.php">Inicio</a></li>
            <li class="breadcrumb-item active"><?php echo $_pag; ?></li>
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
            <h3 class="card-title">Titulos</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="tabla" class="table table-bordered table-striped table-hover dataTable dtr-inline" role="grid">
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
<script src="../assets/js/agregarTitulo.js"></script>
<!--/
<script src="../assets/js/nav.js"></script>
-- >