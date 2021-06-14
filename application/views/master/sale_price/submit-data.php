

    <script type="text/javascript">

        var titleExport = 'Report Harga Jual';
        var columnExport = [ ':visible:not(.not-export-col)' ];

        function updateSalePrice(id) {

            var new_price = $('#i' + id).val();

            var x = new_price.replace(/^\D+/g, '');

            if (x != 0 && x != '') {

                $.ajax({

                    url: "<?=site_url('sale_price/update_sale_price');?>",
                    type: "post",
                    data: {
                        id: id,
                        new_price: new_price
                    },
                    success: function(response) {

                        if (response == 'success') {

                            // Swal.fire({
                            //     position: 'center',
                            //     icon: 'success',
                            //     title: 'Data berhasil disimpan',
                            //     showConfirmButton: false,
                            //     timer: 1500
                            // });

                            $('#text-' + id).text(new_price + '.00');

                            setTimeout(function(){
                                $('#i' + id).addClass('bg-success');
                            }, 500);

                            setTimeout(function(){
                                $('#i' + id).removeClass('bg-success');
                            }, 5000);
                            
                            //$('#tableSalePrice').DataTable().ajax.reload();

                        } else if (response == 'error') { 
                        
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Ada kesalahan!',
                            });

                        }

                    }

                });
                // // End of ajax

            }

        }
        // End of funct updateSalePrice

        $(document).ready( function () {

            $('#tableSalePrice thead tr').clone(true).appendTo('#tableSalePrice thead');

            $('#tableSalePrice thead tr:eq(1) th').each( function (i) {
            
                var title = $(this).text();

                switch (i) {

                    case 2 :

                        $(this).html('<?php echo $filterCategory;?>');

                        break;

                    case 3 :

                        $(this).html('');

                        break;

                    case 4 :

                        $(this).html('');

                        break;

                    default :

                        $(this).html('<input type="text" class="form-control" placeholder="Cari ' + title + '" />');

                        break;

                }

                $('input', this).on('change', function () {

                    tableSalePrice
                        .column(i)
                        .search(this.value)
                        .draw();

                });

                $('select', this).on('keyup change', function () {

                    var result = [];
                    var options = this && this.options;
                    var opt;

                    for (var x = 0, xLen = options.length; x < xLen; x++) {
                            
                        opt = options[x];

                        if (opt.selected) {

                            result.push(opt.value || opt.text);
                            
                        }

                    }

                    tableSalePrice
                        .column(i)
                        .search(result)
                        .draw();
                        
                });

            });
            // End of clone thead

            var tableSalePrice  = $('#tableSalePrice').DataTable({

                // Data
                ajax: {
                    url: "<?=site_url('sale_price/server_side_data');?>",
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
                //     style: 'single'
                // },

                // Paging
                paging: false,

                // Scroll
                scrollY: '400px',
                scrollCollapse: true,

                // Column
                columnDefs: [
                                {
                                    targets: [ 3 ],
                                    orderable: false
                                },
                                {  
                                    targets: [ 4 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],
                columns: [
                            {
                                "width": "20%"
                            },
                            {
                                "width": "15%"
                            },
                            {
                                "width": "15%"
                            },
                            {
                                "width": "15%"
                            },
                            {
                                "width": "20%"
                            }
                        ],   

                // Buttons          
                // buttons: [ 
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

            //tableSalePrice.buttons().container().appendTo('#example_wrapper .col-sm-6:eq(0)');

            $('#tableSalePrice tbody').on('click', 'tr', function () {

                var dataRow = tableSalePrice.row(this).data();

                $.ajax({
                    url: "<?=site_url('sale_price/get_sale_price_history');?>",
                    type: 'post',
                    data: {
                        code: dataRow[1]
                    },
                    beforeSend: function() {
                        var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                        $('#sale-price-list').html(loading);
                    },
                    success: function(response){

                        $('#sale-price-list').html('');

                        $('#sale-price-list').append(response);
                    }

                });

            });

            tableSalePrice.on('draw.dt', function () {

                currency_rp();

            });

        });
        // 

    </script>

    <div class="row">
        
        <div class="col-md-8">
            
            <div class="card card-secondary">
                
                <div class="card-header">
                    <h3 class="card-title">Data Harga Jual</h3>
                </div>
                
                <div class="card-body p-0">    

                    <table id="tableSalePrice" class="table table-striped table-bordered table-valign-middle m-0" style="width: 100% !important">

                        <thead>

                            <tr>
                                <th><i class="fa fa-cube"></i> Barang</th>
                                <th><i class="fa fa-key"></i> Kode</th>
                                <th><i class="fa fa-bookmark"></i> Kategori</th>
                                <th><i class="fa fa-price"></i> <small>Rp</small> Harga Jual</th>
                                <th><i class="fa fa-edit"></i> Edit</th>
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
        <!-- /div.col -->

        <div class="col-md-4">

            <div class="card card-info">
                
                <div class="card-header">
                    <h3 class="card-title">Data History</h3>
                </div>
                
                <div class="card-body p-0 col-list table-responsive">    

                    <div id="sale-price-list"></div>

                </div>
                <!-- /div.card-body -->

            </div>
            <!-- /div.card -->

        </div>

    </div>
    <!-- /div.row -->