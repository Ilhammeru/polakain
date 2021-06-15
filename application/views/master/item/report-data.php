<!doctype html>

<script type="text/javascript">
    var titleExport = 'Report Barang';
    var columnExport = [':visible:not(.not-export-col)'];
    var p_item_add = "<?php echo $this->session->userdata('p_item_add'); ?>";

    $(document).ready(function() {

        $(document).on('click', '#btn-confirm-delete', function() {
            var id = $(this).attr('key');

            Swal.fire({
                title: 'Hapus data?',
                //text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya!'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        url: "<?= site_url('item/delete_item'); ?>",
                        type: "post",
                        data: {
                            id: id
                        },
                        success: function(response) {

                            if (response == 'success') {

                                $('#modal-confirm-delete').modal('hide');

                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Data berhasil dihapus',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                setTimeout(function() {
                                    $('#tableItem').DataTable().ajax.reload();
                                }, 2000);

                            } else if (response == 'error') {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Ada kesalahan!',
                                });

                            }

                        }

                    });

                }

            });
        });

        $('#tableItem thead tr').clone(true).appendTo('#tableItem thead');

        $('#tableItem thead tr:eq(1) th').each(function(i) {

            var title = $(this).text();

            switch (i) {

                case 2:

                    $(this).html('<?php echo $filterCategory; ?>');

                    break;

                case 3:

                    $(this).html('');

                    break;

                default:

                    $(this).html('<input type="text" class="form-control" placeholder="Cari ' + title + '" />');

                    break;

            }

            $('input', this).on('change', function() {

                tableItem
                    .column(i)
                    .search(this.value)
                    .draw();

            });

            $('select', this).on('keyup change', function() {

                var result = [];
                var options = this && this.options;
                var opt;

                for (var x = 0, xLen = options.length; x < xLen; x++) {

                    opt = options[x];

                    if (opt.selected) {

                        result.push(opt.value || opt.text);

                    }

                }

                tableItem
                    .column(i)
                    .search(result)
                    .draw();

            });

        });
        // End of clone thead

        var tableItem = $('#tableItem').DataTable({

            // Data
            ajax: {
                url: "<?= site_url('item/server_side_data'); ?>",
                type: "POST"
            },
            processing: true,
            serverSide: true,

            // Ordering
            order: [0, 'asc'],
            colReorder: true,
            orderCellsTop: true,

            // // Header
            // fixedHeader: {
            //     headerOffset: 55
            // },

            // // Select
            // select: {
            //     style: 'multi'
            // },

            // Length
            lengthChange: false,
            lengthMenu: [
                [10, 25, 50, 100],
                ['10 rows', '25 rows', '50 rows', '100 rows']
            ],

            // Column
            columnDefs: [{
                targets: [3],
                orderable: false,
                className: "not-export-col"
            }],
            columns: [{
                    "width": "40%"
                },
                {
                    "width": "20%"
                },
                {
                    "width": "25%"
                },
                {
                    "width": "15%"
                }
            ],

            // Buttons          
            // buttons: [ 
            //             'pageLength',
            //             'colvis', 
            //             {
            //                 extend: 'copy',
            //                 exportOptions: {
            //                     columns: columnExport
            //                 }
            //             },
            //             {
            //                 extend: 'excel',
            //                 title: titleExport,
            //                 exportOptions: {
            //                     columns: columnExport
            //                 }
            //             },
            //             {
            //                 extend: 'pdf',
            //                 title: titleExport,
            //                 exportOptions: {
            //                     columns: columnExport
            //                 }
            //             },
            //             {
            //                 extend: 'print',
            //                 title: titleExport,
            //                 exportOptions: {
            //                     columns: columnExport
            //                 }
            //             },
            //             {
            //                 text: 'Tambah Barang',
            //                 className: 'btn-success btn-item-add',
            //                 action: function () {
            //                     location.href = "<?= site_url('item/form_new_item'); ?>"       
            //                 }
            //             } 
            //         ],
            // dom: 'Bfrtip',

            // // Responsive
            // responsive: {

            //     details: {

            //         display: $.fn.dataTable.Responsive.display.modal( {

            //             header: function ( row ) {

            //                 var data = row.data();
            //                 return 'Details for ' + data[0];

            //             }

            //         }),

            //         renderer: $.fn.dataTable.Responsive.renderer.tableAll({
            //             tableClass: 'table'
            //         })
            //     }
            // }

        });

        //tableItem.buttons().container().appendTo('#example_wrapper .col-sm-6:eq(0)');

        if (p_item_add == 0) {
            $('.btn-item-add').hide();
        }

    });
    //
