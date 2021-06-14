
	<style>
		.nav-tabs.flex-column .nav-item.show .nav-link, .nav-tabs.flex-column .nav-link.active {
    		border-color: none !important;
		}
		#table-template tbody {
			display: block;
			overflow: auto;
			height: 200px;
			width: 100%;
			/* border-bottom: 1px solid rgba(0,0,0,0.2); */
		}
		#table-template thead, #table-template tbody tr {
			display: table;
			width: 100%;
			table-layout: fixed;/* even columns width , fix width of table too*/
		}
	</style>

	<script>

		$(document).ready( function () {

			$('#modal-load-template').modal('show');
		});

	</script>

	<div id="modal-load-template" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog modal-lg" role="document">

			<div class="modal-content">

				<div class="modal-header border-0">

					<h5 class="modal-title"><i class="fa fa-search"></i> Cari Paket</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>

				</div>

				<div class="modal-body p-0">

					<div class="row">

						<div class="col-md-12">

                            <table id="table-template" class="table table-hover table-sm">

                                <thead>
                                    <tr>
                                        <th class="pl-4 text-center" style="width: 50%">Template</th>
                                        <th class="text-center" style="width: 20%">Qty</th>
                                        <th class="pr-4 text-center" style="width: 30%">Harga</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php foreach ($template as $row) : 
                                        echo '<tr onclick="tr_selected(' . $row->id . ')">';
                                        echo '<td style="width: 50%" class="pl-4">' . $row->name . '</td>';
                                        echo '<td style="width: 20%" class="text-center">' . $row->qty . '</td>';
                                        echo '<td style="width: 30%" class="pr-4 text-right">' . number_format($row->price, 0, '.', ',') . '</td>';
                                        echo '</tr>';
                                    endforeach; ?>
                                
                                </tbody>

                            </table>

                        </div>

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

	<script>

		function tr_selected(id) {

			$.ajax({
				url: "<?=site_url('sales/selected_template');?>",
				type: "post",
				data: {
					id: id
				},
				dataType: "json",
				success: function (data) {

					$('#label-template').html(data.template_name + ' (' + data.template_qty + ' Pcs)');
					$('#modal-load-template').modal('hide');
					$('#code').focus();
					
					var active = $('button[data-active="1"]').attr('id');
					
					if (active == 'btn-ecer') {
					    reset_table('ecer');
					} else {
					    reset_table('paket');
					}

					// location.href = "<?=site_url('sales');?>";
				}
			});
		}

	</script>


