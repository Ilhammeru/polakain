
	<div class="row">

		<div class="col-md-6">

            <?php if (date('H:i:s') < $closingDate) { ?>
			<div class="card card-secondary">

				<div class="card-header">

					<h3 class="card-title">List Permintaan Pengiriman</h3>

				</div>
				<!-- /div.card-header -->

				<div class="card-body p-0">

					<table id="table-list-packing" class="table table-striped table-valign-middle m-0" style="width: 100%">

						<thead>
							<tr class="text-center">
								<th>Nomor Invoice</th>
								<th>Customer</th>
								<th>Tanggal</th>
								<th></th>
							</tr>
						</thead>

					</table>

				</div>
				<!-- /div.card-body -->

			</div>
			<!-- /div.card -->
            <?php } ?>

            <div class="card card-info">

                <div class="card-header">

                    <h3 class="card-title">History pengiriman hari ini</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>

                </div>
                <!-- /div.card-header -->

                <div class="card-body p-0">

                    <table id="table-history" class="table table-striped table-valign-middle m-0" style="width: 100%">

                        <thead>
                            <tr class="text-center">
                                <th>Nomor Invoice</th>
                                <th>Customer</th>
                                <th>Tanggal</th>
                                <th></th>
                            </tr>
                        </thead>

                    </table>

                </div>
                <!-- /div.card-body -->

            </div>
            <!-- /div.card -->

		</div>
		<!-- /div.col -->

		<div class="col-md-6">

			<div id="display-detail"></div>

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

	<script>

		$(document).ready( function () {

			var tableListPacking = $('#table-list-packing').DataTable({

				// Data
                ajax: {
                    url: "<?=site_url('storage/server_side_data');?>",
                    type: "POST"
                },
                processing: true,
                paging: false,
                searching: false,
                info: false,
                order: [2, 'desc'],
                colReorder: true,
                scrollY: '400px',
                scrollX: '1000px',
                scrollCollapse: true,
                // Fix Column
                fixedColumns: {
                    leftColumns: 1
                },

                // Column
                columnDefs: [
                                { 
									targets: [ 2 ],
									type: "de_datetime"
								},
                                {  
                                    targets: [ 3 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],

			});

            var tableHistory = $('#table-history').DataTable({

                // Data
                ajax: {
                    url: "<?=site_url('storage/server_side_data_history');?>",
                    type: "POST"
                },
                processing: true,
                paging: false,
                searching: false,
                info: false,
                order: [2, 'desc'],
                colReorder: true,
                scrollY: '400px',
                scrollX: '1000px',
                scrollCollapse: true,
                // Fix Column
                fixedColumns: {
                    leftColumns: 1
                },

                // Column
                columnDefs: [
                                { 
									targets: [ 2 ],
									type: "de_datetime"
								},
                                {  
                                    targets: [ 3 ],
                                    orderable: false,                                
                                    className: "not-export-col"
                                }
                            ],

            });

		});

		function display_detail(key, x) {

            $.ajax({
                url: "<?=site_url('storage/load_display_detail');?>",
                type: "get",
                data: {
                    id: key,
                    x: x
                },
                beforeSend: function() {
                    var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    $('#display-detail').html(loading);
                },
                success: function (response) {

                    $('#display-detail').html(response);

                }

            });

        }

	</script>