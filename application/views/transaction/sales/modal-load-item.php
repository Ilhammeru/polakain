	
	<style>
		.dataTables_filter {
            display: block; 
            padding: 5px;
        }
    </style>

	<script>

		$(document).ready( function () {

			$('#modal-load-item').modal('show');

			$("body").on("shown.bs.modal", "#modal-load-item", function () {

				$('#table-item').DataTable().destroy();

				var tableItem = $('#table-item').DataTable({

					processing: true,
					paging: false,
					info: false,
					scrollY: '400px',
                	scrollCollapse: true,
                	select: 'single',
                	order: [ 1, 'asc'],
                	columnDefs: [
                				{
                					targets: [ 2 ],
                					orderable: false
                				}
                			]

				});

			});

			$("body").on("hidden.bs.modal", "#modal-load-item", function () {
				$('#code').focus();
			});

		});

		function selected_item(param) {

			var code = param;
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
				url: "<?=site_url('sales/insert_item');?>",
				type: "post",
				data: {
					code: code,
					param_selected: param_selected
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

						$('#scanned').html('#' + data.code + ' - ' + data.name);
						insert_row(data);
						
						var active = $('button[data-active="1"]').attr('id');

						$('#modal-load-item').modal('hide');
						
						if (active == 'btn-ecer') {
						    $('#btn-submit').removeAttr('disabled');
						} else {
						    $('#btn-submit').attr('disabled', true);
						}
						console.log(active);
					}

				}
			})

		}

	</script>

	<div id="modal-load-item" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog" role="document">

			<div class="modal-content">

				<div class="modal-header border-0">

					<h5 class="modal-title"><i class="fa fa-search"></i> Cari Barang</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>

				</div>

				<div class="modal-body p-0">

					<table id="table-item" class="table table-valign-middle m-0">
						<thead>
							<tr>
								<th>Kode</th>
								<th>Barang</th>
								<th></th>
							</tr>
						</thead>
						<tbody>

							<?php foreach ($item as $row) :
								echo '<tr onclick="selected_item(\'' . $row->code . '\')">';
								echo '<td>' . $row->code . '</td>';
								echo '<td>' . $row->item_name . '</td>';
								echo '<td><i class="fa fa-plus text-success"></td>';
								echo '</tr>';
							endforeach; ?>

						</tbody>
					</table>

				</div>

				<div class="modal-footer">

					<div class="btn-group">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
					</div>

				</div>

			</div>
			<!-- /div.modal-content -->

		</div>
		<!-- /div.modal-dialog -->

	</div>
	<!-- /div.modal -->


