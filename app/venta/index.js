
let productosSeleccionados = [];
let saveMode = 'add';

function editVenta($btn) {
    saveMode = 'edit';
    $jdata = base64Decode($($btn).attr('rw'));
    $dt = JSON.parse($jdata);

    $('#optcli').append('<option selected value="' + $dt['idcli'] + '">' + $dt['nomcli'] + '</option>');
    $('.cli').select2('destroy');
    $('.cli').val($dt['idcli']).trigger('change');
    $('.cli').select2({
        theme: 'bootstrap-5',
        dropdownParent: $("#mdl-venta"),
        ajax: {
            url: "../controller/",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    service:'cliente',
                    task: "getListOptionCli",
                    params: [params.term] // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                }
            },
        },
        language: {
            searching: function () {
                return "Buscando...";
            },
            noResults: function () {
                let $bt = `<li><button style="width: 100%" type="button" class="btn btn-primary" onClick="newProv()"><i class="fa fa-plus"></i> Agregar Nuevo</button></li>`;
                $('.select2-results .select2-results__options').append($bt);
                return "No se Encontraron Resultados";
            },
        },
    });

    $('#optvend').append('<option selected value="' + $dt['idvend'] + '">' + $dt['nomvend'] + '</option>');
    $('.vend').select2('destroy');
    $('.vend').val($dt['idvend']).trigger('change');
    $('.vend').select2({
        theme: 'bootstrap-5',
        dropdownParent: $("#mdl-venta"),
        ajax: {
            url: "../controller/",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    service:'vendedor',
                    task: "getListOptionVend",
                    params: [params.term] // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                }
            },
        },
        language: {
            searching: function () {
                return "Buscando...";
            },
            noResults: function () {
                let $bt = `<li><button style="width: 100%" type="button" class="btn btn-primary" onClick="newProv()"><i class="fa fa-plus"></i> Agregar Nuevo</button></li>`;
                $('.select2-results .select2-results__options').append($bt);
                return "No se Encontraron Resultados";
            },
        },
    });

    $('#freg').val($dt['fechab']).trigger('change');
    $('.desc').val($dt['descr']);

    let $tbl = null;
    let rows = 0;

    $dt2 = response('../controller/', {
        service:'venta',
        task: 'getDetailTable',
        id: $dt['cod']
    });
    $tbl = JSON.parse($dt2);
    rows = $tbl.length;
    $('#tbl-item tbody').html("");
    stotald = 0;
    totald = 0;
    let t = sessionStorage.getItem('tipo');
    for (let i = 0; i < rows; i++) {
        let idtr = $('#tbl-item tbody tr').length + 1;
        let titem = ($tbl[i]['cant'] * $tbl[i]['monto']);
        titem = titem.toFixed(2);
        let tprecio = (t == 1) ? $tbl[i]['p_venta'] : $tbl[i]['p_venta' + t];
        $('#tbl-item tbody').append(`
            <tr>
              <td>
                <select class="form-select form-control prod" name="prod[]">
                  <option selected value="` + $tbl[i]['cod_producto'] + `">` + $tbl[i]['nom_producto'] + `</option>
                </select>
              </td>
              <td><input required onkeyup="calcm(this)" onchange="calcm(this)" name="cant[]" value="` + $tbl[i]['cant'] + `" class="form-control cant" min="1" step="1" type="number"></td>
              <td><input required readonly name="monto[]" class="form-control monto" value="` + $tbl[i]['monto'] + `" min="0.01" step="0.01" type="number"></td>
              <td><input readonly class="form-control titem" type="number" value="` + titem + `"></td>
              <td><button onclick="remove(this);" type="button" class="btn btn-sm btn-danger">-</button></td>
            </tr>
          `);
        stotald += ($tbl[i]['cant'] * $tbl[i]['monto']);
        productosSeleccionados.push($tbl[i]['cod_producto']);
    }

    $('#stotald').val(stotald.toLocaleString('es-ES', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true
    }));
    stotald = (stotald * 1.16);
    $('#totald').val(stotald.toLocaleString('es-ES', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true
    }));
    initSelect();

    $('#mdl-venta .modal-title').html('<i class="mb-2 bx bx-edit"></i>Editar venta');
    $('#mdl-venta #btn-save').text('Editar');
    $('#frm-venta input[name="id"]').remove();
    $('#frm-venta').append('<input type="hidden" name="id">');
    $('input[name="id"]').val($dt['cod']);
    $('#mdl-venta #btn-save').addClass('btn-warning').removeClass('btn-primary');
    $('#mdl-venta').modal('show');
}

