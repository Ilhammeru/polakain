
    
    <!-- Buttons v1.6.4 -->
    <link rel="stylesheet" href="<?=base_url();?>assets/vendor/datatables/buttons/css/buttons.bootstrap4.min.css?v1.6.4">
    
    <!-- Buttons v1.6.4 -->
    <script src="<?=base_url();?>assets/vendor/datatables/buttons/js/dataTables.buttons.min.js?v1.6.4"></script>
    <script src="<?=base_url();?>assets/vendor/datatables/buttons/js/buttons.bootstrap4.min.js?v1.6.4"></script>

    <script src="<?=base_url();?>assets/vendor/datatables/buttons/js/buttons.flash.min.js?v1.6.4"></script>

	<div class="row">
		<div class="col-md-12">

			<div class="card">

				<div class="card-body">

					<div class="form-inline float-sm-left">
						<div class="input-group p-1">

							<select id="select_category" class="form-control select2">
								<option value="" selected disabled>Pilih Kategori</option>

								<?php foreach ($filterCategory as $row) :
									if ($row->category != '') {
									echo '<option>' . $row->category . '</option>';
									}
								endforeach; ?>

							</select>

						</div>
					</div>
				
				</div>

			</div>
			
		</div>
	</div>

	<div class="row">

		<div class="col-md-12">

			<div id="preview-data"></div>

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

	 <script>

        $(document).ready( function () {

            $('#select_category').on('change', function () {

                var category = $(this).val();
                var date = "<?php echo $date;?>";
                var stock_count = "<?php echo $stock_count;?>";

                $.ajax({
                    url: "<?=site_url('buku_besar/preview_data');?>",
                    type: "get",
                    data: {
                        category: category,
                        date : date,
                        stock_count: stock_count
                    },
                    beforeSend: function() {
                        var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                        $('#preview-data').html(loading);
                    },
                    success: function (response) {

                        $('#preview-data').html(response);

                    }
                });

            });

        });

    </script>
    