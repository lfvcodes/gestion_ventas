
let saveMode = 'add';

$(document).ready(function () {

    let inputt = $('form input, form select, form checkbox');
    $(inputt).on('invalid', (e) => {
        $('#btn-save').prop('disabled', true);
    });

    $('.form-control,input,select').change(function (e) {
        e.preventDefault();
        $('#btn-save').prop('disabled', false);
    });

    let scrollStartPosition = document.body.scrollTop || document.documentElement.scrollTop;

    $('#tbl-producto').DataTable({
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
        dom: "<'row px-2 px-md-4 pt-2'<'col-md-3'l><'col-md-5 text-center'><'col-md-4'f>>" +
            "<'row'<'col-md-12'tr>>" +
            "<'row px-2 px-md-4 py-3'<'col-md-5'i><'col-md-7'p>>",
        "ajax": {
            url: "../controller/",
            type: "POST",
            data: {
                service: 'producto',
                task: 'getListProduct'
            },
            error: function (data) {
            }
        },
        "Sort": true,
        "aaSorting": [],
        "columnDefs": [{
            "targets": [5],
            "orderable": false,
        },
        {
            "targets": [2],
            "visible": false,
        },
        ],
        "lengthMenu": [
            [5, 10, 25, 50, 100, -1],
            [5, 10, 25, 50, 100, "Todos"]
        ],
        "language": {
            "buttons": {
                "print": "Imprimir",
            },
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
            "sProcessing": "Procesando...",
            "processing": "Procesando...",
        },
    });

    $(window).on('hidden.bs.modal', function () {
        saveMode = 'add';
        $('input[name="stock"]').parents('.col').prop('hidden', false);
        $('#mdl-product .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar Nuevo Producto');
        $('#mdl-product #btn-save').text('Guardar');
        $('input[name="id"]').remove();
        $('#mdl-product #btn-save').removeClass('btn-warning').addClass('btn-primary');
        $('#frm-product')[0].reset();
    });

    $('#btn-save').on("click",()=>{
        
        let dataToSend = {
            service:'producto',
            task: (saveMode === 'edit') ? 'updateProduct' : 'insertProduct',
            params: (saveMode === 'edit') ? [
                $('input[name="nom_producto"]').val(),
                $('select[name="cod_categoria"]').val(),
                $('input[name="p_base"]').val(),
                $('input[name="id"]').val()
            ] : [
                $('input[name="nom_producto"]').val(),
                $('select[name="cod_categoria"]').val(),
                $('input[name="p_base"]').val()
            ]
        }

        $.ajax({
            type: "POST",
            url: "../controller/",
            data: dataToSend,
            dataType: "json",
            success: function (response) {
                if(response.status === 200){
                    alert("Producto Guardado Correctamente");
                    $('#tbl-producto').DataTable().ajax.reload();
                    $('#mdl-product').modal("hide");
                }
            }
        });
    });

    $(".menu-inner .menu-item[sc='Productos']").addClass('active');

});

function editProd($json) {
    saveMode = 'edit';
    $dt = JSON.parse(base64Decode($($json).attr('rw')));

    $('#mdl-product form .form-control').each(function (i) {
        this.value = $dt[this.id];
    });

    $('#mdl-product .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar Producto(s)');
    $('#mdl-product #btn-save').text('Editar');
    $('#frm-product').append(`<input type="hidden" name="id" value=""/>`);
    $('input[name="id"]').val($dt['cod_producto']);
    $('#mdl-product #btn-save').addClass('btn-warning').removeClass('btn-primary');
    $('#mdl-product').modal('show');
}

function delProd(btn) {
    if (confirm('¿Está seguro que desea eliminar este Producto?')) {
        let id_prod = $(btn).attr('dl');
        $.ajax({
            type: "POST",
            url: "../controller/",
            data: {
                service: 'producto',
                task: 'deleteProduct',
                params: [id_prod]
            },
            success: function (resp) {
                let answer = JSON.parse(resp);
                if (answer.status === 200) {
                    $('#tbl-producto').DataTable().ajax.reload();
                }
            }
        });
    } else return false;

}