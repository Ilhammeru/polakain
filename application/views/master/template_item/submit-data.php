	
	<div class="row">

		<div class="col-md-3">

			<div class="col-md-12">

				<li class="list-group-item list-group-item-action">
					<input type="text" onchange="load_brand(this)" class="form-control" placeholder="Search">
				</li>

			</div>

			<div class="col-md-12 col-list table-responsive" style="margin-top: 15px">

				<div id="brand-list"></div>

			</div>

			<br>

			<div class="col-md-12">
				<a class="btn bg-purple btn-block" href="<?=site_url('template_item/print_template_list');?>" target="_blank">Print</a>
			</div>

		</div>
		<!-- /div.col -->

		<div class="col-md-6">

			<div class="card card-primary">

				<div class="card-header">
					<h3 class="card-title">Template <?php if($template != "") { echo $template['brand'] . '-' . $template['tipe'];} ?></h3>
					<button class="btn btn-sm btn-success float-right mr-2" id="btn-add-brand"><i class="fa fa-plus"></i></button>
					<?php if ($template != "") { ?>
						<a class="btn btn-sm btn-danger float-right mr-2" id="btn-delete-template" data-target="<?php echo $template['id'];?>"><i class="fa fa-trash"></i></a>
					<?php } ?>
				</div>

				<div class="card-body card-body-list table-responsive p-0">

					<div id="template-list"></div>

				</div>

			</div>
			<!-- /div.card -->

		</div>
		<!-- /div.col -->

		<div class="col-md-3 col-list table-responsive">

			<div id="item-list"></div>

		</div>
		<!-- /div.col -->

	</div>
	<!-- /div -->

	<script>

		var brand_id = "<?php echo $brand_id;?>";
		var brand_count = "<?php echo $brand_count;?>";

		$(document).ready( function () {

			if (brand_id != '') {

				load_item();

			}

			if (brand_count != 0) {

				load_brand();

			}

			load_template();

			function load_item() {

				var brandId = "<?php echo $brand_id;?>";

				$.ajax({
                    url: "<?=site_url('template_item/load_item');?>?brand_id=" + brandId,
                    type: 'get',
                    dataType: 'json',
                    beforeSend: function() {
                    	var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    	$('#item-list').html(loading);
                    },
                    success: function(response){

						$('#item-list').html('');

                    	var row = '<ul class="list-group">';

                        for (index in response.item) {

                        	var key = 'i' + response.item[index].id;

                        	if (response.template == null) {

                        		var link = "<?=site_url('template_item/add_item_to_template');?>?item_id=" + response.item[index].id + '&brand_id=' + brandId;

		                        row += '<a href="' + link + '" class="list-group-item list-group-item-action"><i class="fa fa-angle-double-left text-success"></i> ' + response.item[index].name + '</a>';

                        	} else if (response.template == "") {

                        		var link = "<?=site_url('template_item/add_item_to_template');?>?item_id=" + response.item[index].id + '&brand_id=' + brandId;

		                        row += '<a href="' + link + '" class="list-group-item list-group-item-action"><i class="fa fa-angle-double-left text-success"></i> ' + response.item[index].name + '</a>';

                        	} else {

	                        	if (key in response.template) {

	                        	} else {
	                    		
		                    		var link = "<?=site_url('template_item/add_item_to_template');?>?item_id=" + response.item[index].id + '&brand_id=' + brandId;

		                        	row += '<a href="' + link + '" class="list-group-item list-group-item-action"><i class="fa fa-angle-double-left text-success"></i> ' + response.item[index].name + '</a>';

		                        }

                        	}
		  
		                }

		                row += '</ul>';

		                $('#item-list').append(row);

                    }

                });

			}
			// End of load_item_list

			function load_template() {

				var brandId = "<?php echo $brand_id;?>";

				$.ajax({
                    url: "<?=site_url('template_item/load_template');?>",
                    type: 'get',
                    data: {
                    	brand_id : brandId
                    },
                    beforeSend: function() {
                    	var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                    	$('#template-list').html(loading);
                    },
                    success: function(response){

						$('#template-list').html('');

						if (response == 'error-null') {

							$('#template-list').append('<div class="p-4">Tidak terdapat data</div>');

						} else if (response == 'null') {

							$('#template-list').append('<div class="p-4">Tidak terdapat pengaturan</div>');

						} else {
							$('#template-list').append(response);
						}

                    }

                });

			}
			// End of load_template

			$(document).on('click', '#btn-add-brand', function () {
                $('div#modal-placehorder').load("<?=site_url('template_item/load_modal_add_brand');?>");
            });

            $(document).on('click', '#btn-delete-template', function () {

            	var value = $(this).attr('data-target');

            	Swal.fire({
                    title: 'Hapus template?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus!'
                    }).then((result) => {

                    if (result.isConfirmed) {

                        $.ajax({
                            url: "<?=site_url('template_item/delete_template');?>",
                            type: "post",
                            data: {
                                id: value
                            },
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

                                        location.href = "<?=site_url('template_item/submit_data');?>";

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

                    }

                });

            });

		});

		function load_brand(e) {

			var brandId = "<?php echo $brand_id;?>";

			var search;

			if (e == null) {
				search = 0;
			} else {
				search = e.value;
			}

			$.ajax({
                url: "<?=site_url('template_item/load_brand');?>",
                type: 'get',
                data:{
                    brand: search
                },
                dataType: 'json',
                beforeSend: function() {
                	var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                	$('#brand-list').html(loading);
                },
                success: function(response){

					$('#brand-list').html('');

					if (response != 'error-null') {

                    	var row = '<ul class="list-group">';

                        for (index in response.brand) {
                    		
                    		var link = "<?=site_url('template_item/submit_data');?>?brand_id=" + response.brand[index].id;

                    		var addClass = '';

                    		if (brandId == response.brand[index].id) {
                    			addClass = 'active';
   							}

                        	row += '<a href="' + link + '" class="list-group-item list-group-item-action ' + addClass + '">' + response.brand[index].brand + ' - ' + response.brand[index].tipe + '</a>';
		  
		                }

		                row += '</ul>';

		                $('#brand-list').append(row);

		            }
                }

            });

		}
		// End of load_brand

		function insert_qty(item_id) {

			var getval = $('#' + item_id).val();
			var brandId = "<?php echo $brand_id;?>";
			var getPrice = $('#price-' + item_id).text();

			var subtotal = getPrice.replace(',', '');
			subtotal = subtotal * getval;

			$.ajax({
                url: "<?=site_url('template_item/insert_qty');?>",
                type: 'get',
                data: {
                	item_id: item_id,
                	qty: getval,
                	brand_id: brandId
                },
                success: function(response){

                	if (response == 'success') {

						$('#subtotal-' + item_id).text(number_format_0(subtotal));

                        setTimeout(function(){
                            $('#' + item_id).addClass('bg-success');
						}, 500);
						
						setTimeout(function() {
							var total = 0;
							$('.subtotal').each( function () {
								subtotal = $(this).text();
								subtotal = subtotal.replace(',', '');
								total += +subtotal;
							});
							$('#total').text(number_format_0(total));
						}, 1000);

                        setTimeout(function(){
                            $('#' + item_id).removeClass('bg-success');
                        }, 5000);

	            	} 

                }

            });

		}
		// End of insert_qty

	</script>