function deleteVenta(btn) {

    if (confirm('¿Está seguro que desea eliminar esta Venta?')) {
        let id_venta = $(btn).attr('dl');
        $.ajax({
            type: "POST",
            url: "../controller/",
            data: {
                service: 'venta',
                task: 'deleteVenta',
                id: id_venta
            },
            success: function (resp) {
                let answer = JSON.parse(resp);
                if (answer.status === 200) {
                    $('#tbl-venta').DataTable().ajax.reload();
                }
            }
        });
    } else return false;
}

function newCrud(url) {
    popupWindow = window.open(url, 'popUpWindow', 'height=500,width=500,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no, status=yes');
}

function initSelect() {
    $('.prod').select2({
        theme: 'bootstrap-5',
        dropdownParent: $("#mdl-venta"),
        ajax: {
            url: "../controller/",
            type: "POST",
            dataType: 'json',
            delay: 250,
            data: function (lk) {
                return {
                    service: 'producto',
                    task: "getListOptionProduct",
                    params: lk.term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                }
            },
            cache: true,
        },
        language: {
            searching: function () {
                return "Buscando...";
            },
            noResults: function () {
                let $bt = addNewButton('../producto/');
                $('.select2-results .select2-results__options').append($bt);
                return "No se Encontraron Resultados";
            },
        },
    });

    $('.prod').change(function (e) {
        let nuevoProducto = $(this).val();
        let filaActual = $(this).closest('tr');
        let filaIndex = filaActual.index();
        let productosSeleccionadosTemp = [...productosSeleccionados]; // Crear una copia temporal del array de productos seleccionados

        // Eliminar el producto previamente seleccionado de la lista de productos seleccionados
        let productoAnterior = filaActual.data('producto-seleccionado');
        if (productoAnterior) {
            let indexProductoAnterior = productosSeleccionadosTemp.indexOf(productoAnterior);
            if (indexProductoAnterior > -1) {
                productosSeleccionadosTemp.splice(indexProductoAnterior, 1);
            }
        }

        // Validar si el producto ya ha sido seleccionado en otra fila
        if (productosSeleccionadosTemp.includes(nuevoProducto)) {
            alert('¡El producto seleccionado ya ha sido agregado en otra fila!');
            $(this).val(productoAnterior).trigger('change'); // Revertir la selección al producto anterior
            return;
        }

        // Actualizar el producto seleccionado en la fila actual
        filaActual.data('producto-seleccionado', nuevoProducto);

        // Actualizar el array de productos seleccionados después de la validación
        if (productoAnterior) {
            let indexProductoAnterior = productosSeleccionados.indexOf(productoAnterior);
            if (indexProductoAnterior > -1) {
                productosSeleccionados.splice(indexProductoAnterior, 1);
            }
        }
        if (nuevoProducto) {
            let vl = $(this).val();
            $rs = response('../controller/', {
                service: 'producto',
                task: "getProductPrices",
                id: vl
            });
            $rs = JSON.parse($rs)[0];
            $(this).parent('td').parent('tr').find('td .monto').val($rs['pcosto']);
            productosSeleccionados.push(nuevoProducto);
        }
        $(this).parent('td').parent('tr').find('td .cant').val(null).trigger('change');
    });

     $('#optcli').select2({
        theme: 'bootstrap-5',
        dropdownParent: $("#mdl-venta"),
        ajax: {
            url: "../controller/",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (lk) {
                return {
                    service: 'cliente',
                    task: "getListOptionCli",
                    params: lk.term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                }
            },
            cache: true
        },
        language: {
            searching: function () {
                return "Buscando...";
            },
            noResults: function () {
                let $bt = addNewButton('../cliente/');
                $('.select2-results .select2-results__options').append($bt);
                return "No se Encontraron Resultados";
            },
        },
    });

    $('#optvend').select2({
        theme: 'bootstrap-5',
        dropdownParent: $("#mdl-venta"),
        ajax: {
            url: "../controller/",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (lk) {
                return {
                    service: 'vendedor',
                    task: "getOptionSelectVend",
                    params: lk.term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                }
            },
            cache: true
        },
        language: {
            searching: function () {
                return "Buscando...";
            },
            noResults: function () {
                let $bt = addNewButton('../vendedor/');
                $('.select2-results .select2-results__options').append($bt);
                return "No se Encontraron Resultados";
            },
        },
    });
}

