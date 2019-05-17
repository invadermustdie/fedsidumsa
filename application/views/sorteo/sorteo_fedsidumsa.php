<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sorteo FEDSIDUMSA</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/'); ?>css/sb-admin-2.min.css" rel="stylesheet">
    <!-- odomter style -->
    <link rel="stylesheet" href="<?= base_url('assets/'); ?>css/odometer/themes/odometer-theme-slot-machine.css"/>
</head>
<style>
    .odometer {
        font-size: 150px;
    }
</style>
<script>
    window.odometerOptions = {
        format: '(ddd)'
    };
</script>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column" style="height: 100%;">
        <!-- Main Content -->

        <div style="background-image: url('assets/img/background/sorteo_fedsidumsa_2019.jpg');
            background-repeat: no-repeat;
            background-size: 100%;
            padding-bottom: 400px;">
            <br> <br><br>
        </div>

        <div id="content" style="background-color: #fff593">
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->
                <br><br>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="odometer">9999999</div>
                        <div class="p-5">
                            <button id="girarTombola" class="btn btn-primary btn-user"
                                    style="width: 200px;">
                                INICIAR
                            </button>
                            <button id="btnAddGanador" class="btn btn-success btn-user"
                                    style="width: 200px;">
                                VER GANADOR
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="table-responsive" style="height: 520px; background-color: white; font-size: large;">
                            <table class="table table-bordered" id="dataTable" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>FACULTAD</th>
                                    <th>NOMBRE</th>
                                    <th>AP PATERNO</th>
                                    <th>AP MATERNO</th>
                                    <th>CI</th>
                                    <th>FECHA REG</th>
                                    <th colspan="2">ACCIONES</th>
                                </tr>
                                </thead>
                                <tbody id="showdata">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
        <!-- Footer -->
        <footer class="sticky-footer" style="background-color: #9fff78;">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <h2>Copyright &copy; FEDSIDUMSA 2019</h2>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Modal -->
<div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form id="miForm">
                    <div class="form-group">
                        <h1>
                            <label type="text" id="txtFacultad" style="font-size: 60px;"></label>
                        </h1>
                    </div>
                    <div class="form-group">
                        <h3>
                            <label for="recipient-name" class="col-form-label">Numero de CI :</label>
                        </h3>
                        <h1>
                            <input id="idDocente" name="idDocente" hidden>
                            <label type="text" id="txtNumCi" style="font-size: 120px;"></label>
                            <input id="numCi" name="numCi" hidden>
                            <label style="font-size: 120px;">-</label>
                            <label type="text" id="txtExpCi" style="font-size: 120px;"></label>
                        </h1>
                    </div>
                    <div class="form-group">
                        <h3>
                            <label for="recipient-name" class="col-form-label">Nombre :</label>
                        </h3>
                        <h1>
                            <label type="text" id="txtNombre" style="font-size: 90px;"></label>
                            <label type="text" id="txtApPat" style="font-size: 90px;"></label>
                            <label type="text" id="txtApMat" style="font-size: 90px;"></label>
                        </h1>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnRegistrar">Registrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/'); ?>vendor/jquery/jquery.min.js"></script>

<script src="<?= base_url('assets/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Odometer -->
<script src="<?= base_url('assets/'); ?>js/odometer/odometer.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/'); ?>js/sb-admin-2.min.js"></script>

<script>
    $(function () {

        showAllGanador();

        // adicionar
        $('#btnAddGanador').click(function () {
            //alert('prueba de añadir ganador');
            $('#miModal').modal('show');

            $('#miForm').attr('action', '<?php echo base_url()?>sorteo/registrarGanador');

        });

        $('#btnRegistrar').click(function () {


            var url = $('#miForm').attr('action');
            var data = $('#miForm').serialize();

            //var ci_docente = $('input[name=numCi]').val();
            var id_docente = $('input[name=idDocente]').val();
            //alert(id_docente);
            $.ajax({
                type: 'ajax',
                method: 'post',
                url: url,
                data: data,
                async: false,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        showAllGanador();
                        $('#miModal').modal('hide');

                    } else {

                        alert('error al insertar en la BD');
                    }

                },
                error: function () {
                    alert("No se pudo agregar datos en la BD");
                }
            });
        });

        // gira la tombola para biscar numeros aleatorios
        $('#girarTombola').click(function () {

            showAllGanador();

            //alert("prueba de tombola");
            $.ajax({
                type: 'ajax',
                url: '<?php echo base_url();?>sorteo/getNumeroAleatorio',
                async: false,
                dataType: 'json',
                success: function (data) {

                    //--------------actualiza el odometro
                    var el = document.querySelector('.odometer');

                    od = new Odometer({
                        el: el,
                        // Any option (other than auto and selector) can be passed in here
                    });

                    var id_docente = '';
                    var num_ci = '';
                    var exp_ci = '';
                    var nombre = '';
                    var apPat = '';
                    var apMat = '';
                    var facultad = '';

                    for (i = 0; i < data.length; i++) {

                        id_docente += data[i].id_docente;
                        num_ci += data[i].nro_ci;
                        exp_ci += data[i].exp_ci;
                        nombre += data[i].nombre;
                        apPat += data[i].ap_pat;
                        apMat += data[i].ap_mat;
                        facultad += data[i].facultad;
                    }

                    //od.update(Math.floor((Math.random() * (9999999-1111111))+1111111));
                    od.update(num_ci);

                    /*
                    // actualizacion de manera automatica
                    setInterval(function(){
                        od.update(Math.floor((Math.random() * (9999999-1111111))+1111111));
                    }, 9000);
                    */

                    console.log(data);
                    //$('#showdata').html(html);

                    // cargar datos al modal
                    $('#idDocente').val(id_docente);
                    $('#txtNumCi').html(num_ci);
                    $('#numCi').val(num_ci);

                    $('#txtExpCi').html(exp_ci);
                    $('#txtNombre').html(nombre);
                    $('#txtApPat').html(apPat);
                    $('#txtApMat').html(apMat);
                    $('#txtFacultad').html(facultad);


                },
                error: function () {

                    alert("No se obtubieron datos desde la Base de datos");

                }
            });

        });

        function showAllGanador() {

            $.ajax({
                type: 'ajax',
                url: '<?php echo base_url();?>sorteo/getAllGanador',
                async: false,
                dataType: 'json',
                success: function (data) {
                    console.log(data);

                    var html = '';
                    var i;
                    for (i = 0; i < data.length; i++) {
                        html += '<tr>' +
                            '<td>' + data[i].facultad + '</td>' +
                            '<td>' + data[i].nombre + '</td>' +
                            '<td>' + data[i].ap_pat + '</td>' +
                            '<td>' + data[i].ap_mat + '</td>' +
                            '<td>' + data[i].nro_ci + '-' + data[i].exp_ci + '</td>' +
                            '<td>' + data[i].fecha_reg + '</td>' +
                            '<td><a href="#" class="btn btn-success btn-circle"><i class="fas fa-check"></i></a></td>' +
                            '<td><a href="#" class="btn btn-danger btn-circle"><i class="fas fa-trash"></i></a></td>' +
                            '</tr>';
                    }
                    $('#showdata').html(html);
                },
                error: function () {
                    alert("No se obtubieron datos desde la Base de datos");
                }
            });
        }
    });
    /*
        setTimeout(function () {
            $('.odometer').html("123456");
        });
    */
</script>

</body>

</html>
