
	<!-- Main content -->
	<div class="invoice p-3 mb-3">

		<!-- title row -->
		<div class="row">

			<div class="col-12">
			<h4>
			<small><?php echo date_format(date_create($move_item['created_time']), 'd M Y H:i:s');?></small>
			</h4>
			</div>
			<!-- /.col -->

		</div>
		<!-- info row -->

		<div class="row invoice-info">

			<div class="col-sm-4 invoice-col">
				Gudang
				<address>
					<strong><?php echo $move_item['from_warehouse_name'];?></strong><br>
				</address>
			</div>
			<!-- /.col -->

			<div class="col-sm-4 invoice-col">
			</div>
			<!-- /.col -->

			<div class="col-sm-4 invoice-col">
				Tujuan
				<address>
					<strong><?php echo $move_item['target_warehouse_name'];?></strong><br>
				</address>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.row -->

		<!-- Table row -->
		<div class="row">

			<div class="col-12 table-responsive">

            	<table id="table-receipt" class="table table-stripted table-valign-middle">

            		<head>
            			<tr class="text-center">
            				<th style="width: 15%">Kode</th>
            				<th style="width: 30%">Barang</th>
            				<th style="width: 15%">Qty</th>
            			</tr>
            		</head>

            		<tbody>

            			<?php
            			$arrayTemplate = array();
            			foreach ($template as $row) :
            				$arrayTemplate['t' . $row->id] = $row->template_name;
            			endforeach;

            			$last = null;
            			$detail = json_decode($move_item['detail'], TRUE);
            			foreach ($item as $row) :

            				for ($i = 0; $i < count($detail); $i++) :

            					$key_t = 't' . $detail[$i]['template_id'];

            					if ($last != $detail[$i]['template_id']) {
	            					if (isset($arrayTemplate[$key_t])) {

	            						echo '<tr>';
	            						echo '<td colspan="3"><b>' . $arrayTemplate[$key_t] . '</b></td>';
	            						echo '</tr>';
	            					}
	            				}

	            				if ($detail[$i]['item_id'] == $row->id) {

	            					echo '<tr>';
	            					echo '<td class="text-center">' . $row->code . '</td>';
	            					echo '<td>' . $row->name . '</td>';
	            					echo '<td class="text-center">' . $detail[$i]['qty'] . '</td>';
	            					echo '</tr>';

	            				}
	            				$last = $detail[$i]['template_id'];
	            			endfor;

            			endforeach;
            			?>

            		</tbody>

            	</table>

			</div>
			<!-- /.col -->

		</div>
		<!-- /.row -->

		<div class="row no-print">
			<div class="col-12">

				<div class="btn-group float-right">
					<?php 
			        if ($this->session->userdata('p_move_item_delete') == 1 AND date('H:i:s') < $closingDate) { ?>
					<a href="javascript:void(0)" id="btn-delete" class="btn btn-danger">Hapus</a>
					<?php } ?>
				</div>
			</div>
		</div>

	</div>
	<!-- /.invoice -->

	<script>

		$(document).ready( function () {

			$('#btn-delete').on('click', function () {

				Swal.fire({
	                title: 'Hapus data?',
	                //text: "You won't be able to revert this!",
	                icon: 'warning',
	                showCancelButton: true,
	                confirmButtonColor: '#dc3545',
	                cancelButtonColor: '#6c757d',
	                confirmButtonText: 'Submit!'
	                }).then((result) => {

	                if (result.isConfirmed) {

	                	$.ajax({
							url: "<?=site_url('storage/delete_move_item');?>",
							data: {
								id: "<?php echo $move_item['id'];?>"
							},
							type: "post",
							success: function(response) {

								 if (response == 'success') {

			                        Swal.fire({
			                            position: 'center',
			                            icon: 'success',
			                            title: 'Data berhasil dihapus',
			                            showConfirmButton: false,
			                            timer: 1500
			                        });

			                        setTimeout(function(){

			                        	location.href = "<?=site_url('storage/move_item');?>";	

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

		});

	</script>