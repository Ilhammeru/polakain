	
	<script>

		$(document).ready( function () {

			$('#modal-confirm-delete').modal('show');

			$("body").on("shown.bs.modal", "#modal-confirm-delete", function () {

				$('#btn-delete').focus();

				$('#btn-delete').on('click', function () {

					$.ajax({

			            url: "<?=site_url('payment_method/delete_payment_method');?>",
			            type: "post",
			            data: {
			            	id: "<?php echo $id;?>"
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

								setTimeout(function(){
								    $('#tablePaymentMethod').DataTable().ajax.reload();
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
			        // End of ajax

				});
				// En of btn-delete click

			});
			// End of show modal-confirm-delete

		});

	</script>

	<div id="modal-confirm-delete" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog" role="document">

			<div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title"><i class="fa fa-info-circle"></i> Konfirmasi</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>

				</div>

				<div class="modal-body">
					<p>Hapus data?</p>
				</div>

				<div class="modal-footer">

					<div class="btn-group">
						<button type="button" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash"></i> Hapus</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					</div>

				</div>

			</div>
			<!-- /div.modal-content -->

		</div>
		<!-- /div.modal-dialog -->

	</div>
	<!-- /div.modal -->

