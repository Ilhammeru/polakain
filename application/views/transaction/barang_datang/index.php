    
    <style>
        html {
            --dim-gray: #5e5e5e;
        }
        .container {
            color: var(--dim-gray) !important;
        }
        .container {
            height: 600px;
        } 
        .b-default {
            border: 1px solid #eaeaea;
        }
        .content-wrapper {
            background-color: white;
        }
        .table th, 
        .table td {
            padding:  .25rem !important;
        }
        .table thead th,
        .table tbody td {
            border-bottom: 1px solid #eaeaea;
            border-right: 1px solid #eaeaea;
            border-left: 1px solid #eaeaea;
        }
        .table tbody {
            display: block;
            overflow: auto;
            height: 400px;
            width: 100%;
            /* border-bottom: 1px solid rgba(0,0,0,0.2); */
        }
        .table thead, .table tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;/* even columns width , fix width of table too*/
        }
        .table>:not(:last-child)>:last-child>* {
            border-bottom-color: 1px solid rgba(0,0,0,0.2) !important;
        }
        .input-danger {
            border: 1px solid red !important;
        }
        .form-control {
            border: 1px solid #eaeaea;
        }
        .form-control:focus {
            border: 1px solid #eaeaea;
            box-shadow: none;
        }
        .bg-success {
            background-color: #28a745;
            color: white;
        }
        .modal-content {
            box-shadow: none;
        }
    </style>
    <div class="container-fluid p-4">

        <div class="row mb-4">

            <div class="col-12 mb-4">
                <h3>Input Barang Datang</h3>
            </div>  

            <div class="col-3 p-0 mb-3">
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-input-item">Input Item</button>
            </div>
            <div class="col-2 ml-4">
                <input type="text" id="scanner" class="form-control mb-2" placeholder="Scan barcode disini">
            </div>
            <div class="col-6"></div>

	        <form style="display: inherit" id="form-barang-datang" method="post" action="<?=site_url('invoice/save_barang_datang');?>">
	        <input type="hidden" name="grandtotal" id="grandtotal">

            <div class="col-3 b-default p-4">

                <div class="form-group">
                    <input type="text" id="invoice_number" name="invoice_number" class="form-control text-right" placeholder="Nomor Invoice">
                </div>

                <div class="form-group">
                    <select id="vendor_id" name="vendor_id"
                        class="form-control">
                        <option value="" selected disabled>Pilih Vendor</option>
                        <?php
                        foreach ($vendor as $row):
                            echo '<option value="' . $row->id . '">' . $row->name . '</option>';
                        endforeach;
                        ?>
                    </select>
                </div>

                <!-- <div class="form-group">
                    <select id="warehouse_id" name="warehouse_id"
                        class="form-control">
                        <option value="" selected disabled>Pilih Warehouse</option>
                        <?php
                        foreach ($warehouse as $row):
                            echo '<option value="' . $row->id . '">' . $row->name . '</option>';
                        endforeach;
                        ?>
                    </select>
                </div> -->
                
                <div class="form-group">
				    <input type="text" id="disc" name="disc" class="form-control currency-rp" placeholder="Diskon">
                </div>

                <div class="form-group">
				    <input type="text" id="ppn" name="ppn" class="form-control currency-rp" placeholder="PPN">
                </div>
				
                <div class="form-group">
                    <input type="text" id="d_cost" name="d_cost" class="form-control currency-rp" placeholder="Ongkos kirim">
                </div>							
                
                <div class="form-group">
                    <label for="ekpedisi_matoa">Expedisi Matoa</label>

                    <div class="form-check">
                        <input type="radio" name="with_matoa_shipping" value="0" autocomplete="off" checked> 
                        <label class="form-check-label">Tidak</label>
                        <input type="radio" name="with_matoa_shipping" value="1" autocomplete="off">
                        <label class="form-check-label">Ya</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="pembayaran">Pembayaran</label>

                    <div class="form-check">
                        <input type="radio" name="payment_status" value="2" autocomplete="off" checked> 
                        <label class="form-check-label">Lunas</label>
                        <input type="radio" name="payment_status" value="3" autocomplete="off">
                        <label class="form-check-label">Hutang</label>
                    </div>
                </div>

            </div>
            <div class="col-8 b-default p-4 ml-4">
                
                <table id="table-invoice" class="table w-100">
                    <thead>
                        <tr class="text-center"> 
                            <th style="width: 25%">Kode</th>
                            <th style="width: 25%">Qty</th>
                            <th style="width: 25%">HPP</th>
                            <th style="width: 25%">Total</th>
                    </thead> 
                    <tbody></tbody>
                    
                </table>
            </div>

        </div>
        <!--/.row --> 

        <div class="row">

            <div class="col-11 ml-4">
                <button type="submit" class="btn btn-primary float-right">Submit</button>
            </div>
        </div>
        <!--/.row -->
    
    </div>
    <!--/.container -->
    </form>

    <div class="modal fade" id="modal-input-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Input Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-item" entype="multipart/form-data">
                <div class="modal-body">
                
                    <div class="form-group">
                        <select id="category_id" name="category_id"
                        class="form-control select2 w-100" required>
                            <option value="" selected disabled>Pilih Category</option>
                            <?php
                            foreach ($category as $row):
                                echo '<option value="' . $row->id . '">' . $row->name . '</option>';
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <div class="row b-default p-2 m-0 mb-4">
                        
                        <?php
                        $i = 0;
                        foreach ($color as $row) : 
                            
                            echo '<div class="col-4">';
                                         
                            echo '<div class="form-check">
                                <input class="form-check-input color" name="color[]" type="checkbox" value="' . $row->id . '" id="color-' . $row->id . '">
                                <label class="form-check-label" for="color-' . $row->id . '">
                                    ' . $row->name . '
                                </label>
                            </div>';

                            echo '</div>';
                            
                        endforeach;
                        ?>
                    
                    </div>
                    <!--/.b-default -->

                    <div class="form-group">
                        <input type="file" name="file" id="file">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal modal-loading" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="row" style="margin-left:auto;margin-right:auto">
                <div class="col-12 text-center">
                    <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                </div>
            </div>
        </div>
        <!--/.modal-dialog -->

    </div>
    <!--/.modal.modal-loading -->

    <script>

        $(document).ready( function () {
            $('#scanner').focus();
        });

        $('#scanner').on('change', function ()   {

            if ($(this).val() != '')  {

                $.ajax({
                    url:  "<?=site_url('invoice/scan_barcode');?>",
                    type: "post",
                    data: {
                        code: $(this).val()
                    },
                    dataType: "json",
                    success: function (data)  {

                        $('#scanner').val('');

                        if (data.response == 'error-null') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Item tidak ditemukan!',
                            });
                        } else {

                            append_row(data, 1);
                        }
                    }
                });
            }
        });

        function currency_rp() {

            $('.currency-rp').inputmask("numeric", {

	            radixPoint: ".",
	            groupSeparator: ",",
	            digits: 2,
	            autoGroup: true,
	            prefix: 'Rp ',
	            rightAlign: false,
	            allowMinus: false,
	            oncleared: function () {
	                // self.value('');
	            }
	        });
        }

		function calculate() {
			
			var total = 0;
			$('.totalprice').each( function () {
				var value = $(this).val();
				value = value.replace('.00','');
				value = value.replace(/,/g,'');
				total += +value;
			});
			
			$('#grandtotal').val(total);
		}

        function check_required()  {

            var checked = 0;
            $('.color').each( function () {

                if ($(this).is(':checked')) {
                    checked += 1;
                }
            });

            return checked;
        }


		function check_row(id) {

			var check = 0;

			$('.item-id').each( function () {

				var x = $(this).data('id');
				if (x == id) {
					check = id;
				}
			});

			return check;
		}

        $('#form-item').submit( function (e) {
            e.preventDefault();
            
            var form = $('#form-item');
            var formData = new FormData(form[0]);

            Swal.fire({
                title: 'Data sudah benar?',
                //text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Submit!'
                }).then((result) => {

                if (result.isConfirmed) {

                    if (check_required() == 0) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Pilih color!',
                        });

                        return false;
                    }

                    if ($('#file').val() == '') {

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Pilih gambar!',
                        });

                        return false;
                    }

                    $.ajax({
                        url: "<?=site_url('item/save_item');?>",
                        type: "post",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        beforeSend: function(data) {
                            $('.modal-loading').modal('show');
                        },
                        success: function (data) {
                            $('.modal-loading').modal('hide');

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Data berhasil disimpan',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            $('#category_id').select2('destroy');

                            setTimeout(function(){
								$('#form-item')[0].reset();	
                                $('#modal-input-item').modal('hide');
                                // $('#modal-input-item #category_id').prop('selectedIndex',0);
                                $('#category_id').select2();

                                for (i = 0; i < data.length; i++)  {
                                    append_row(data[i], 0);
                                }
							}, 1000);

                        }
                    });
                }
            });
        });

        function append_row(data, param) {

            if (check_row(data.id) != 0) {

                if ($('#warehouse-qty-' + data.id).val() != '' && $('#price_' + data.id).val() != '') {

                    var qty = +$('#warehouse-qty-' + data.id).val() + 1;
                    $('#warehouse-qty-' + data.id).val(qty);

                    var price = $('#price_' + data.id).val();
                    var subprice = qty * price.replace(',', '');

                    $('#input_subtotal_' + data.id).val(subprice);
                }
			} else {

                var table = document.getElementById('table-invoice').getElementsByTagName('tbody')[0];
                var countRow = table.rows.length;

                var row = table.insertRow(countRow);

                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                var cell4 = row.insertCell(3);

                var deleted = '';
                if (param == 1) {
                    deleted = '<a onclick="delete_row(this, ' + data.id + ')"><i class="fa fa-times-circle text-red"></i></a>';
                }

                cell1.innerHTML = data.code + ' (' +  data.color + ')' + ' ' + deleted + '<input type="hidden" name="item_id[]" class="item-id" data-id="' +  data.id + '" value="' + data.id + '">';
                cell2.innerHTML = '<input type="number" name="warehouse_qty[]" class="form-control" id="warehouse-qty-' + data.id + '" step="1" min="0">';
                cell3.innerHTML = '<input type="text" name="price[]" class="form-control price currency-rp" id="price_' +  data.id + '">';
                cell4.innerHTML = '<input type="text" name="totalprice[]" class="form-control totalprice currency-rp" id="input_subtotal_' + data.id + '" onkeyup="calculate()">';

            }

            calculate();
            
            currency_rp();
        }

		function delete_row(param, id) {

			var i = param.parentNode.parentNode.rowIndex;
			document.getElementById("table-invoice").deleteRow(i);

            calculate();
		}

        $('#form-barang-datang').validate({

            rules: {
                invoice_number: {
                    required: true
                },
                vendor_id: {
                    required: true
                }
            },
            messages: {
                invoice_number: {
                    required: 'Kolom nomor invoice harus diisi'
                },
                vendor_id: {
                    required: 'Pilih vendor'
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function (form) {

                Swal.fire({
                    title: 'Data sudah benar?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Submit!'
                    }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: $(form).serialize(),
                            success: function(response) {

                                if (response == 'success') {
                                    setTimeout(function(){
                                        $(form)[0].reset();	
                                    }, 1000);

                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Data berhasil disimpan',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                    setTimeout(function(){
                                        location.href = "<?=site_url('invoice/report_data');?>";
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
            }
        });

    </script>
