
        <?php
        
		foreach ($template as $row) :
            $total = 0;
			$detail = json_decode($row->detail, TRUE);
        ?>

        <div class="card">

            <div class="card-header">
                <h3 class="card-title"><?php echo $row->brand;?></h3>

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

                <table class="table m-0">

                    <thead>
                        <tr class="text-center">
                            <th rowspan="2" valign="middle">Item</th>
                            <th rowspan="2" valign="middle">Qty</th>
                            <th colspan="2">HPP</th>
                            <th colspan="2">Harga Jual</th>
                        </tr>
                        <tr class="text-center">
                            <th>Satuan</th>
                            <th>Subtotal</th>
                            <th>Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                <?php

				$price = 0;
                $subtotal = 0;
                $hpp = 0;
                $subtotalHpp = 0;
                $totalHpp = 0;
                $margin = 0;

                foreach ($detail as $key => $x) :

				if (isset($arrayItem[$key])) {
					$itemName = $arrayItem[$key];
				}

				if (isset($detailPrice[$key])) {
					$price = $detailPrice[$key];
					$subtotal = $price * $x;
                }
					
                if (isset($arrayHpp[$key])) {
                    $hpp = $arrayHpp[$key];
                    $subtotalHpp = $hpp * $x;
                } else {
                    $hpp = 0;
                    $subtotalHpp = 0;
                }

                $total += $subtotal;
                $totalHpp += $subtotalHpp;

                ?>
                    <tr>
                        <td><?php echo $itemName;?></td>
                        <td class="text-center"><?php echo $x;?></td>
                        <td class="text-center"><?php echo number_format($hpp);?></td>
                        <td class="text-center"><?php echo number_format($subtotalHpp);?></td>
                        <td class="text-center"><?php echo number_format($price);?></td>
                        <td class="text-center"><?php echo number_format($subtotal);?></td>
                    </tr>

                <?php endforeach;
                $margin = $total - $totalHpp;
                ?>

                    <tfoot>
                        <tr>
                            <th class="pl-4">Margin</th>
                            <th class="text-center"><?php echo number_format($margin);?></th>
                            <th class="text-center">HPP Perpaket</th>
                            <th class="text-center"><?php echo number_format($totalHpp);?></th>
                            <th class="text-center">Harga Jual Perpaket</th>
                            <th class="text-center"><?php echo number_format($total);?></th>
                        </tr>
                    </tfoot>

                </table>

            </div>

        </div>

        <?php endforeach; ?>