function add() {
    $('#tbl-item tbody').append(`
          <tr>
            <td>
              <select class="form-select form-control prod" name="prod[]"></select>
            </td>
            <td><input required onkeyup="calcm(this)" onchange="calcm(this)" name="cant[]" class="form-control cant" min="1" step="1" type="number"></td>
            <td><input required readonly name="monto[]" class="form-control monto" min="0.01" step="0.01" type="number"></td>
            <td><input readonly class="form-control titem" type="number"></td>
            <td><button onclick="remove(this);" type="button" class="btn btn-sm btn-danger">-</button></td>
          </tr>
          `);
    initSelect();
}

function remove(btn) {
    $(btn).parent('td').parent('tr').remove();
    if ($('#tbl-item tbody tr').length < 1) {
        $('#stotald,#totald').val("");
    }
    calcm($('#tbl-item tbody tr:last td:eq(1) .form-control'));
}

function calcm(me) {

    let stotald = 0.0;
    let totald = 0.0;
    let precio = $(me).parent('td').parent('tr').find('td .monto').val();

    let titem = $(me).parent('td').parent('tr').find('td .titem');
    let item = (precio * Number($(me).val()));
    titem.val(item.toFixed(2));

    $('.titem').each(function () {
        stotald += Number($(this).val());
    });

    totald = (stotald * 1.16);
    $('#stotald').val(stotald.toFixed(2));
    $('#totald').val(totald.toLocaleString('es-ES', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true
    }));
}

function addNewButton($url){
    return `<li>
    <button style="width: 100%" type="button" class="btn btn-primary" 
    onClick="newCrud('${$url}')"><i class="fa fa-plus"></i> Agregar Nuevo
    </button>
    </li>`;
}

$(document).ready(function () {

    $('#btn-save').on("click", () => {
        
        stotald = 0;
        $('.titem').each(function () {
            stotald += Number($(this).val());
        });

        if (stotald <= 0) {
            alert('El monto total de la venta debe ser mayor a cero');
            return false;
        }

        let dataToSend = {
            service: 'venta',
            task: (saveMode === 'edit') ? 'updateVenta' : 'insertVenta',
            params: {
                freg: $('#freg').val(),
                optcli: $('#optcli').val(),
                optvend: $('#optvend').val(),
                desc: $('.desc').val(),
                prod: $('.prod').map(function () { return $(this).val(); }).get(),
                cant: $('.cant').map(function () { return $(this).val(); }).get(),
                monto: $('.monto').map(function () { return $(this).val(); }).get()
            }
        }

        if (saveMode === 'edit') {
            dataToSend.params.id = $('input[name="id"]').val();
        }

        $.ajax({
            type: "POST",
            url: "../controller/",
            data: dataToSend,
            dataType: "json",
            success: function (response) {
                if (response.status === 200) {
                    alert("Venta Guardada Correctamente");
                    $('#tbl-venta').DataTable().ajax.reload();
                    $('#mdl-venta').modal("hide");
                }
            }
        });
    });

    let scrollStartPosition = document.body.scrollTop || document.documentElement.scrollTop;

    initSelect();

    $('#tbl-venta').DataTable({
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
                service: 'venta',
                task: 'getListVenta'
            },
            error: function (data) {
            }
        },
        "Sort": true,
        "aaSorting": [],
        "columnDefs": [{
            "targets": [8],
            "orderable": false,
        },
        {
            "targets": [1, 3, 5],
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
        $('#stotal').prop('hidden', true);
        $('#frm-venta')[0].reset();
        $('#frm-venta input[name="id"]').remove();
        $('#optcli,#optvend').select2('destroy');
        $('#optcli').val(null).trigger('change');
        $('#optvend').val(null).trigger('change');

        $('#mdl-venta .modal-title').html('<i class="mb-2 bx bx-folder-plus"></i>Registar venta');
        $('#mdl-venta #btn-save').text('Guardar');
        $('#mdl-venta #btn-save').removeClass('btn-warning').addClass('btn-primary');

        $('#tbl-item tbody').html("");
        productosSeleccionados = [];
        initSelect();
    });

    $(".menu-inner .menu-item[sc='Ventas']").addClass('active');

});