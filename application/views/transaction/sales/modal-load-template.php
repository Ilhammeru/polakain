
	<style>
		.nav-tabs.flex-column .nav-item.show .nav-link, .nav-tabs.flex-column .nav-link.active {
    		border-color: none !important;
		}
	</style>

	<script>

		$(document).ready( function () {

			$('#modal-load-template').modal('show');

		});

		function selected_template(param) {

			var qty = $("#template_qty_" + param).val();

			$.ajax({

				url: "<?=site_url('sales/insert_template');?>/" + param + "/" + qty,
				dataType: "json",
				success: function(data) {

					if (data.response == 'price-null') {

						Swal.fire({
								icon: 'error',
								text: 'Harga ' + data.name + ' tidak ditemukan!',
								showConfirmButton: false,
								timer: 1500
							});

					} else {

						for (i = 0; i < data.length; i++) {
							insert_row(data[i]);
						}

						$('#scanned').html('#' + data[0].template_name);

						$('#modal-load-template').modal('hide');

					}

				}

			});

		}

	</script>

	<div id="modal-load-template" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-lg" role="document">

			<div class="modal-content">

				<div class="modal-header border-0">

					<h5 class="modal-title"><i class="fa fa-search"></i> Cari Template</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>

				</div>

				<div class="modal-body p-0">

					<div class="row">

						<div class="col-5 col-sm-3">
							<div class="nav flex-column nav-tabs" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">

								<div id="x" style="height: 700px; overflow-y: auto">

								<?php foreach ($template as $row) :

									if ($row['detail'] != '') {

										echo '<a class="nav-link" 
												 id="vert-tabs-' . $row['id'] . '-tab" 
												 data-toggle="pill" 
												 href="#vert-tabs-' . $row['id'] . '" 
												 role="tab" 
												 aria-controls="vert-tabs-' . $row['id'] . '" 
												 aria-selected="true"
												 style="color: #495057;">' . $row['brand'] . ' ' . $row['tipe'] . '</a>';

									}

								endforeach; ?>

								</div>

							</div>
						</div>

						<div class="col-7 col-sm-9">

							<div class="tab-content" id="vert-tabs-tabContent">

								<p>Pilih template</p>

								<?php foreach ($template as $row) :

								if ($row['detail'] != '') {

									echo '<div class="tab-pane" id="vert-tabs-' . $row['id'] . '" role="tabpanel" aria-labelledby="vert-tabs-' . $row['id'] . '-tab">';

									$detail = json_decode($row['detail'], TRUE);
									?>

									<h2><?php echo $row['template_name'];?></h2>

									<table class="table table-striped table-valign-middle">
										<thead>
											<tr>
												<th>Kode</th>
												<th>Barang</th>
												<th>Qty</th>
											</tr>
										</thead>
										<tbody>

										<?php
										foreach ($item as $list) :

											$key = 'i' . $list->id;

											if (isset($detail[$key])) {
												echo '<tr>';
												echo '<td>' . $list->code . '</td>';
												echo '<td>' . $list->name . '</td>';
												echo '<td>' . $detail[$key] . '</td>';
												echo '</tr>';
											}

										endforeach;
										?>
									
										</tbody>
									</table>

									<div class="form-group">

										<label for="template_qty">Jumlah</label>
										<input type="number" class="form-control" id="<?php echo 'template_qty_' . $row['id'];?>" min="1" value="1" step="1" style="width: 30%">

									</div>

									<button class="btn btn-primary float-right mr-3 mb-3" onclick="selected_template('<?php echo $row['id'];?>')">Pilih</button>
									</div>
									<!-- /div.tab-pane -->

								<?php }

							endforeach; ?>

							</div>
							<!-- /div.tab-content -->

						</div>
						<!-- /div.col -->

					</div>
					<!-- /div.row -->

				</div>
				<!-- /div.modal -->

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


