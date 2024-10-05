$(document).ready(function () {
    var funcion;
    var edit = false;

    // Cargar las opciones de gestión, egresados y títulos al cargar la página
    cargar_gestiones();
    cargar_egresados();
    cargar_titulos();

    // Inicializar la tabla DataTable con configuraciones personalizadas
    var tabla = $('#tabla').DataTable({
        dom: 'Bfrtip',

        "lengthMenu": [[5, 10, 20, 25, 50, -1], [5, 10, 20, 25, 50, "Todos"]],
        "iDisplayLength": 10,
        "responsive": true,
        "autoWidth": false,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando..."
        },
        "ajax": {
            "url": "../controlador/EgresadoController.php",
            "method": 'POST',
            "data": { funcion: 'listar' },
            "dataSrc": ""
        },
        "columns": [
            { "data": "identificacion", "title": "Identificación" },
            { "data": "nombreCompleto", "title": "Nombre Completo" },
            { "data": "dirResidencia", "title": "Dir Residencia" },
            { "data": "telResidencia", "title": "Tel Residencia" },
            { "data": "correoPrincipal", "title": "Correo Principal" },
            { "data": "carnet", "title": "Carnet" },
            { "data": "sexo", "title": "Sexo" },
            { "data": "fallecido", "title": "Fallecido" },
            { "data": "nombreGestion", "title": "Gestión" },
            { "data": "titulo", "title": "Título" },
            {
                "defaultContent": "<div class='btn-group'><button class='avatar btn bg-teal btn-sm' title='Cambiar imagen' data-toggle='modal' data-target='#cambiaravatar'><i class='fas fa-image'></i></button><button class='editar btn btn-sm btn-primary' title='Editar' data-toggle='modal' data-target='#crear'><i class='fas fa-pencil-alt'></i></button><button class='titulo btn btn-sm btn-success' title='Agregar Título' data-toggle='modal' data-target='#modalTituloEgresado'><i class='fas fa-graduation-cap'></i></button><button class='eliminar btn btn-sm btn-danger' title='Eliminar'><i class='fas fa-trash'></i></button></div>",
                "title": "Acciones"
            }
        ],
        "columnDefs": [
            {
                "className": "text-center",
                "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                "visible": true,
                "searchable": true
            }
        ],
        buttons: [
            {
                extend: 'copy',
                text: 'Copiar',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csv',
                text: 'CSV',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excel',
                text: 'Excel',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                title: 'Lista de Egresados',
                orientation: 'landscape',
                pageSize: 'A2',
                exportOptions: {
                    columns: ':visible'
                },
                customize: function (doc) {
                    var colCount = [];
                    $('#tabla').find('thead th').each(function() {
                        colCount.push('*'); // Utiliza '*' para auto-ajustar el ancho de las columnas
                    });
                    doc.content[1].table.widths = colCount;
                }
            },
            {
                extend: 'print',
                text: 'Imprimir',
                exportOptions: {
                    columns: ':visible'
                }
            },
            'colvis'
        ]
    });

    tabla.buttons().container().appendTo($('.col-md-6:eq(0)', tabla.table().container()));

    // Evento para cambiar el avatar del egresado
    $(document).on('click', '.avatar', function () {
        if (tabla.row(this).child.isShown())
            var data = tabla.row(this).data();
        else
            var data = tabla.row($(this).parents("tr")).data();

        const id = data.identificacion;
        buscar(id);
        funcion = 'cambiar_logo';
        $('#funcion').val(funcion);
    });

    // Enviar el formulario para cambiar el logo del egresado
    $('#form-logo').submit(e => {
        let formData = new FormData($('#form-logo')[0]);
        $.ajax({
            url: '../controlador/EgresadoController.php',
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false
        }).done(function (response) {
            const json = JSON.parse(response);
            if (json.alert == 'editalogo') {
                $('#avataractual').attr('src', json.ruta);
                $('#updatelogo').hide('slow');
                $('#updatelogo').show(1000);
                $('#updatelogo').hide(2000);
                buscar_todos();
            } else {
                $('#noupdatelogo').hide('slow');
                $('#noupdatelogo').show(1000);
                $('#noupdatelogo').hide(2000);
            }
            // Cerrar el modal después de procesar la respuesta
            $('#cambiaravatar').modal('hide');
        });
        e.preventDefault();
    });

    // Recargar la página cuando el modal se cierra
    $('#cambiaravatar').on('hidden.bs.modal', function () {
        location.reload();
    });

    // Evento para mostrar el formulario de creación de egresado
    $(document).on('click', '.btn-crear', (e) => {
        $('#form-crear').trigger('reset');
        $('#tit_ven').html('Nuevo egresado');
        $('#identificacion').prop('disabled', false); // Asegúrate de que el campo esté habilitado al crear
        $('#identificacion').parent().show(); // Muestra el campo de identificación
        edit = false;
    });

    // Evento para mostrar el formulario de edición de egresado
    $(document).on('click', '.editar', function () {
        edit = true;
        $('#tit_ven').html('Editar egresado');
        if (tabla.row(this).child.isShown()) {
            var data = tabla.row(this).data();
        } else {
            var data = tabla.row($(this).parents("tr")).data();
        }
        const id = data.identificacion;
        buscar(id);
        $('#identificacion').prop('disabled', true); // Deshabilita el campo de identificación al editar
        $('#identificacion').parent().hide(); // Oculta el campo de identificación
    });

    // Función para buscar un egresado por identificación
    function buscar(dato) {
        funcion = 'buscar';
        $.post('../controlador/EgresadoController.php', { dato, funcion }, (response) => {
            const respuesta = JSON.parse(response);
            $('#identificacion').val(respuesta.identificacion);
            $('#nombreCompleto').val(respuesta.nombreCompleto);
            $('#dirResidencia').val(respuesta.dirResidencia);
            $('#telResidencia').val(respuesta.telResidencia);
            $('#telAlternativo').val(respuesta.telAlternativo);
            $('#correoPrincipal').val(respuesta.correoPrincipal);
            $('#correoSecundario').val(respuesta.correoSecundario);
            $('#carnet').val(respuesta.carnet);
            $('#sexo').val(respuesta.sexo);
            $('#fallecido').val(respuesta.fallecido);
            $('#idGestion').val(respuesta.idGestion).trigger('change');

            $('#nombre_avatar').html(respuesta.nombreCompleto);
            $('#id_avatar').val(respuesta.identificacion);
            $('#avataractual').attr('src', '../assets/img/prod/' + respuesta.avatar);
        });
    }

    // Enviar el formulario para crear o editar un egresado
    $('#form-crear').submit(e => {
        let identificacion = $('#identificacion').val();
        let nombreCompleto = $('#nombreCompleto').val();
        let dirResidencia = $('#dirResidencia').val();
        let telResidencia = $('#telResidencia').val();
        let telAlternativo = $('#telAlternativo').val();
        let correoPrincipal = $('#correoPrincipal').val();
        let correoSecundario = $('#correoSecundario').val();
        let carnet = $('#carnet').val();
        let sexo = $('#sexo').val();
        let fallecido = $('#fallecido').val();
        let idGestion = $('#idGestion').val();
        let avatar = 'default.png';

        if (edit == true)
            funcion = 'editar';
        else
            funcion = 'crear';

        $.post('../controlador/EgresadoController.php', { identificacion, nombreCompleto, dirResidencia, telResidencia, telAlternativo, correoPrincipal, correoSecundario, carnet, sexo, fallecido, idGestion, avatar, funcion }, (response) => {
            response = response.trim();
            if (response == 'add') {
                Swal.fire({
                    title: 'Egresado creado!',
                    text: 'El egresado ha sido creado exitosamente.',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else if (response == 'noadd') {
                Swal.fire({
                    title: 'Error!',
                    text: 'El egresado ya existe.',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else if (response == 'update') {
                Swal.fire({
                    title: 'Egresado actualizado!',
                    text: 'El egresado ha sido actualizado exitosamente.',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'No se pudo realizar la operación.',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            }
            $('#crear').modal('hide');
            tabla.ajax.reload(null, false);
        });
        e.preventDefault();
    });

    // Evento para agregar un título a un egresado
    $(document).on('click', '.titulo', function () {
        let data;
        if (tabla.row(this).child.isShown())
            data = tabla.row(this).data();
        else
            data = tabla.row($(this).parents("tr")).data();

        $('#egresado').val(data.identificacion);

        // Cargar títulos disponibles
        $.post('../controlador/AgregarTituloEgresadoController.php', { funcion: 'seleccionarTitulos' }, (response) => {
            let titulos = JSON.parse(response);
            $('#titulo').empty();
            titulos.forEach(titulo => {
                $('#titulo').append(`<option value="${titulo.id}">${titulo.nombre}</option>`);
            });
        });

        // Cargar título y fecha de graduación del egresado
        $.post('../controlador/AgregarTituloEgresadoController.php', { funcion: 'obtenerTituloEgresado', identificacion: data.identificacion }, (response) => {
            let tituloEgresado = JSON.parse(response);
            if (tituloEgresado) {
                $('#titulo').val(tituloEgresado.id);

                // Convertir la fecha de dd/mm/yyyy a yyyy-mm-dd
                let fechaArray = tituloEgresado.fechagrado.split('/');
                let fechaGrado = `${fechaArray[2]}-${fechaArray[1]}-${fechaArray[0]}`;

                $('#fechaGrado').val(fechaGrado);
            } else {
                $('#titulo').val('');
                $('#fechaGrado').val('');
            }
        });

        $('#modalTituloEgresado').modal('show');
    });

// Enviar el formulario para agregar un título a un egresado
$('#formTituloEgresado').submit(e => {
    let egresado = $('#egresado').val();
    let titulo = $('#titulo').val();
    let fechaGrado = $('#fechaGrado').val();

    funcion = 'agregarTitulo';
    $.post('../controlador/AgregarTituloEgresadoController.php', { egresado, titulo, fechaGrado, funcion }, (response) => {
        if (response.trim() === 'add') {
            Swal.fire({
                icon: 'success',
                title: 'Título agregado',
                text: 'El título se ha agregado correctamente',
            });
            $('#modalTituloEgresado').modal('hide');
            tabla.ajax.reload(null, false);
        } else if (response.trim() === 'existe') {
            Swal.fire({
                icon: 'error',
                title: 'Título ya asignado',
                text: 'Este título ya ha sido asignado a este egresado',
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo agregar el título',
            });
        }
    });
    e.preventDefault();
});




    // Evento para eliminar un egresado
    $(document).on('click', '.eliminar', function () {
        if (tabla.row(this).child.isShown()) {
            var data = tabla.row(this).data();
        } else {
            var data = tabla.row($(this).parents("tr")).data();
        }
        const id = data.identificacion;
        const nombre = data.nombreCompleto;
        buscar(id);
        funcion = 'eliminar';

        Swal.fire({
            title: 'Desea eliminar ' + nombre + '?',
            text: "Esto no se podrá revertir!",
            icon: 'warning',
            showCancelButton: true,
            reverseButtons: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then((result) => {
            if (result.value) {
                $.post('../controlador/EgresadoController.php', { id, funcion }, (response) => {
                    response = response.trim();
                    if (response == 'eliminado') {
                        Swal.fire(
                            'Eliminado!',
                            nombre + ' fue eliminado.',
                            'success'
                        );
                    }
                    else {
                        Swal.fire(
                            'No se pudo eliminar!',
                            nombre + ' está utilizado',
                            'error'
                        );
                    }
                    tabla.ajax.reload(null, false);
                });
            }
        });
    });

    // Cargar las opciones de egresados en el formulario de observaciones
    function cargar_egresados() {
        funcion = 'obtener_egresados';
        $.post('../controlador/ObservacionController.php', { funcion }, (response) => {
            const registros = JSON.parse(response);
            let template = '';
            registros.forEach(registro => {
                template += `<option value="${registro.identificacion}">${registro.nombreCompleto}</option>`;
            });
            $('#egresado').html(template);
        });
    }

    // Cargar las opciones de títulos en el formulario de agregar título
    function cargar_titulos() {
        funcion = 'seleccionar';
        $.post('../controlador/AgregarTituloController.php', { funcion }, (response) => {
            const registros = JSON.parse(response);
            let template = '';
            registros.forEach(registro => {
                template += `<option value="${registro.id}">${registro.nombre}</option>`;
            });
            $('#titulo').html(template);
        });
    }

    // Cargar las opciones de gestión en el formulario de crear/editar egresado
    function cargar_gestiones() {
        funcion = 'seleccionar';
        $.post('../controlador/GestionController.php', { funcion }, (response) => {
            const registros = JSON.parse(response);
            let template = '';
            registros.forEach(registro => {
                template += `<option value="${registro.id}">${registro.nombre}</option>`;
            });
            $('#idGestion').html(template);
        });
    }
});
