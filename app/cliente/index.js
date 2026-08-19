let saveMode = 'add';

$(document).ready(function () {
    let scrollStartPosition = document.body.scrollTop || document.documentElement.scrollTop;
    $('#tbl-cliente').DataTable({
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
                service: 'cliente',
                task: 'getListCliente'
            },
            error: function (data) {
            }
        },"lengthMenu": [
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
        }
    });

    $(window).on('hidden.bs.modal', function () {
        saveMode = 'add';
        $('#mdl-cliente .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar Cliente');
        $('#mdl-cliente #btn-save').text('Guardar');
        $('#mdl-cliente #btn-save').removeClass('btn-warning').addClass('btn-primary');
        $('#frm-cliente')[0].reset();
    });

    $('#btn-save').on("click",()=>{
        
        let dataToSend = {
            service:'cliente',
            task: (saveMode === 'edit') ? 'updateCliente' : 'insertCliente',
            params: (saveMode === 'edit') ? [
                $('select[name="nac"]').val(),
                $('input[name="id"]').val(),
                $('input[name="nom"]').val(),
                $('input[name="dir"]').val(),
                $('input[name="email"]').val(),
                $('input[name="tel"]').val(),
                $('input[name="oldnac"]').val(),
                $('input[name="oldid"]').val()
            ] : [
                $('select[name="nac"]').val(),
                $('input[name="id"]').val(),
                $('input[name="nom"]').val(),
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
                if(response.status === 200){
                    alert("Cliente Guardado Correctamente");
                    $('#tbl-cliente').DataTable().ajax.reload();
                    $('#mdl-cliente').modal("hide");
                }
            }
        });
    });

    $(".menu-inner .menu-item[sc='Clientes']").addClass('active');
});

function editClient($btn) {
    saveMode = 'edit';
    $jdata = base64Decode($($btn).attr('rw'));
    $dt = JSON.parse($jdata);
    
    $('#mdl-cliente form .form-control').each(function () {
        this.value = $dt[this.name];
    });

    $('input[name="oldnac"]').val($dt['nac']);
    $('input[name="oldid"]').val($dt['id']);

    $('#mdl-cliente .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar Cliente');
    $('#mdl-cliente #btn-save').addClass('btn-warning').removeClass('btn-primary');
    $('#mdl-cliente #btn-save').text('Editar');
    $('#mdl-cliente').modal('show');
}

function deleteClient(btn) {

    if (confirm('¿Está seguro que desea eliminar este cliente?')) {
        let nac_cliente = $(btn).attr('nc');
        let id_cliente = $(btn).attr('dl');
        $.ajax({
            type: "POST",
            url: "../controller/",
            data: {
                service: 'cliente',
                task: 'deleteCliente',
                params: [nac_cliente,id_cliente]
            },
            success: function (resp) {
                let answer = JSON.parse(resp);
                if (answer.status === 200) {
                    $('#tbl-cliente').DataTable().ajax.reload();
                }
            }
        });
    } else return false;

}