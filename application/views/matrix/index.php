
    <div class="row">

        <div class="col-md-12">
        
            <div class="card">

                <div class="card-body">

                    <div class="form-inline form-sm-left">

                        <div class="input-group p-1">

                            <select id="select-matrix" class="form-control select2">
                                <option value="" selected disabled>Pilih Analisa</option>
                                <?php
                                foreach ($menu as $key => $row) :
                                    echo '<option value="' . $key . '">' . $row . '</option>';
                                endforeach;
                                ?>
                            </select>

                        </div>

                        <div classs="input-group">
							<input type="text" id="date_input" class="form-control daterangepicker2" style="position:static!important;margin-top:0; display:none" name="date_input" placeholder="Pilih tanggal" readonly>
                        </div>

                    </div> 

                </div>
                    
            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-12" id="display-matrix"></div>

    </div>


    <script>

        $(document).ready( function () {

            $('#select-matrix').on('change', function () {

                var value = $(this).val();

                if (value == 1) {

                    $('#date_input').hide();

                    $.ajax({
                        url: "<?=site_url('matrix/hpp_hj');?>",
                        success: function (response) {
                            $('#display-matrix').html(response);
                        }
                    });
                    
                } else if (value == 2) {

                    $('#date_input').val('');
                    $('#display-matrix').html('');
                    $('#date_input').show();

                }

            });

            $('#date_input').on('change', function () {

                var value = $(this).val(); 
                var option = $('#select-matrix').val();

                if (option == 2) {

                    $.ajax({
                        url: "<?=site_url('matrix/get_total_penjualan');?>",
                        type: "post",
                        data: {
                            date: value
                        },
                        beforeSend: function() {
                            var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                            $('#display-matrix').html(loading);
                        },
                        success: function(response) {

                            if (response != null) {
                            $('#display-matrix').html(response);
                            }

                        }
                    });

                }

            });

        });

    </script>