</script>

<style>
    .main-col {
        transition: ease .5s;
    }

    .second-col {
        position: relative;
        left: 300em;
        transition: ease .5s;
    }

    .barcode {
        width: 39mm;
        height: 21mm;
        border: 1px solid black;
        text-align: center;
        padding: 0.2em 0;
    }

    .div-barcode {
        border-bottom: 1px solid #e6e6e6;
        padding: 1em;
        width: 100%;
    }

    .card-body-print {
        padding: 1em 0;
    }
</style>

<div class="row">

    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 col-main">
        <div class="card card-secondary">

            <div class="card-header">
                <h3 class="card-title">Data Barang</h3>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-12">
                        <!--<a href="<?= site_url('item/form_new_item'); ?>" class="btn btn-success btn-sm">Tambah Barang</a>-->
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <buttton class="btn btn-success" onclick="print_barcode()">Print Barcode</buttton>
                    </div>
                </div>

                <table id="tableItem" class="table table-bordered table-valign-middle">

                    <thead>

                        <tr>
                            <th><i class="fa fa-cube"></i> Barang</th>
                            <th><i class="fa fa-key"></i> Kode</th>
                            <!-- <th><i class="fa fa-tag"></i> Merk</th> -->
                            <th><i class="fa fa-bookmark"></i> Kategori</th>
                            <!-- <th><i class="fa fa-bookmark"></i> Warna</th> -->
                            <th><i class="fa fa-wrench"></i> Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                    </tbody>

                </table>

            </div>
            <!-- /div.card-body -->

        </div>
        <!-- /div.card -->
    </div>
    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
        <div class="card">
            <div class="card-header text-center target-name">
                <h5>Ilham Meru Gumilang</h5>
            </div>
            <div class="card-body card-body-print">

                <div class="div-barcode">

                    <div class="row text-center">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 text-center">
                            <div class="barcode">
                                image
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 text-center">
                            <div class="barcode">
                                image
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 text-center">
                            <div class="barcode">
                                image
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row p-3">

                    <div class="col-12">
                        <form class="form-inline">
                            <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Jumlah</label>
                            <input type="number" class="form-control form-control-sm print-value">

                            <button type="button" class="btn btn-success btn-sm ml-4 btn-print">Print</button>
                        </form>
                    </div>


                </div>

            </div>
        </div>
    </div>

</div>

<script>
    function print(id, code) {
        var value = $('.print-value').val();

        $.ajax({
            type: 'post',
            data: {
                value: value,
                code: code
            },
            url: '<?= site_url('item/process_print'); ?>',
            dataType: 'text',
            success: function(response) {
                console.log(response);
                w = window.open(window.location.href, "_blank");
                w.document.open();
                w.document.write(response);
                w.document.close();
            }
        })
    }

    function print_barcode() {
        var page = '<?= site_url('item/view_print'); ?>';
        window.location = page;
    }

    function view_print(id) {
        $('.main-div').css({
            "margin": "0",
            "right": "50%"
        });

        $.ajax({
            type: 'post',
            data: {
                id: id
            },
            url: '<?= site_url('item/get_print'); ?>',
            dataType: 'json',
            success: function(response) {

                var img = '<img src="' + response.img + '" />';
                var code = '<p>' + response.code + '</p>';
                $('.barcode').html(img);
                $('.barcode').append(code);

                $('.target-name').html('<h5>' + response.name + '</h5>');

                var print_code = "'" + response.code + "'";

                $('.btn-print').attr('onclick', 'print(' + id + ', ' + print_code + ')');

                $('.print-value').focus();
                $('.print-value').val(1);
            }
        })
    }
</script>