
    <style>
        .dt-buttons {
            padding: 5px;
        }
        .dataTables_info {
            padding: 5px;
        }
    </style>
    
	<div class="row">

		<div class="col-md-12">

            <?php if ($stock_now > 0) { ?>

            <div class="row">

                <div class="col-md-12">

                    <div class="card card-success">

                        <div class="card-header">

                            <h3 class="card-title">Sudah closing!</h3>

                        </div>

                    </div>

                </div>

            </div>

            <?php } ?>

			<div class="card card-primary">

				<div class="card-header">

					<h3 class="card-title">Update Stock</h3>

					<div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

				</div>
				<!-- /div.card-header -->

                <form id="form-update-stock" method="post">

				<div class="card-body table-responsive p-0" style="max-height: 550px;">

					<table class="table table-striped table-valign-middle table-head-fixed text-nowrap m-0">

						<thead>
							<tr class="text-center">
								<th>Kode</th>
								<th>Barang</th>
								<th>Stock Awal</th>
								<th>In</th>
								<th>Out</th>
                                <th>Pindah</th>
								<th>Hilang</th>
								<th>Rusak</th>
								<th>Stock Akhir</th>
								<th>Real</th>
								<th>Sisa</th>
								<th></th>
							</tr>
						</thead>

						<tbody>

                            <?php
                            $no = 0;
                            foreach ($result as $row) :

                                $key_i = 'i' . $row->id;

                                $stock_awal_v = 0;
                                $stock_real_v = 0;
                                $stock_rusak_v = 0;
                                $stock_hilang_v = 0;

                                if ($stock_awal != null) {
                                    if (isset($stock_awal[$key_i])) {
                                        $stock_awal_v = $stock_awal[$key_i];
                                    }
                                }

                                if ($stock_real != null) {
                                    if (isset($stock_real[$key_i])) {
                                        $stock_real_v = $stock_real[$key_i];
                                    }
                                }

                                if ($stock_rusak != null) {
                                    if (isset($stock_rusak[$key_i])) {
                                        $stock_rusak_v = $stock_rusak[$key_i];
                                    }
                                }

                                if ($stock_hilang != null) {
                                    if (isset($stock_hilang[$key_i])) {
                                        $stock_hilang_v = $stock_hilang[$key_i];
                                    }
                                }

                                echo '<tr>';

                                echo '<td>' . $row->code . '</td>';

                                echo '<td>
                                            ' . $row->name . '<input type="hidden" name="item_id[]" value="' . $row->id . '"
                                    </td>';

                                echo '<td>
                                        <span class="get-stock-awal get-stock-awal-' . $row->id . '" data-id="' . $row->id . '">' . $stock_awal_v . '</span>
                                            <input type="hidden" name="stock_awal[]" id="input-stock-awal-' . $row->id . '" value="' . $stock_awal_v . '">
                                    </td>';

                                echo '<td>
                                            <span class="get-stock-in get-stock-in-' . $row->id . '" data-id="' . $row->id . '">Loading</span>
                                            <input type="hidden" name="stock_in[]" id="input-stock-in-' . $row->id . '">
                                    </td>';

                                echo '<td>
                                            <span class="get-stock-out get-stock-out-' . $row->id . '" data-id="' . $row->id . '">Loading</span>
                                            <input type="hidden" name="stock_out[]" id="input-stock-out-' . $row->id . '">
                                    </td>';

                                echo '<td>
                                            <span class="get-stock-move get-stock-move-' . $row->id . '" data-id="' . $row->id . '">Loading</span>
                                            <input type="hidden" name="stock_move[]" id="input-stock-move-' . $row->id . '">
                                    </td>';

                                echo '<td>
                                            <input type="number" 
                                                   name="stock_hilang[]" 
                                                   class="form-control get-stock-hilang get-stock-hilang-' . $row->id . '" 
                                                   data-id="' . $row->id . '"
                                                   value="' . $stock_hilang_v . '" 
                                                   min="0" 
                                                   step="1" 
                                                   onchange="count_stock_akhir()" 
                                                   id="input-stock-hilang-' . $row->id . '">
                                    </td>';

                                echo '<td>
                                            <input type="number" 
                                                   name="stock_rusak[]" 
                                                   class="form-control get-stock-rusak get-stock-rusak-' . $row->id . '" 
                                                   data-id="' . $row->id . '"
                                                   value="' . $stock_rusak_v . '" 
                                                   min="0" 
                                                   step="1" 
                                                   onchange="count_stock_akhir()" 
                                                   id="input-stock-rusak-' . $row->id . '">
                                    </td>';

                                echo '<td>
                                            <span class="get-stock-akhir get-stock-akhir-' . $row->id . '" data-id="' . $row->id . '">0</span>
                                            <input type="hidden" name="stock_akhir[]" id="input-stock-akhir-' . $row->id . '">
                                    </td>';

                                echo '<td>
                                            <input type="number" 
                                                   name="stock_real[]" 
                                                   class="form-control get-stock-real get-stock-real-' . $row->id . '" 
                                                   data-id="' . $row->id . '"
                                                   value="' . $stock_real_v . '" 
                                                   min="0" 
                                                   step="1" 
                                                   onchange="count_stock_sisa(' . $row->id . ')" 
                                                   id="input-stock-real-' . $row->id . '">
                                    </td>';

                                echo '<td>
                                            <span class="get-stock-sisa get-stock-sisa-' . $row->id . '">0</span>
                                            <input type="hidden" 
                                                   name="stock_sisa[]" 
                                                   class="input-stock-sisa" 
                                                   data-id="' . $row->id . '" 
                                                   id="input-stock-sisa-' . $row->id . '">
                                    </td>';

                                echo '<td></td>';

                                echo '</tr>';

                                $no++;

                            endforeach;

                            ?>

						</tbody>

					</table>

				</div>
				<!-- /div.card-body -->

                <div class="card-footer">

                    <div class="btn-group">
                        <a href="<?=site_url('update_stock') . '?date=' . date('Y-m-d', strtotime('-1 days', strtotime($date)));?>" class="btn btn-warning">
                            <?php echo date('d M Y', strtotime('-1 days', strtotime($date)));?>
                        </a>
                        <a href="<?=site_url('update_stock') . '?date=' . date_format(date_create($date), 'Y-m-d');?>" class="btn btn-secondary disabled">
                            <?php echo date_format(date_create($date), 'd M Y');?>
                        </a>
                        <a href="<?=site_url('update_stock') . '?date=' . date('Y-m-d', strtotime('+1 days', strtotime($date)));?>" class="btn btn-warning">
                            <?php echo date('d M Y', strtotime('+1 days', strtotime($date)));?>
                        </a>
                    </div>

                    <?php if ($stock_now > 0) {
                        $disabled = 'disabled';
                    } else {
                        $disabled = '';
                    } ?>

                    <?php
                    if ($closingDate != '23:59:59') {
                    if ($date <= date('Y-m-d') AND date('H:i:s') > $closingDate) {
                        if ($this->session->userdata('p_update_stock_add') == 1 
                            || $this->session->userdata('p_update_stock_edit') == 0) { ?>
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                    <?php } }
                    } else {
                         if ($this->session->userdata('p_update_stock_add') == 1 
                            || $this->session->userdata('p_update_stock_edit') == 0) { ?>
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                    <?php }
                    } ?>
                </div>

                </form>

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div.row -->

    <input type="hidden" id="date_now" value="<?php echo date('Y-m-d', strtotime($date));?>">

	<script>

		$(document).ready( function () {

            get_stock_in();
            get_stock_out();
            get_stock_move();
		});

        function get_stock_in() {
            var ajaxRequest = $('.get-stock-in').length;
            var progress = 0;
            $('.get-stock-in').each( function () {

                var id = $(this).data('id');
                var date = $('#date_now').val();

                $.ajax({
                    url: "<?=site_url('update_stock/get_data_stock_in');?>",
                    type: "post",
                    data: {
                        id: id,
                        date: date
                    },
                    success: function (response) {
                        $('.get-stock-in-' + id).html(response);

                        $('#input-stock-in-' + id).val(response);

                        progress++;

                        if(progress == ajaxRequest){
                            count_stock_akhir();
                        }
                    }
                });

            });
        }

        function get_stock_out() {
            var ajaxRequest = $('.get-stock-out').length;
            var progress = 0;
            $('.get-stock-out').each( function () {

                var id = $(this).data('id');
                var date = $('#date_now').val();

                $.ajax({
                    url: "<?=site_url('update_stock/get_data_stock_out');?>",
                    type: "post",
                    data: {
                        id: id,
                        date: date
                    },
                    success: function (response) {
                        $('.get-stock-out-' + id).html(response);

                        $('#input-stock-out-' + id).val(response);

                        progress++;
            
                        if(progress == ajaxRequest){
                            count_stock_akhir();
                        }
                    }
                });

            });
        }

        function get_stock_move() {
            var ajaxRequest = $('.get-stock-move').length;
            var progress = 0;
            var date = $('#date_now').val();
            $('.get-stock-move').each( function () {

                var id = $(this).data('id');

                $.ajax({
                    url: "<?=site_url('update_stock/get_data_stock_move');?>",
                    type: "post",
                    data: {
                        id: id,
                        date: date
                    },
                    success: function (response) {
                        $('.get-stock-move-' + id).html(response);

                        $('#input-stock-move-' + id).val(response);

                        progress++;
                        if(progress == ajaxRequest){
                            count_stock_akhir();
                        }
                    }
                });

            });
        }

        function count_stock_akhir() {

            $('.get-stock-akhir').each( function () {

                var id = $(this).data('id');

                var awal = +$('.get-stock-awal-' + id).text();
                var stock_in = +$('.get-stock-in-' + id).text();
                var stock_out = +$('.get-stock-out-' + id).text();
                var stock_move = +$('.get-stock-move-' + id).text();

                var hilang = +$('.get-stock-hilang-' + id).val();
                var rusak = +$('.get-stock-rusak-' + id).val();

                var akhir = awal + stock_in - stock_out - stock_move - hilang - rusak;

                $('.get-stock-akhir-' + id).text(akhir);

                $('#input-stock-akhir-' + id).val(akhir);

                count_stock_sisa(id);
            });
        }

        function count_stock_sisa(id) {
            var real = +$('.get-stock-real-' + id).val();
            var akhir = +$('.get-stock-akhir-' + id).text();

            var sisa = real - akhir;

            $('.get-stock-sisa-' + id).text(sisa);
            $('#input-stock-sisa-' + id).val(sisa);
        }

        $("#form-update-stock").submit( function (e) {
            e.preventDefault();

            var err = 0;

            $('.input-stock-sisa').each( function () {
                var id = $(this).data('id');
                var value = $('#input-stock-sisa-' + id).val();

                if (parseInt(value) < 0) {
                    err++;
                }
            });

            if (err < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Sisa tidak boleh kurang dari nol!',
                });
            } else {

                var count_data = "<?php echo $stock_now;?>";
                var date = $('#date_now').val();

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
                            url: "<?=site_url('update_stock/save_data');?>",
                            data: $(this).serialize() + '&count_data=' + count_data + '&date=' + date,
                            type: $(this).attr("method"),
                            beforeSend: function () {
                                $('#modal-overlay').modal('show');
                            },
                            success: function(response) {

                                if (response == 'success') {

                                    setTimeout( function () {
                                        $('#modal-overlay').modal('hide');
                                    }, 500);

                                    setTimeout( function () {
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'success',
                                            title: 'Data berhasil disimpan',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }, 1000);

                                     setTimeout(function(){
                                        location.href = "<?=site_url('update_stock') . '?date=' . $date;?>";
                                    }, 2500);
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

            }

        });

	</script>









