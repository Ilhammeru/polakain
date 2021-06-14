	
    <link rel="stylesheet" href="<?=base_url();?>assets/bootstrap/css/bootstrap.min.css?v4.5.2">

    <script src="<?=base_url();?>assets/vendor/jquery/jquery-3.5.1.min.js?v3.5.1"></script>

    <script src="<?=base_url();?>assets/bootstrap/js/bootstrap.min.js?v4.5.2"></script>


    <div class="row">

    	<div class="col-md-2" style="text-align: center; margin-top: 2em;">

			<img src="<?=base_url() . 'assets/barcode/' . $item['code'] . '.png';?>" style="width: 150px"/>
			<p><?= $item['code']; ?></p>

		</div>

		<div class="col-md-4" style="padding-top: 15px">

			<p>
				<label><?php echo $item['name'];?></label>
			</p>
			<p>
				<label><?php echo $item['code'];?></label>
			</p>
			<p>
				<label><?php echo $item['category'];?></label>
			</p>
			<p>
				<label><?php echo $item['merk'];?></label>
			</p>

		</div>

	</div>
