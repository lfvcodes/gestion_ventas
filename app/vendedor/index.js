let saveMode = 'add';
$(document).ready(function () {

    let scrollStartPosition = document.body.scrollTop || document.documentElement.scrollTop;
    $('#tbl-vendedor').DataTable({
        "processing": true,
        "sProcessing": true,
        "serverSide": true,
        "order": [],
        "ordering": true,
        "responsive": true,
        "fixedHeader": true,
        "scroller": true,
        "sScrollY": ((window.innerHeight - scrollStartPosition) - 340) + "px",
        "pageLength": 25,
        dom: "<'row px-2 px-md-4 pt-2'<'col-md-3' l><'col-md-5 text-center'><'col-md-4'f>>" +
            "<'row'<'col-md-12'trip>>",
        "columnDefs": [
            {
                "targets": [6],
                "orderable": false,
            },
        ],
        "ajax": {
            url: "../controller/",
            type: "POST",
            data: {
                service: 'vendedor',
                task: 'getListVendedor'
            },
            error: function (data) {
            }
        }, "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Todos"]
        ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "<br>(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
        },
    });

    $('#btn-save').on("click", () => {

        let dataToSend = {
            service: 'vendedor',
            task: (saveMode === 'edit') ? 'updateVendedor' : 'insertVendedor',
            params: (saveMode === 'edit') ? [
                $('select[name="nac"]').val(),
                $('input[name="id"]').val(),
                $('input[name="nom"]').val(),
                $('input[name="ape"]').val(),
                $('input[name="dir"]').val(),
                $('input[name="email"]').val(),
                $('input[name="tel"]').val(),
                $('input[name="oldnac"]').val(),
                $('input[name="oldid"]').val()
            ] : [
                $('select[name="nac"]').val(),
                $('input[name="id"]').val(),
                $('input[name="nom"]').val(),
                $('input[name="ape"]').val(),
                $('input[name="dir"]').val(),
                $('input[name="email"]').val(),
                $('input[name="tel"]').val(),
            ]
        }

        $.ajax({
            type: "POST",
            url: "../controller/",
            data: dataToSend,
            dataType: "json",
            success: function (response) {
                if (response.status === 200) {
                    alert("Vendedor Guardado Correctamente");
                    $('#tbl-vendedor').DataTable().ajax.reload();
                    $('#mdl-vendedor').modal("hide");
                }
            }
        });
    });

    $(window).on('hidden.bs.modal', function () {
        saveMode = 'add';
        $('#mdl-vendedor .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar vendedor');
        $('#mdl-vendedor #btn-save').text('Guardar');
        $('#mdl-vendedor #btn-save').removeClass('btn-warning').addClass('btn-primary');
        $('#frm-vendedor')[0].reset();
    });

    $(".menu-inner .menu-item[sc='Vendedores']").addClass('active');

    $('#nac').change(function (e) {
        e.preventDefault();
        if ($(this).val() == 'P') {
            $('#id').removeClass('val-only-numbers');
            $('#id').addClass('val-only-letters');
        } else {
            $('#id').removeClass('val-only-letters');
            $('#id').addClass('val-only-numbers');
        }
    });

});

function editVend($btn) {
    saveMode = 'edit'
    $jdata = base64Decode($($btn).attr('rw'));
    $dt = JSON.parse($jdata);

    $('#mdl-vendedor form .form-control').each(function () {
        this.value = $dt[this.name];
    });

    $('input[name="oldnac"]').val($dt['nac']);
    $('input[name="oldid"]').val($dt['id']);

    $('#mdl-vendedor .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar vendedor');
    $('#mdl-vendedor #btn-save').addClass('btn-warning').removeClass('btn-primary');
    $('#mdl-vendedor #btn-save').text('Editar');
    $('#mdl-vendedor').modal('show');
}

function deleteVend(btn) {

    if (confirm('¿Está seguro que desea eliminar este Vendedor?')) {
        let nac_vend = $(btn).attr('nc');
        let id_vend = $(btn).attr('dl');
        $.ajax({
            type: "POST",
            url: "../controller/",
            data: {
                service: 'vendedor',
                task: 'deleteVendedor',
                params: [nac_vend, id_vend]
            },
            success: function (resp) {
                let answer = JSON.parse(resp);
                if (answer.status === 200) {
                    $('#tbl-vendedor').DataTable().ajax.reload();
                }
            }
        });
    } else return false;

}