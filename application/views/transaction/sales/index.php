<style>
	.sale-form {
		display: none;
	}

	.card-footer {
		display: none;
	}

	.border-danger {
		border: 1px solid red;
		-webkit-box-shadow: 2px 2px 15px 2px rgba(255, 37, 21, 0.51);
		box-shadow: 2px 2px 15px 2px rgba(255, 37, 21, 0.51);
	}
</style>
<div class="row">

	<div class="col-md-12">

		<?php
		if (date('H:i:s') > $closingDate) { ?>

			<div class="card">

				<div class="card-body">

					Sudah closing

				</div>

			</div>

		<?php } else { ?>
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

					<div class="phone-selection">
						<div class="form-row">
							<div class="form-group col-md-3">
								<label for="phone">No. Telepon</label>
								<input type="text" class="form-control" id="phone" placeholder="Ketik nomor visitor">
							</div>
						</div>
					</div>

					<div class="sale-form">

						<div class="row mb-3">

							<div class="col-md-4">
								<div class="input-group">
									<div class="input-group-prepend">
										<input type="text" id="customer_ref" placeholder="#Customer" class="form-control form-left">
									</div>

									<!-- <select id="customer_id" class="form-control select2">
										<option value="" selected disabled>Pilih Customer</option>
										<?php foreach ($customer as $row) :
											echo '<option value="' . $row->id . '">' . $row->name . '</option>';
										endforeach; ?>
									</select> -->
								</div>
							</div>
							<!-- /div.col -->

							<div class="col-md-2">
								<?php
								if ($this->session->userdata('template_id') == '0') {
									echo '<button type="button" class="btn btn-type-jual btn-primary selected" id="btn-ecer">Eceran</button>';
									echo ' <button type="button" class="btn btn-type-jual btn-default" id="btn-paket">Paket</button>';
								} else {
									echo '<button type="button" class="btn btn-type-jual btn-default" id="btn-ecer">Eceran</button>';
									echo ' <button type="button" class="btn btn-type-jual btn-primary selected" id="btn-paket">Paket</button>';
								}
								?>
							</div>

							<div class="col-md-2">
								<button id="btn-template" class="btn btn-primary ml-1 <?= $this->session->userdata('template_id') != '0' ? '' : 'd-none'; ?>">PILIH PAKET <i class="fa fa-archive"></i></button>
							</div>
							<!-- /div.col -->

							<div class="col-md-4">
								<h3 id="label-template" class="text-center">
									<?php

									if ($this->session->userdata('template_id') == '0') {
										echo 'ECERAN';
									} else {
										echo $this->session->userdata('template_name') . ' (' . $this->session->userdata('template_qty') . ' Pcs)';
									}
									?>
								</h3>
							</div>
							<!-- /div.col -->

						</div>
						<!-- /div.row -->

						<div class="row">
							<div class="col-md-12">

								<div class="input-group">
									<input type="text" id="code" onchange="insert_item(this)" class="form-control">
									<div class="input-group-append">
										<button id="btn-search" class="btn btn-primary"><i class="fa fa-search"></i></button>
									</div>

								</div>

							</div>
						</div>
						<!--/div.row -->

						<br>

						<div class="row">

							<div class="col-md-12">

								<h3 id="scanned" class="bg-lightblue p-2">###</h3>

							</div>

						</div>

						<br>

						<form id="form-sales" method="post" action="<?= site_url('sales/save_sales'); ?>">

							<div class="row">

								<div class="col-md-12">

									<input type="hidden" id="visitor_id" name="visitor_id">
									<input type="hidden" id="visitor_phone" name="visitor_phone">

									<table id="table-sales" class="table table-stripted table-valign-middle">

										<thead>
											<tr class="text-center">
												<th style="width: 15%">Kode</th>
												<th style="width: 30%">Barang</th>
												<th style="width: 15%">Qty</th>
												<th style="width: 17%">Harga</th>
												<th style="width: 18%">Jumlah</th>
												<th style="width: 5%"></th>
											</tr>
										</thead>

										<tbody>

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
												<div id="subtotal">0</div>
												<input type="hidden" id="input_subtotal" name="subtotal" value="0">
											</td>
										</tr>
										<tr>
											<th>PPN (10%)</th>
											<td class="text-right pr-24">
												0
												<input type="hidden" name="ppn" id="ppn" value="0">
											</td>
										</tr>
										<tr>
											<th>Ongkir</th>
											<td>
												<input type="text" name="d_cost" id="d_cost" class="form-control text-right currency-rp" value="0" onchange="calc_d_cost(this)">
											</td>
										</tr>
										<tr>
											<th>Total</th>
											<td class="text-right pr-24">
												<div id="grandtotal">0</div>
												<input type="hidden" id="input_grandtotal" name="grandtotal" value="0">
											</td>
										</tr>
										<tr>
											<th colspan="3">Metode Pembayaran</th>
										</tr>
										<tr>
											<td>
												<input type="radio" name="pay_status" value="2" onchange="ps('2')" checked> Lunas
												<input type="radio" name="pay_status" value="1" onchange="ps('1')"> DP
												<input type="radio" name="pay_status" value="3" onchange="ps('3')"> Piutang
											</td>
											<td>
												<select name="payment_method_id" id="payment_method_id" class="form-control" style="width: 100%">
													<!-- <option value="" selected disabled>Pilih rekening</option> -->
													<option value="z">Tunai</option>
													<?php
													foreach ($payment_method as $row) :
														echo '<option value="' . $row->id . '" data-name="' . $row->method . '">' . $row->method . '</option>';
													endforeach;
													?>
												</select>
											</td>
										</tr>
										<tr>
											<th>Nominal Bayar</th>
											<td>
												<input type="text" name="pay" id="pay" class="form-control text-right currency-rp" readonly>
											</td>
										</tr>

									</table>

								</div>

							</div>
							<!-- /div.row -->

					</div>

				</div>
				<!-- /div.card-body -->

				<div class="card-footer">
					<button type="submit" class="btn btn-primary float-right" id="btn-submit">Submit</button>
				</div>
				<!-- /div.card-footer-->
				</form>

			</div>
			<!-- /div.card -->
		<?php } ?>

	</div>
	<!-- /div.col -->

