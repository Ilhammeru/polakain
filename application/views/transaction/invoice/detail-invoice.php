	
	<!-- Main content -->
	<div class="invoice p-3 mb-3">

		<div class="ribbon-wrapper ribbon-lg">
			<?php 
			if ($invoice['payment_status'] == 2) {
			?>
			<div class="ribbon bg-success">
				LUNAS
			</div>
			<?php
			} elseif ($invoice['payment_status'] == 3 OR $invoice['payment_status'] == NULL) {
			?>
			<div class="ribbon bg-danger">
				HUTANG
			</div>
			<?php } ?>
		</div>

		<!-- title row -->
		<div class="row">

			<div class="col-12">
			<h4>
			<i class="fa fa-hashtag"></i> <?php echo $invoice['invoice_number'];?>
			<small><?php echo date_format(date_create($invoice['date_invoice']), 'd M Y');?></small>
			</h4>
			</div>
			<!-- /.col -->

		</div>
		<!-- info row -->

		<div class="row invoice-info">

			<div class="col-sm-4 invoice-col">
				Dari
				<address>
					<strong><?php echo $invoice['vendor_name'];?></strong><br>
					<?php echo $invoice['vendor_address'];?>
				</address>
			</div>
			<!-- /.col -->

			<div class="col-sm-4 invoice-col">
			</div>
			<!-- /.col -->

			<div class="col-sm-4 invoice-col">
			</div>
			<!-- /.col -->

		</div>
		<!-- /.row -->

		<!-- Table row -->
		<div class="row">

			<div class="col-12 table-responsive">
				<table id="table-invoice" class="table table-striped table-valign-middle">
					<thead>
						<tr class="text-center">
							<th>Qty</th>
							<th>Barang</th>
							<th>Kode</th>
							<th>Harga</th>
							<th>Subtotal</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<!-- /.col -->

		</div>
		<!-- /.row -->

		<div class="row">
		<!-- accepted payments column -->
		
			<div class="col-6">
				<!-- <p class="lead">Payment Methods:</p>
				<img src="../../dist/img/credit/visa.png" alt="Visa">
				<img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
				<img src="../../dist/img/credit/american-express.png" alt="American Express">
				<img src="../../dist/img/credit/paypal2.png" alt="Paypal">

				<p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
				Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
				plugg
				dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
				</p> -->
			</div>
			<!-- /.col -->

			<div class="col-6">

				<!-- <p class="lead">Amount Due 2/22/2014</p> -->

				<div class="table-responsive">
					<table class="table table-valign-middle">
						<tr>
							<th style="width:50%">Subtotal</th>
							<td>Rp </td>
							<td class="text-right"><?php echo number_format($invoice['total_price'], '2', '.', ',');?></td>
						</tr>
						<tr>
							<th style="width:50%">Diskon</th>
							<td>Rp </td>
							<td class="text-right"><?php echo '-' . number_format($invoice['disc'], '2', '.', ',');?></td>
						</tr>
						<tr>
							<th>PPN (10%)</th>
							<td>Rp </td>
							<td class="text-right"><?php echo number_format($invoice['ppn'], '2', '.', ',');?></td>
						</tr>
						<?php if ($invoice['with_matoa_shipping'] == 0) { ?>
						<tr>
							<th>
								Ongkir
							</th>
							<td>Rp </td>
							<td class="text-right"><?php echo number_format($invoice['d_cost'], '2', '.', ',');?></td>
						</tr>
						<?php } ?>
						<tr>
							<th>Total</th>
							<td>Rp </td>
							<td class="text-right"><?php echo number_format($invoice['grand_total'], '2', '.', ',');?></td>
						</tr>

						<?php if ($invoice['with_matoa_shipping'] == 1) { ?>
						<tr class="bg-orange">
							<th>
								Titipan Pengiriman
							</th>
							<td>Rp </td>
							<td class="text-right"><?php echo number_format($invoice['d_cost'], '2', '.', ',');?></td>
						</tr>
						<?php } ?>
					</table>
				</div>

				<?php 
				if ($invoice['payment_date'] != '') { ?>
				<p class="lead">Dibayar tanggal:</p>
				<p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
					<?php echo date_format(date_create($invoice['payment_date']), 'd M Y H:i:s'); ?>
				</p>
				<?php } ?>

			</div>
			<!-- /.col -->

		</div>
		<!-- /.row -->

		<!-- this row will not appear when printing --><!-- 
		<div class="row no-print">
			<div class="col-12">
				<a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
				<button type="button" class="btn btn-success float-right">
					<i class="far fa-credit-card"></i> Submit Payment
				</button>
				<button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
					<i class="fas fa-download"></i> Generate PDF
				</button>
			</div>
		</div> -->

	</div>
	<!-- /.invoice -->

	<script>

		$(document).ready( function () {

			var invoice_id = "<?php echo $invoice['invoice_id'];?>";
			var titleExport = "<?php echo 'Data Invoice Barang Datang ' . $invoice['invoice_number'];?>";

			$('#table-invoice').DataTable({

				// Data
				ajax: {
                    url: "<?=site_url('invoice/server_side_data_invoice_detail');?>/" + invoice_id,
                    type: "POST"
                },
                processing: true,
                paging: false,
                searching: false,
                info: false,
                ordering: false,
                 // Buttons          
                buttons: [ 
                            {
                                extend: 'copy'
                            },
                            {
                                extend: 'excel',
                                title: titleExport
                            },
                            {
                                extend: 'pdf',
                                title: titleExport
                            },
                            {
                                extend: 'print',
                                title: titleExport
                            },
                        ],
                dom: 'Bfrtip',

			});

		});

	</script>