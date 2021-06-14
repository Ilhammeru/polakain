

	<style>
		.dt-buttons {
			padding: 4px;
		}
	</style>

	<?php

	if ($count_data > 0) {?>

		<div class="row">

			<div class="col-md-12">

				<div class="card card-success">

					<div class="card-header">

						<h3 class="card-title">Sudah closing!</h3>

					</div>

				</div>

			</div>

		</div>

	<?php } 
	
	if ($stock_count == 0) { ?>

		<div class="row">

			<div class="col-md-12">

				<div class="card card-warning">

					<div class="card-header">

						<h3 class="card-title">Closing persediaan terlebih dahulu!</h3>

					</div>

				</div>

			</div>

		</div>
		
	<?php } ?>

	<form id="form-buku-besar" method="post">

	<?php

	$i = 0;

	$jumlah = 0;
	$harga 	= 0;
	$total 	= 0;

	$countArray = 0;
	$kesimpulan = array();

	$total_hpp_penjualan = 0;
	$total_barang_hilang = 0;
	$total_barang_rusak = 0;

	foreach ($item as $row) :

		foreach ($data as $list) :

			if ($row->id == $list['item_id']) {

				if ($i == 0) {
					$x = $row->id;
					$countArray += 1;

					echo '<div class="card">';

					echo '<div class="card-header border-0">';
					echo '<h3 class="card-title">' . $row->name . '</h3>'; 

					echo '<div class="card-tools">
		                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
		                            <i class="fas fa-minus"></i>
		                        </button>
		                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
		                            <i class="fas fa-expand"></i>
		                        </button>
		                    </div>';

					echo '</div>';

					echo '<div class="card-body table-responsive p-0">';

					echo '<table class="table table-bordered table-valign-middle text-center m-0" style="wdith: 100%">';
					echo '<tr class="text-center">';
					echo '<th rowspan="2">Tanggal</th>';
					echo '<th rowspan="2">Keterangan</th>';
					echo '<th colspan="3">Masuk</th>';
					echo '<th colspan="3">Keluar</th>';
					echo '<th colspan="3">Sisa</th>';
					echo '</tr>';
					echo '<tr class="text-center">';
					echo '<th>Jumlah</th>';
					echo '<th>Harga</th>';
					echo '<th>Total</th>';
					echo '<th>Jumlah</th>';
					echo '<th>Harga</th>';
					echo '<th>Total</th>';
					echo '<th>Jumlah</th>';
					echo '<th>Harga</th>';
					echo '<th>Total</th>';
					echo '</tr>';

					if ($basic == 0) {

						echo '<tr>';
						echo '<td>' . date('d M Y') . '</td>';
						echo '<td>Stock Awal</td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td></td>';
						echo '<td>0</td>';
						echo '<td>0</td>';
						echo '<td>0</td>';
						echo '</tr>';

					}

				}

				if ($x != $list['item_id']) {

					$countArray += 1;

					echo '<div class="card">';
					echo '<div class="card-header border-0">';
					echo '<h3 class="card-title">' . $row->name . '</h3>'; 
					
					echo '<div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>';

					echo '</div>';

					echo '<div class="card-body table-responsive p-0">';

					echo '<table class="table table-bordered table-valign-middle text-center m-0" style="wdith: 100%">';
					echo '<tr class="text-center">';
					echo '<th rowspan="2">Tanggal</th>';
					echo '<th rowspan="2">Keterangan</th>';
					echo '<th colspan="3">Masuk</th>';
					echo '<th colspan="3">Keluar</th>';
					echo '<th colspan="3">Sisa</th>';
					echo '</tr>';
					echo '<tr class="text-center">';
					echo '<th>Jumlah</th>';
					echo '<th>Harga</th>';
					echo '<th>Total</th>';
					echo '<th>Jumlah</th>';
					echo '<th>Harga</th>';
					echo '<th>Total</th>';
					echo '<th>Jumlah</th>';
					echo '<th>Harga</th>';
					echo '<th>Total</th>';
					echo '</tr>';

					$jumlah = 0;
					$harga 	= 0;
					$total 	= 0;

				}

				if ($x != $list['item_id'] && $basic == 0) {

					echo '<tr>';
					echo '<td>' . date('d M Y') . '</td>';
					echo '<td>Stock Awal</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td>0</td>';
					echo '<td>0</td>';
					echo '<td>0</td>';
					echo '</tr>';

				}

				if ($list['qty'] != 0) {

				echo '<tr>';
				echo '<td>' . $list['created'] . '</td>';
				echo '<td>' . $list['type'] . '</td>';

				}

				switch ($list['type']) :

					case 'Pembelian' :

							if ($list['qty'] != 0) {

							$jumlah = $jumlah + $list['qty'];
							$total 	= $total + $list['total_price'];
							$harga 	= round($total/$jumlah, 2);

							echo '<td>' . $list['qty'] . '</td>';
							echo '<td>' . number_format($list['price'], 2, '.', ',') . '</td>';
							echo '<td>' . number_format($list['total_price'], 2, '.', ',') . '</td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td>' . $jumlah . '</td>';
							echo '<td>' . number_format($harga, 2, '.', ',') . '</td>';
							echo '<td>' . number_format($total, 2, '.', ',') . '<input type="hidden" 
										 name="i' . $list['item_id'] . '[]" 
										 value="in,x,' . $list['qty'] . ',x,' . $list['price'] . ',x,' . $list['total_price'] . ',x,result,x,' . $jumlah . ',x,' . $harga . ',x,' . $total . '">
									</td>';

							$kesimpulan['i' . $list['item_id']] = array($jumlah, $harga, $total);

							}

						break;

					case 'Penjualan' :

							$jumlah = $jumlah - $list['qty'];

							if ($jumlah == 0) {
								$harga_terakhir = $harga;
								$total_terakhir = $total;
								$harga = 0;
								$total 	= 0;
							} else {

								$harga_terakhir = $harga;
								$total_terakhir = $harga_terakhir * $list['qty'];

								$total = $total - $total_terakhir;
								$harga = round($total/$jumlah, 2);

							}
							echo '<td></td>';

							echo '<td></td>';
							echo '<td></td>';
							echo '<td>' . $list['qty'] . '</td>';
							echo '<td>' . number_format($harga_terakhir, 2, '.', ',') . '</td>';
							echo '<td>' . number_format($total_terakhir, 2, '.', ',') . '</td>';
							echo '<td>' . $jumlah . '</td>';
							echo '<td>' . number_format($harga, 2, '.', ',') . '</td>';
							echo '<td>' . number_format($total, 2, '.', ',') . '<input type="hidden" 
										 name="i' . $list['item_id'] . '[]" 
										 value="out,x,' . $list['qty'] . ',x,' . $harga_terakhir . ',x,' . $total_terakhir . ',x,result,x,' . $jumlah . ',x,' . $harga . ',x,' . $total . '">
										 </td>';

							$total_hpp_penjualan += $total_terakhir;
							$kesimpulan['i' . $list['item_id']] = array($jumlah, $harga, $total);

						break;

					case 'Kerusakan' :

							$jumlah = $jumlah - $list['qty'];

							if ($jumlah == 0) {
								$total 	= 0;
								$harga 	= 0;
							} else {

								$harga_terakhir = $harga;
								$total_terakhir = $harga_terakhir * $list['qty'];

								$total = $total - $total_terakhir;
								$harga = round($total/$jumlah, 2);

							}

							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td>' . $list['qty'] . '</td>';
							echo '<td>' . number_format($harga_terakhir, 2, '.', ',') . '</td>';
							echo '<td>' . number_format($total_terakhir, 2, '.', ',') . '</td>';
							echo '<td>' . $jumlah . '</td>';
							echo '<td>' . number_format($harga, 2, '.', ',') . '</td>';
							echo '<td>' . number_format($total, 2, '.', ',') . '<input type="hidden" 
										 name="i' . $list['item_id'] . '[]" 
										 value="dmg,x,' . $list['qty'] . ',x,' . $harga_terakhir . ',x,' . $total_terakhir . ',x,result,x,' . $jumlah . ',x,' . $harga . ',x,' . $total . '">
										 </td>';

							$total_barang_rusak += $total_terakhir;

							$kesimpulan['i' . $list['item_id']] = array($jumlah, $harga, $total);

						break;

					case 'Kehilangan' :

							$jumlah = $jumlah - $list['qty'];

							if ($jumlah == 0) {
								$total 	= 0;
								$harga 	= 0;
							} else {

								$harga_terakhir = $harga;
								$total_terakhir = $harga_terakhir * $list['qty'];

								$total = $total - $total_terakhir;
								$harga = round($total/$jumlah, 2);

							}

							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td>' . $list['qty'] . '</td>';
							echo '<td>' . number_format($harga_terakhir, 2, '.', ',') . '</td>';
							echo '<td>' . number_format($total_terakhir, 2, '.', ',') . '</td>';
							echo '<td>' . $jumlah . '</td>';
							echo '<td>' . number_format($harga, 2, '.', ',') . '</td>';
							echo '<td>' . number_format($total, 2, '.', ',') . '<input type="hidden" 
										 name="i' . $list['item_id'] . '[]" 
										 value="lost,x,' . $list['qty'] . ',x,' . $harga_terakhir . ',x,' . $total_terakhir . ',x,result,x,' . $jumlah . ',x,' . $harga . ',x,' . $total . '">
										 </td>';

							$total_barang_hilang += $total_terakhir;

							$kesimpulan['i' . $list['item_id']] = array($jumlah, $harga, $total);

						break;

					case 'Kelebihan' :

							$jumlah = $jumlah + $list['qty'];

							if ($jumlah == 0) {
								$total 	= 0;
								$harga 	= 0;
							} else {

								$harga_terakhir = $harga;
								$total_terakhir = $harga_terakhir * $list['qty'];

								$total = $total + $total_terakhir;
								$harga = round($total/$jumlah, 2);

							}
							echo '<td>' . $list['qty'] . '</td>';
							echo '<td>' . number_format($harga_terakhir, 2, '.', ',') . '</td>';
							echo '<td>' . number_format($total_terakhir, 2, '.', ',') . '</td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td>' . $jumlah . '</td>';
							echo '<td>' . number_format($harga, 2, '.', ',') . '</td>';
							echo '<td>' . number_format($total, 2, '.', ',') . '<input type="hidden" 
										 name="i' . $list['item_id'] . '[]" 
										 value="plus,x,' . $list['qty'] . ',x,' . $harga_terakhir . ',x,' . $total_terakhir . ',x,result,x,' . $jumlah . ',x,' . $harga . ',x,' . $total . '">
										 </td>';

							$kesimpulan['i' . $list['item_id']] = array($jumlah, $harga, $total);

						break;

					// case 'Pindah' :

					// 		$jumlah = $jumlah - $list['qty'];

					// 		if ($jumlah == 0) {
					// 			$total 	= 0;
					// 			$harga 	= 0;
					// 		} else {

					// 			$harga_terakhir = $harga;
					// 			$total_terakhir = $harga_terakhir * $list['qty'];

					// 			$total = $total - $total_terakhir;
					// 			$harga = round($total/$jumlah, 2);

					// 		}

					// 		echo '<td></td>';
					// 		echo '<td></td>';
					// 		echo '<td></td>';
					// 		echo '<td>' . $list['qty'] . '</td>';
					// 		echo '<td>' . number_format($harga_terakhir, 2, '.', ',') . '</td>';
					// 		echo '<td>' . number_format($total_terakhir, 2, '.', ',') . '</td>';
					// 		echo '<td>' . $jumlah . '</td>';
					// 		echo '<td>' . number_format($harga, 2, '.', ',') . '</td>';
					// 		echo '<td>' . number_format($total, 2, '.', ',') . '<input type="hidden" 
					// 					 name="i' . $list['item_id'] . '[]" 
					// 					 value="out,x,' . $list['qty'] . ',x,' . $harga_terakhir . ',x,' . $total_terakhir . ',x,result,x,' . $jumlah . ',x,' . $harga . ',x,' . $total . '">
					// 					 </td>';

					// 	break;

					default :

							$jumlah = $list['qty'];
							$harga  = $list['price'];
							$total  = $list['total_price'];

							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td></td>';
							echo '<td>' . $list['qty'] . '</td>';
							echo '<td>' . number_format($list['price'], 2, '.', ',') . '</td>';
							echo '<td>' . number_format($list['total_price'], 2, '.', ',') . '<input type="hidden" 
										 name="i' . $list['item_id'] . '[]" 
										 value="basic,x,' . $list['qty'] . ',x,' . $list['price'] . ',x,' . $list['total_price'] . ',x,result,x,' . $jumlah . ',x,' . $harga . ',x,' . $total . '">
										 </td>';

							$kesimpulan['i' . $list['item_id']] = array($jumlah, $harga, $total);

						break;

				endswitch;

				$i = 1;
				$x = $row->id;

				if ($list['qty'] != 0) {
				echo '</tr>';
				}
			}

		endforeach;

		echo '</table>';
		echo '</div>'; /* card-body */


		echo '</div>'; /* card */

	endforeach;

	?>

	<div class="row">
		<div class="col-md-12">

			<a href="<?=site_url('buku_besar') . '?date=' . date('Y-m-d', strtotime('-1 days', strtotime($date)));?>" class="btn btn-warning">
				<?php echo date('d M Y', strtotime('-1 days', strtotime($date)));?>
			</a>
			<a>
				<input type="text" id="date-bukubesar" class="single-datepicker btn btn-secondary mr-2 ml-2">
			</a>
			<a href="<?=site_url('buku_besar') . '?date=' . date('Y-m-d', strtotime('+1 days', strtotime($date)));?>" class="btn btn-warning">
				<?php echo date('d M Y', strtotime('+1 days', strtotime($date)));?>
			</a>

			<?php 

			if ($closingDate == '23:59:59') {
				if ($this->session->userdata('p_buku_besar_approval') == 1) { 
					if ($stock_count != 0) { ?>
					<button type="submit" class="btn btn-primary float-right">Submit</button>
					<?php } 
				}
			} else {
			if ($date <= date('Y-m-d') AND date('H:i:s') > $closingDate) {
				if ($this->session->userdata('p_buku_besar_approval') == 1) {
					if ($stock_count != 0) { ?>
					<button type="submit" class="btn btn-primary float-right">Submit</button>
					<?php } 
				} }
			} ?>
		</div>
	</div>

	<br>

	<div class="row">

		<div class="col-md-6">

			<div class="card card-primary">

				<div class="card-header">
					<h3 class="card-title">Rincian Persediaan Barang</h3>

					<div class="card-tools">
		                <button type="button" class="btn btn-tool" data-card-widget="collapse">
		                    <i class="fas fa-minus"></i>
		                </button>
		                <button type="button" class="btn btn-tool" data-card-widget="maximize">
		                    <i class="fas fa-expand"></i>
		                </button>
		            </div>

				</div>

				<div class="card-body p-0">

					<table id="table-kesimpulan" class="table table-striped m-0" style="width: 100%">
						<thead>
							<tr class="text-center">
								<th>Barang</th>
								<th>Qty</th>
								<th>Harga Jual</th>
								<th>HPP</th>
								<th>Total</th>
							</tr>
						</thead>

						<tbody>

							<?php 
							$total = 0;
							foreach ($item as $row) :

								$key = 'i' . $row->id;

								if (isset($salePrice[$key])) {
									$hargaJual = $salePrice[$key];
								} else {
									$hargaJual = 0;
								}

								if (isset($kesimpulan[$key])) {

									echo '<tr>';
									echo '<td>' . $row->name . '</td>';
									echo '<td class="text-center">' . $kesimpulan[$key][0] . '</td>';
									echo '<td class="text-center">' . number_format($hargaJual) . '</td>';
									echo '<td class="text-center">' . number_format($kesimpulan[$key][1], 2, '.', ',') . '</td>';
									echo '<td class="text-right">' . number_format($kesimpulan[$key][2], 2, '.', ',') . '</td>';
									echo '</tr>';

									$total += $kesimpulan[$key][2];

								}

							endforeach; ?>

						</tbody>

						<tfoot>
							<tr>
								<td>Total</td>
								<td></td>
								<td></td>
								<td></td>
								<td class="text-right pr-4"><?php echo number_format($total, 2, '.', ',');?></td>
							</tr>
						</tfoot>

					</table>

				</div>

			</div>

		</div>
		<!-- /div.col -->

		<div class="col-md-6">
			
			<div class="card card-info">

				<div class="card-header">
					<h3 class="card-title">Kesimpulan <?php echo date_format(date_create($date), 'd M Y');?></h3>
				</div>

				<div class="card-body p-0">

					<table class="table m-0">
						<tr>
							<th>Total Pendapatan (Lunas)</th>
							<td class="text-right"><?php echo number_format($penjualanLunas, 2, '.', ',');?></td>
						</tr>
						<tr>
							<th>Total Pendapatan (Piutang)</th>
							<td class="text-right"><?php echo number_format($penjualanPiutang, 2, '.', ',');?></td>
						</tr>
						<tr>
							<th>Total Persediaan Barang (<?php echo $category;?>)</th>
							<td class="text-right"><?php echo number_format($total, 2, '.', ',');?></td>
						</tr>
						<tr>
							<th>Total HPP Penjualan (<?php echo $category;?>)</th>
							<td class="text-right"><?php echo number_format($total_hpp_penjualan, 2, '.', ',');?></td>
						</tr>
						<tr>
							<th>Total Kerugian Barang Hilang (<?php echo $category;?>)</th>
							<td class="text-right"><?php echo number_format($total_barang_hilang, 2, '.', ',');?></td>
						</tr>
						<tr>
							<th>Total Kerugian Barang Rusak (<?php echo $category;?>)</th>
							<td class="text-right"><?php echo number_format($total_barang_rusak, 2, '.', ',');?></td>
						</tr>
					</table>

				</div>

			</div>

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

	</form>

	<script>

		$(document).ready( function () {

			var date_select = "<?php echo $date;?>";

			single_datepicker2(date_select);

			$('#date-bukubesar').on('change', function () {
				var date = $(this).val();
				var category = "<?php echo $category;?>";
				var stock_count = "<?php echo $stock_count;?>";

				location.href = "<?=site_url('buku_besar');?>?date=" + date;

			});

			var get_date = "<?php echo date('d M Y', strtotime($this->input->get('date')));?>";
			var category = "<?php echo $category;?>";
			var titleExport = "Persediaan Barang Datang (" + category + ")" + get_date;

			var tableKesimpulan  = $('#table-kesimpulan').DataTable({

                // Buttons          
                buttons: [  
                            {
                                extend: 'copy',
                                footer: true
                            },
                            {
                                extend: 'excel',
                                title: titleExport,
                                footer: true
                            }
                            // {
                            //     extend: 'pdf',
                            //     title: titleExport,
                            //     footer: true
                            // },
                            // {
                            //     extend: 'print',
                            //     title: titleExport,
                            //     footer: true
                            // }
                        ],
                dom: 'Bfrtip',
                paging: false,
                ordering: false,
                info: false,

                // // Responsive
                // responsive: {

                //     details: {

                //         display: $.fn.dataTable.Responsive.display.modal( {

                //             header: function ( row ) {

                //                 var data = row.data();
                //                 return 'Details for ' + data[0];

                //             }

                //         }),

                //         renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                //             tableClass: 'table'
                //         })
                //     }
                // }

			});

		});

		$("#form-buku-besar").submit( function (e) {
        e.preventDefault();

        	var category 	= "<?php echo $category;?>";
        	var date 		= "<?php echo $date;?>";
        	var count_data  = "<?php echo $count_data;?>";

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
	                    url: "<?=site_url('buku_besar/save_data');?>",
	                    data: $(this).serialize() + '&category=' + category + '&date=' + date + '&count_data=' + count_data,
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
	                            	location.href = "<?=site_url('buku_besar') . '?date=' . $date;?>";
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

	</script>
