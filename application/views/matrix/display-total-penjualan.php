
    <style>
        .card-body {
            max-height: 500px;
            overflow: auto;
        }
    </style>

    <div class="row">

        <div class="col-md-12">

            <div class="callout callout-info">
                <h5>Total Penjualan</h5>

                <p>Periode: <?php if ($date_1 == $date_2) { echo $date_1; } else { echo $date_1 . ' s/d ' . $date_2; } ?></p>
            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-6">

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">Total Penjualan Paket</h3>

                </div>

                <div class="card-body">

                    <table class="table table-sm table-borderless m-0 table-striped" style="width: 100%" id="table-1">

                        <thead>
                            <tr style="border-bottom:1px solid #e5e5e5">
                                <th style="width:60%; border-right:1px solid #e5e5e5" class="text-center">Template</th>
                                <th style="width:40%" class="text-center">Qty</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php 
                            $x = json_decode($templateData);
                            foreach ($x as $row) :
                                echo '<tr>';
                                echo '<td style="border-right:1px solid #e5e5e5">' . $row->template_name . '</td>';
                                echo '<td class="text-center">' . $row->template_qty . '</td>';
                                echo '</tr>';
                            endforeach; ?>

                        </tbody>
                        
                    </table>

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">Total Penjualan Item</h3>

                </div>

                <div class="card-body">

                    <table class="table table-sm table-borderless m-0 table-striped" style="width: 100%" id="table-2">

                        <thead>
                            <tr style="border-bottom:1px solid #e5e5e5">
                                <th style="width:60%; border-right:1px solid #e5e5e5" class="text-center">Item</th>
                                <th style="width:40%" class="text-center">Qty</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $y = json_decode($itemData);
                            foreach ($y as $row) :

                                echo '<tr>';
                                echo '<td style="border-right:1px solid #e5e5e5">' . $row->item_name . '</td>';
                                echo '<td class="text-center">' . $row->item_qty  . '</td>';
                                echo '</tr>';

                            endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <script>

        var titleExport = 'Total Penjualan';

        $('#table-1').DataTable({
            processing: true,
            info: false,
            paging: false,
            searching: false,
            scrollY: '450px',
            scrollCollapse: true,
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
                        ],
            dom: 'Bfrtip',
        });

        $('#table-2').DataTable({
            processing: true,
            info: false,
            paging: false,
            searching: false,
            scrollY: '450px',
            scrollCollapse: true,
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
                        ],
            dom: 'Bfrtip',
        });

    </script>