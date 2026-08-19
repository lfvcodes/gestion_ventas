
let saveMode = 'add';

$(document).ready(function () {

    let scrollStartPosition = document.body.scrollTop || document.documentElement.scrollTop;
    $('#tbl-categoria').DataTable({
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
                "targets": [2],
                "orderable": false,
            },
        ],
        "ajax": {
            url: "../controller/",
            type: "POST",
            data: {
                service: 'categoria',
                task: 'getListCategoria'
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
            "sProcessing": "Procesando...",
            "processing": "Procesando...",
        },
    });

    $(window).on('hidden.bs.modal', function () {
        saveMode = 'add';
        $('#mdl-categoria .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar Categoria');
        $('#mdl-categoria #btn-save').removeClass('btn-warning').addClass('btn-primary');
        $('#mdl-categoria #btn-save').text('Guardar');
        $('#frm-cat')[0].reset();
    });

    $('#btn-save').on("click",()=>{
        
        let dataToSend = {
            service:'categoria',
            task: (saveMode === 'edit') ? 'updateCategoria' : 'insertCategoria',
            params: (saveMode === 'edit') ? [
                $('input[name="nom"]').val(),
                $('input[name="id"]').val()
            ] : [
                $('input[name="nom"]').val(),
            ]
        }

        $.ajax({
            type: "POST",
            url: "../controller/",
            data: dataToSend,
            dataType: "json",
            success: function (response) {
                if(response.status === 200){
                    alert("Categoria Guardada Correctamente");
                    $('#tbl-categoria').DataTable().ajax.reload();
                    $('#mdl-categoria').modal("hide");
                }
            }
        });
    });

    $(".menu-inner .menu-item[sc='Categorías']").addClass('active');
});

function editCat($btn) {
    saveMode = 'edit';
    $jdata = base64Decode($($btn).attr('rw'));
    $dt = JSON.parse($jdata);
    
    $('#mdl-categoria form .form-control').each(function () {
        this.value = $dt[this.name];
    });
    
    $('#frm-cat').append('<input type="hidden" value="' + $dt['cod'] + '" name="id">');
    $('#mdl-categoria .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar Categoria');
    $('#mdl-categoria #btn-save').addClass('btn-warning').removeClass('btn-primary');
    $('#mdl-categoria #btn-save').text('Editar');
    $('#mdl-categoria').modal('show');
}

function delCat(btn) {
    if (confirm('¿Está seguro que desea eliminar esta categoría?')) {
        let id_cat = $(btn).attr('dl');
        $.ajax({
            type: "POST",
            url: "../controller/",
            data: {
                service: 'categoria',
                task: 'deleteCategoria',
                params: [id_cat]
            },
            success: function (resp) {
                let answer = JSON.parse(resp);
                if (answer.status === 200) {
                    $('#tbl-categoria').DataTable().ajax.reload();
                }
            }
        });
    } else return false;

}