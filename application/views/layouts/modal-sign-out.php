	
	<script>

		$(document).ready( function () {

			$('#modal-signout').modal('show');

			$("body").on("shown.bs.modal", "#modal-signout", function () {

				$('#btn-confirm-signout').focus();

				$('#btn-confirm-signout').on('click', function () {

					location.href = "<?=site_url('dashboard/destroy_sessions');?>";

				});

			});

		});

	</script>

	<div id="modal-signout" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog" role="document">

			<div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title"><i class="fa fa-info-circle"></i> Konfirmasi</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>

				</div>

				<div class="modal-body">
					<p>Logout aplikasi?</p>
				</div>

				<div class="modal-footer">

					<div class="btn-group">
						<button type="button" id="btn-confirm-signout" class="btn btn-primary"><i class="fa fa-power-off"></i> Logout</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					</div>

				</div>

			</div>
			<!-- /div.modal-content -->

		</div>
		<!-- /div.modal-dialog -->

	</div>
	<!-- /div.modal -->