</div>
<!-- /div.row -->

<script>
	$('.fa-expand').on('click', function() {
		$('#phone').focus();
	});

	$(document).ready(function() {

		$('#phone').focus();

		$('#customer_id').on('change', function() {
			var value = $('option:selected', this).text();
			$('#customer_ref').val(value);
		});

		$('#btn-search').on('click', function() {
			$('div#modal-placehorder').load("<?= site_url('sales/load_modal_item'); ?>");
		});

		$('#btn-template').on('click', function() {
			$('div#modal-placehorder').load("<?= site_url('sales/load_modal_template_pola'); ?>");
		});

		autocomplete();
	});

	function autocomplete() {
		$('#phone').focus(() => {
			$('#phone').removeClass('border-danger');
			$('#phone').attr('placeholder', 'Ketik nomor visitor');
		});
		$('#phone').autocomplete({
			minLength: 7,
			source: '<?= site_url('sales/autocomplete'); ?>',
			select: function(event, ui) {
				var hp = ui.item.value;

				get_visitor(hp);
			}
		});
	}

	function get_visitor(hp = '') {
		if (hp == '') {
			var hp = $('#phone').val();
		}

		$.ajax({
			type: 'post',
			data: {
				data: hp
			},
			url: '<?= site_url('sales/get_visitor'); ?>',
			dataType: 'json',
			success: function(response) {
				var success = '<i class="fas fa-check"></i>';
				var failed = '<i class="fas fa-times"></i>';

				if (response.message == 'success') {
					$('#visitor_id').val('');
					$('#visitor_phone').val('');
					$('#customer_ref').val('');

					$('#phone').val(hp);
					$('#visitor_id').val(response.id);
					$('#customer_ref').val(response.name);
					$('#visitor_phone').val(hp);

					show_form();
					reset_table('ecer');
				} else {
					$('.sale-form').hide();
					$('#phone').addClass('border-danger');

					$('#phone').val('');
					$('#phone').attr('placeholder', 'Data tidak ditemukan');
				}

			}
		})
	}

	function show_form() {
		$('.sale-form').show();
		$('.card-footer').show();
		$('.btn-type-jual').removeClass('selected btn-primary').addClass('btn-default');
		$('#btn-ecer').removeClass('btn-default').addClass('selected btn-primary');

		// add atribute
		$('.btn-type-jual').removeAttr('data-active');
		$('#btn-ecer').attr('data-active', 1);

		$('#btn-template').addClass('d-none');
		$('#btn-submit').removeAttr('disabled');

		$('#label-template').html('ECERAN');
	}

	function reset_table(param = '') {
		var table = $('#table-sales tbody');
		table.empty();

		$('#subtotal').text(0);
		$('#input_subtotal').val('');

		$('#ppn').val('');

		$('#d_cost').val(0);

		$('#grandtotal').text(0);
		$('#input_grandtotal').val('');

		$('#pay').val('');

		if (param != 'ecer') {
			$('#btn-submit').attr('disabled', true);
		} else {
			$('#btn-submit').attr('disabled', false);
		}

	}

	function insert_item(param) {

		var code = param.value;
		var btn_ecer = 0;
		var btn_paket = 0;
		var param_selected;

		if ($('#btn-ecer').hasClass('selected')) {
			btn_ecer = 1;
		}

		if ($('#btn-paket').hasClass('selected')) {
			btn_paket = 1;
		}

		if (btn_ecer == 1) {
			param_selected = 1;
		} else {
			param_selected = 2;
		}

		$.ajax({
			url: "<?= site_url('sales/insert_item'); ?>",
			type: "post",
			data: {
				code: code,
				param_selected: param_selected
			},
			dataType: "json",
			success: function(data) {

				if (data.response == 'error-null') {

					Swal.fire({
						icon: 'error',
						text: 'Barang tidak ditemukan!',
						showConfirmButton: false,
						timer: 1500
					});

					$('#code').val('');

				} else if (data.response == 'price-null') {

					Swal.fire({
						icon: 'error',
						text: 'Harga Barang tidak ditemukan!',
						showConfirmButton: false,
						timer: 1500
					});

					$('#code').val('');

				} else if (data.response == 'template-null') {

					Swal.fire({
						icon: 'error',
						text: 'Pilih paket terlebih dahulu!',
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

		var price_txt = number_format(data.price);
		var subprice_txt = number_format(data.total);

		if (data.template_id != 0) {
			price_txt = '-';
			subprice_txt = '-';
		}

		if (check_row(data.id) != 0) {

			var qty = +$('#item-qty-' + data.id).val() + 1;
			$('#item-qty-' + data.id).val(qty);

			var price = $('#price_' + data.id).val();
			var subprice = qty * price;

			$('#input_subtotal_' + data.id).val(subprice);

			if (data.template == 0) {
				$('#subtotal_' + data.id).html(number_format(subprice));
			}
		} else {
			var tableSales = document.getElementById('table-sales').getElementsByTagName('tbody')[0];

			var length = tableSales.rows.length;

			var row = tableSales.insertRow(length);

			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var cell4 = row.insertCell(3);
			var cell5 = row.insertCell(4);
			var cell6 = row.insertCell(5);

			cell1.innerHTML = data.code + '<input type="hidden" class="item-id" id="item-id-' + data.id + '" data-id="' + data.id + '" name="item_id[]" value="' + data.id + '">';
			cell2.innerHTML = data.name;

			cell3.innerHTML = '<input type="number" name="item_qty[]" id="item-qty-' + data.id + '" class="form-control" min="1" step="1" onchange="count_subtotal(this, ' + data.id + ', ' + data.template_id + ')" value="' + data.qty + '">';

			cell3.innerHTML += '<input type="hidden" name="template_id[]" value="' + data.template_id + '">';
			cell3.innerHTML += '<input type="hidden" name="template_qty[]" value="' + data.template_qty + '">';
			cell3.innerHTML += '<input type="hidden" name="template_price[]" value="' + data.template_price + '">';

			cell4.innerHTML = '<div class="text-right">' + price_txt + '</div>' + '<input type="hidden" name="item_price[]" id="price_' + data.id + '" value="' + data.price + '"><input type="hidden" name="sale_price_id[]" value="' + data.sale_price_id + '">';

			cell5.innerHTML = '<div id="subtotal_' + data.id + '" class="text-right">' + subprice_txt + '</div><input type="hidden" class="input_subtotal" id="input_subtotal_' + data.id + '" value="' + data.total + '">';

			cell6.innerHTML = '<a onclick="delete_row(this, ' + data.id + ')"><i class="fa fa-times-circle text-red"></i></a>';
		}

		calc_subtotal();
	}

	function check_row(id) {

		var check = 0;

		$('.item-id').each(function() {

			var x = $(this).data('id');

			if (x == id) {
				check = id;
			}
		});

		return check;
	}

	function count_subtotal(param, key, template_id) {

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

		if (template_id == 0) {
			document.getElementById('subtotal_' + key).innerHTML = number_format(subtotal);
		}

		document.getElementById('input_subtotal_' + key).value = subtotal;

		var subtotal = 0;
		$('.input_subtotal').each(function() {
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

		$('.input_subtotal').each(function() {
			subtotal += +$(this).val();
		});

		$('#subtotal').html(number_format(subtotal));
		$('#input_subtotal').val(subtotal);

		subtotal += +d_cost.replace(/,/g, '');
		subtotal += +ppn.replace(/,/g, '');

		$('#grandtotal').html(number_format(subtotal));
		$('#input_grandtotal').val(subtotal);
		$('#pay').val(number_format_2(subtotal));

		validate_template();
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

	function validate_template() {

		var grandtotal = $('#input_grandtotal').val();

		$.ajax({
			url: "<?= site_url('sales/validate_template'); ?>",
			type: "post",
			data: {
				grandtotal: grandtotal
			},
			success: function(response) {
				//validation btn type jual 
				var active = $('button[data-active="1"]').attr('id');

				if (active == 'btn-ecer') {
					$('#btn-submit').removeAttr('disabled');
				} else {
					if (response == 'matched') {
						$('#btn-submit').removeAttr('disabled');
					} else {
						$('#btn-submit').attr('disabled', true);
					}
				}
			}
		});
	}

	$("#form-sales").submit(function(e) {
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
					url: "<?= site_url('sales/save_sales'); ?>",
					data: $(this).serialize() + '&customer_ref=' + customer_ref,
					type: $(this).attr("method"),
					success: function(response) {

						if (response == 'success') {

							Swal.fire({
								position: 'center',
								icon: 'success',
								title: 'Data berhasil disimpan',
								showConfirmButton: false,
								timer: 1500
							});

							setTimeout(function() {
								location.href = "<?= site_url('sales/index'); ?>";
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

	$('#btn-ecer').on('click', function() {

		$.ajax({
			url: "<?= site_url('sales/selected_template'); ?>",
			type: "post",
			data: {
				id: 0
			},
			success: function() {
				$('.btn-type-jual').removeClass('selected btn-primary').addClass('btn-default');
				$('#btn-ecer').removeClass('btn-default').addClass('selected btn-primary');

				$('#btn-template').addClass('d-none');
				$('#btn-submit').removeAttr('disabled');

				$('#label-template').html('ECERAN');

				//add atribute
				$('.btn-type-jual').removeAttr('data-active');
				$('#btn-ecer').attr('data-active', 1);

				$('#table-sales tbody').empty();
				reset_table('ecer');
			}
		});
	});

	$('#btn-paket').on('click', function() {

		$('.btn-type-jual').removeClass('selected btn-primary').addClass('btn-default');
		$(this).removeClass('btn-default').addClass('selected btn-primary');

		//add atribute
		$('.btn-type-jual').removeAttr('data-active');
		$('#btn-paket').attr('data-active', 1);

		$('#btn-template').removeClass('d-none');
		$('#btn-submit').attr('disabled', true);

		$('#label-template').html('');
		reset_table();
	});
</script>