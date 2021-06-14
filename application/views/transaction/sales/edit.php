
	<div class="row">

		<div class="col-md-12">

			<div class="card">

				<div class="card-header border-0">
                    <h3 class="card-title">Penjualan</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">    

                	<div class="row">

                		<div class="col-md-4">

                			<div class="input-group">

                				<div class="input-group-prepend">
                					<input type="text" id="customer_ref" placeholder="#Customer"
                						   class="form-control form-left" value="<?php echo $sales['customer_ref'];?>">
                				</div>

                				<select id="customer_id" class="form-control select2">
                					<?php 
                					if ($sales['customer_id'] != 0) {
                						echo '<option value="' . $sales['customer_id'] . '">' . $sales['customer_name'] . '</option>'; 
                					} else {
                						echo '<option value="' . $sales['customer_id'] . '">Pilih customer</option>';
                					foreach ($customer as $row) :
                						echo '<option value="' . $row->id . '">' . $row->name . '</option>';
                					endforeach;
                					} ?>
                				</select>

                			</div>

                		</div>
                		<!-- /div.col -->

	                	<div class="col-md-3">

	                		<div class="input-group">
			                	<input type="text" id="code" onchange="insert_item(this)"
			                		   class="form-control" autofocus>
			                	<div class="input-group-append">
			                		<button id="btn-search" class="btn btn-primary"><i class="fa fa-search"></i></button>
			                	</div>

		                	</div>

		                </div>
		                <!-- /div.col -->

		                <div class="col-md-1">

		                	<button id="btn-template" class="btn btn-primary ml-1"><i class="fa fa-archive"></i></button>

		                </div>
	                	<!-- /div.col -->

	                	<div class="col-md-4">

	                		

	                	</div>
	                	<!-- /div.col -->

	                </div>
	                <!-- /div.row -->

	                <br>

	                <div class="row">

	                	<div class="col-md-12">

	                		<h3 id="scanned" class="bg-lightblue p-2">###</h3>

	                	</div>

	                </div>

	                <br>

                	<form id="form-sales" method="post" action="<?=site_url('sales/save_sales');?>">

	                <div class="row">

	                	<div class="col-md-12">

		                	<table id="table-sales" class="table table-stripted table-valign-middle">

		                		<head>
		                			<tr class="text-center">
		                				<th style="width: 15%">Kode</th>
		                				<th style="width: 30%">Barang</th>
		                				<th style="width: 15%">Qty</th>
		                				<th style="width: 17%">Harga</th>
		                				<th style="width: 18%">Jumlah</th>
		                				<th style="width: 5%"></th>
		                			</tr>
		                		</head>

		                		<tbody>
		                			<div id="load-detail"></div>
		                		</tbody>

		                	</table>

		                </div>
		                <!-- /div.col -->

		            </div>
		            <!-- /div.row -->

		            <div class="row">

		            	<div class="offset-md-6 col-md-6">

		            		<table class="table table-valign-middle">

		            			<tr>
		            				<th style="width: 40%">Subtotal</th>
		            				<td class="text-right pr-24" style="width: 50%">
		            					<div id="subtotal">
		            						<?php echo number_format($sales['total_price'], 2, '.', ',');?>
		            					</div>
		            					<input type="hidden" id="input_subtotal" name="subtotal" 
		            						   value="<?php echo number_format($sales['total_price'], 0, '.', ',');?>">
		            				</td>
		            			</tr>
		            			<tr>
		            				<th>PPN (10%)</th>
		            				<td class="text-right pr-24">
		            					<?php echo number_format($sales['ppn'], 2, '.', ',');?>
		            					<input type="hidden" id="ppn" name="ppn" 
		            						   value="<?php echo number_format($sales['ppn'], 0, '.', ',');?>">
		            				</td>
		            			</tr>
		            			<tr>
		            				<th>Ongkir</th>
		            				<td>
		            					<input type="text" name="d_cost" id="d_cost" class="form-control currency-rp" 
		            						   value="<?php echo number_format($sales['d_cost'], 0, '.', ',');?>" onchange="calc_d_cost(this)">
		            				</td>
		            			</tr>
		            			<tr>
		            				<th>Total</th>
		            				<td class="text-right pr-24">
		            					<div id="grandtotal">
		            						<?php echo number_format($sales['grand_total'], 2, '.', ',');?>
		            					</div>
		            					<input type="hidden" id="input_grandtotal" name="grandtotal" 
		            						   value="<?php echo $sales['grand_total'];?>">
		            				</td>
		            			</tr>
		            			<tr>
		            				<th>Metode Pembayaran</th>
		            				<td>
		            					
									</td>
								</tr>
		            			<tr>
		            				<td>

		            					<?php if ($sales['sale_status'] == 2) { ?>
		            						<input type="radio" name="pay_status" value="2" onchange="ps('2')" checked> Lunas
											<input type="radio" name="pay_status" value="1" onchange="ps('1')"> DP
											<input type="radio" name="pay_status" value="3" onchange="ps('3')"> Piutang
										<?php } elseif ($sales['sale_status'] == 1) { ?>
											<input type="radio" name="pay_status" value="2" onchange="ps('2')"> Lunas
											<input type="radio" name="pay_status" value="1" onchange="ps('1')" checked> DP
											<input type="radio" name="pay_status" value="3" onchange="ps('3')"> Piutang
										<?php } elseif ($sales['sale_status'] == 3) { ?>
											<input type="radio" name="pay_status" value="2" onchange="ps('2')"> Lunas
											<input type="radio" name="pay_status" value="1" onchange="ps('1')"> DP
											<input type="radio" name="pay_status" value="3" onchange="ps('3')" checked> Piutang
										<?php } ?>
									</td>
		            				<td>
	            						<?php
	            						if ($sales['sale_status'] != 3) { ?>
		            					<select name="payment_method_id" id="payment_method_id" class="form-control" style="width: 100%">
		            						<!-- <option value="" selected disabled>Pilih rekening</option> -->
		            						<option value="<?php echo $sales['payment_method_id'];?>"><?php echo $sales['payment_method_name'];?></option>
		            						<option value="z">Tunai</option>
		            						<?php
		            						foreach ($payment_method as $row) :
		            							echo '<option value="' . $row->id . '" data-name="' . $row->method . '">' . $row->method . '</option>';
		            						endforeach;
		            						?> 
		            					</select>
		            					<?php } else { ?>
		            						<script>
		            							$(document).ready( function () {
		            								$('#payment_method_id').hide();
		            							});
		            						</script>
		            						<select name="payment_method_id" id="payment_method_id" class="form-control" style="width: 100%">
			            						<option value="0" selected>Pilih rekening</option>
			            						<option value="z">Tunai</option>
			            						<?php
			            						foreach ($payment_method as $row) :
			            							echo '<option value="' . $row->id . '" data-name="' . $row->method . '">' . $row->method . '</option>';
			            						endforeach;
			            						?> 
			            					</select>

		            					<?php } ?>
		            				</td>
		            			</tr>
		            			<tr>
		            				<th>Nominal Bayar</th>
		            				<td>
		            					<input type="text" name="pay" id="pay" class="form-control currency-rp"
		            						   value="<?php echo number_format($sales['nominal_bayar'], 0, '.', ',');?>" readonly>
		            					<?php if($sales['sale_status'] == 1) { ?>
		            						<script>
		            							$(document).ready( function () {
		            								$('#pay').removeAttr('readonly');
		            							});
		            						</script>
		            					<?php } ?>
		            				</td>
		            			</tr>

		            		</table>

		            	</div>

		            </div>
		            <!-- /div.row -->

                </div>
                <!-- /div.card-body -->

                <div class="card-footer">
                	<button type="submit" class="btn btn-primary float-right">Submit</button>
                </div>
                <!-- /div.card-footer-->
            	</form>

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

	<script>

		$('.fa-expand').on('click', function () {
			$('#code').focus();
		});

		$(document).ready( function () {

			load_detail();

			$('#code').focus();

			$('#customer_id').on('change', function() {
				var value = $('option:selected', this).text();
				$('#customer_ref').val(value);
			});

			$('#btn-search').on('click', function () {

                $('div#modal-placehorder').load("<?=site_url('sales/load_modal_item');?>");

			});

			$('#btn-template').on('click', function () {

                $('div#modal-placehorder').load("<?=site_url('sales/load_modal_template');?>");

			});

		});

		function insert_item(param) {

			var code = param.value;

			$.ajax({
				url: "<?=site_url('sales/insert_item');?>",
				type: "post",
				data: {
					code: code
				},
				dataType: "json",
				success: function(data){

					if (data.response == 'error-null') {

						Swal.fire({
									icon: 'error',
									text: 'Barang tidak ditemukan!',
									showConfirmButton: false,
									timer: 1500
								});

						$('#code').val('');

					} else {
						insert_row(data);
						$('#scanned').html('#' + data.code + ' - ' + data.name);
						$('#code').val('');
					}

				}
			})

		}

		function insert_row(data) {
			var tableSales = document.getElementById('table-sales');

			var row = tableSales.insertRow(1);

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			var cell5 = row.insertCell(4);
			var cell6 = row.insertCell(5);

			cell1.innerHTML = data.code + '<input type="hidden" name="item_id[]" value="' + data.id + '">';
			cell2.innerHTML = data.name;
			cell3.innerHTML = '<input type="number" name="item_qty[]" class="form-control" min="1" step="1" onchange="count_subtotal(this, ' + data.id + ')" value="' + data.qty + '">';
			cell3.innerHTML += '<input type="text" name="template_id[]" value="' + data.template_id + '">';
			cell3.innerHTML += '<input type="text" name="template_qty[]" value="' + data.template_qty + '">';
			cell3.innerHTML += '<input type="text" name="template_price[]" value="' + data.template_price + '">';
			cell4.innerHTML = '<div class="text-right">' + number_format(data.price) + '</div>' + '<input type="hidden" name="item_price[]" id="price_' + data.id + '" value="' + data.price + '"><input type="hidden" name="sale_price_id[]" value="' + data.sale_price_id + '">';
			cell5.innerHTML = '<div id="subtotal_' + data.id + '" class="text-right">' + number_format(data.total) + '</div><input type="hidden" class="input_subtotal" id="input_subtotal_' + data.id + '" value="' + data.total + '">';
			cell6.innerHTML = '<a onclick="delete_row(this, ' + data.id + ')"><i class="fa fa-times-circle text-red"></i></a>';

			calc_subtotal();

		}

		function count_subtotal(param, key) {

			if (param.value == 0) {

				Swal.fire({
							icon: 'error',
							text: 'Isikan Qty minimal 1!',
							showConfirmButton: false,
							timer: 1500
						});

				param.value = 1;

				return false;
			}

			var price = $('#price_' + key).val();

			var subtotal = param.value * price;

			document.getElementById('subtotal_' + key).innerHTML = number_format(subtotal);
			document.getElementById('input_subtotal_' + key).value = subtotal;

			var subtotal = 0;
			$('.input_subtotal').each( function () {

				subtotal += +$(this).val();

			});

			document.getElementById("subtotal").innerHTML = number_format(subtotal);

			$('#input_subtotal').val(subtotal);
			
			calc_subtotal();

		}

		function delete_row(param, id) {

			var subtotal = $('#input_subtotal_' + id).val();
			var value = $('#input_subtotal').val();

			var i = param.parentNode.parentNode.rowIndex;
			document.getElementById("table-sales").deleteRow(i);

			var final = value - subtotal;

			calc_subtotal();
		}

		function calc_subtotal() {
			var subtotal = 0;
			var d_cost = $('#d_cost').val();
			var ppn = $('#ppn').val();

			$('.input_subtotal').each( function () {
				subtotal += +$(this).val();
			});

			$('#subtotal').html(number_format(subtotal));
			$('#input_subtotal').val(subtotal);

			subtotal += +d_cost.replace(/,/g,'');
			subtotal += +ppn.replace(/,/g,'');

			$('#grandtotal').html(number_format(subtotal));
			$('#input_grandtotal').val(subtotal);
			$('#pay').val(number_format_2(subtotal));
		}

		function calc_d_cost(param) {
			var value = param.value;
			calc_subtotal();
		}

		function ps(param) {

			var id = parseInt(param);

			if (id == 1) {
				// DP
				$('#payment_method_id').removeAttr('disabled');
				$('#pay').removeAttr('readonly');
				$('#payment_method_id').show();

			} else if (id == 2) {
				// Lunas
				$('#payment_method_id').removeAttr('disabled');
				$('#pay').attr('readonly', true);
				$('#pay').val($('#input_grandtotal').val());
				$('#payment_method_id').show();

			} else if (id == 3) {

				// Piutang
				$('#pay').attr('readonly', true);
				$('#pay').val($('#input_grandtotal').val());
				$('#payment_method_id').hide();
				$('#payment_method_id').attr('disabled', true);

			}

		}

		$("#form-sales").submit( function (e) {
			e.preventDefault();

			var customer_ref = $("#customer_ref").val();

			if (customer_ref == '') {
				customer_ref = 'auto'
			}

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
						url: "<?=site_url('sales/edit_sales/' . $id);?>",
						data: $(this).serialize()+'&customer_ref=' + customer_ref,
						type: $(this).attr("method"),
						success: function(response) {

							console.log(response);

							if (response == 'success') {

		                        Swal.fire({
		                            position: 'center',
		                            icon: 'success',
		                            title: 'Data berhasil disimpan',
		                            showConfirmButton: false,
		                            timer: 1500
		                        });

		                        setTimeout(function(){

		                        	location.href = "<?=site_url('sales/report_data');?>";	

		                        }, 1500);

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

		function load_detail() {

			$.ajax({
				url: "<?=site_url('sales/server_side_data_detail');?>",
				type: "get",
				data: {
					id: "<?php echo $sales['id'];?>"
				},
				dataType: "json",
				success: function (data) {

					for (i = 0; i < data.length; i++) {
						insert_row(data[i]);
					}

				}
			})

		}

	</script>

