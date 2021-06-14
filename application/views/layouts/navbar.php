    
    <nav class="navbar navbar-expand-md fixed-top navbar-dark bg-dark">

        <img src="<?=base_url();?>assets/background/logo-ans.png" style="width: 40px; height: auto;">

        <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">

            <ul class="navbar-nav mr-auto">

                <li class="nav-item active">
                    <a class="nav-link" href="#">Dashboard <span class="sr-only">(current)</span></a>
                </li>

                <?php

                if ($this->session->userdata('p_user_report') == 1 ||
                    $this->session->userdata('p_role_report') == 1 ||
                    $this->session->userdata('p_vendor_report') == 1 ||
                    $this->session->userdata('p_warehouse_report') == 1 ||
                    $this->session->userdata('p_item_report') == 1 ||
                    $this->session->userdata('p_payment_method_report') == 1 ||
                    $this->session->userdata('p_sale_price_report') == 1 ||
                    $this->session->userdata('p_template_item') == 1 ||
                    $this->session->userdata('p_log_activity') == 1) { ?>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Master</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">

                        <?php

                        // if ($this->session->userdata('p_user_report') == 1 ||
                        //     $this->session->userdata('p_user_add') == 1 ||
                        //     $this->session->userdata('p_user_edit') == 1 ||
                        //     $this->session->userdata('p_user_delete') == 1) {
                        //     echo '<a class="dropdown-item" href="' . site_url('users/report_data') . '">User</a>';
                        // } 

                        // if ($this->session->userdata('p_role_report') == 1 ||
                        //     $this->session->userdata('p_role_add') == 1 ||
                        //     $this->session->userdata('p_role_edit') == 1 ||
                        //     $this->session->userdata('p_role_delete') == 1) {
                        //     echo '<a class="dropdown-item" href="' . site_url('role/report_data') . '">Hak Akses</a>';
                        // }

                        if ($this->session->userdata('p_vendor_report') == 1 ||
                            $this->session->userdata('p_vendor_add') == 1 ||
                            $this->session->userdata('p_vendor_edit') == 1 ||
                            $this->session->userdata('p_vendor_delete') == 1) {
                            echo '<a class="dropdown-item" href="' . site_url('vendor/report_data') . '">Vendor</a>';
                        }

                        if ($this->session->userdata('p_warehouse_report') == 1 ||
                            $this->session->userdata('p_warehouse_add') == 1 ||
                            $this->session->userdata('p_warehouse_edit') == 1 ||
                            $this->session->userdata('p_warehouse_delete') == 1) {
                            echo '<a class="dropdown-item" href="' . site_url('warehouse/report_data') . '">Gudang</a>';
                        }

                        // if ($this->session->userdata('p_item_report') == 1 ||
                        //     $this->session->userdata('p_item_add') == 1 ||
                        //     $this->session->userdata('p_item_edit') == 1 ||
                        //     $this->session->userdata('p_item_delete') == 1) {
                        //     echo '<a class="dropdown-item" href="' . site_url('item/report_data') . '">Barang</a>';
                        // }

                        if ($this->session->userdata('p_item_report') == 1 ||
                            $this->session->userdata('p_item_add') == 1 ||
                            $this->session->userdata('p_item_edit') == 1 ||
                            $this->session->userdata('p_item_delete') == 1) {
                            echo '<a class="dropdown-item" href="' . site_url('category') . '">Category & Color</a>';
                            echo '<a class="dropdown-item" href="' . site_url('item/report_data') . '">Barang</a>';
                        }

                        if ($this->session->userdata('p_payment_method_report') == 1 ||
                            $this->session->userdata('p_payment_method_add') == 1 ||
                            $this->session->userdata('p_payment_method_edit') == 1 ||
                            $this->session->userdata('p_payment_method_delete') == 1) {
                            echo '<a class="dropdown-item" href="' . site_url('payment_method/report_data') . '">Metode Pembayaran</a>';
                        }

                        if ($this->session->userdata('p_sale_price_edit') == 1 ||
                            $this->session->userdata('p_sale_price_report') == 1) {
                            echo '<a class="dropdown-item" href="' . site_url('sale_price/submit_data') . '">Harga Jual</a>';
                        }

                        if ($this->session->userdata('p_template_item') == 1) {
                            echo '<a class="dropdown-item" href="' . site_url('template_pola') . '">Template</a>';
                        }

                        // if ($this->session->userdata('p_log_activity') == 1) {
                        //     echo '<a class="dropdown-item" href="' . site_url('log_activity/report_data') . '">Aktifitas User</a>';
                        // }

                        ?>

                    </div>

                </li>

                <?php } ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Gudang</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">

                        <?php

                        if ($this->session->userdata('p_invoice_report') == 1 ||
                            $this->session->userdata('p_invoice_add') == 1 ||
                            $this->session->userdata('p_invoice_edit') == 1 ||
                            $this->session->userdata('p_invoice_delete') == 1) {

                            echo '<a class="dropdown-item" href="' . site_url('barang_datang') . '">Input Barang Datang</a>';
                        }

                        if ($this->session->userdata('p_invoice_report') == 1 ||
                            $this->session->userdata('p_invoice_add') == 1 ||
                            $this->session->userdata('p_invoice_edit') == 1 ||
                            $this->session->userdata('p_invoice_delete') == 1) {

                            echo '<a class="dropdown-item" href="' . site_url('invoice/report_data') . '">Data Barang Datang</a>';
                        }

                        if ($this->session->userdata('p_storage_report') == 1 ||
                            $this->session->userdata('p_storage_approval') == 1 ||
                            $this->session->userdata('p_storage_cancel') == 1) {

                            echo '<a class="dropdown-item" href="' . site_url('storage/packing_list') . '">Storage</a>';

                        }

                        if ($this->session->userdata('p_move_item_report') == 1 ||
                            $this->session->userdata('p_move_item_add') == 1 ||
                            $this->session->userdata('p_move_item_delete') == 1) {
                            
                            echo '<a class="dropdown-item" href="' . site_url('storage/move_item') . '">Pindah Barang</a>';
                        }
                        
                        ?>

                    </div>

                </li>

                 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Keuangan</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">

                        <?php

                        if ($this->session->userdata('p_payment_dp_report') == 1 ||
                            $this->session->userdata('p_payment_dp_add') == 1 ||
                            $this->session->userdata('p_payment_dp_delete') == 1) {

                            echo '<a class="dropdown-item" href="' . site_url('payment_dp/input_data') . '">Pembayaran Dimuka</a>';

                        }

                        if ($this->session->userdata('p_payment_report') == 1 ||
                            $this->session->userdata('p_payment_approval') == 1 ||
                            $this->session->userdata('p_payment_cancel') == 1) {
                            
                            echo '<a class="dropdown-item" href="' . site_url('payment/index') . '">Hutang Dagang</a>';

                        }

                        if ($this->session->userdata('p_approval_report') == 1 ||
                            $this->session->userdata('p_approval_submit') == 1 ||
                            $this->session->userdata('p_approval_cancel') == 1) {

                            echo '<a class="dropdown-item" href="' . site_url('acc/approval') . '">Approval</a>';

                        }

                        ?>

                    </div>

                </li>

                 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Penjualan</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">

                        <?php

                        if ($this->session->userdata('p_sale_add') == 1 ||
                            $this->session->userdata('p_sale_edit') == 1) {

                            echo '<a class="dropdown-item" href="' . site_url('sales') . '">Input Penjualan</a>';

                        }

                        if ($this->session->userdata('p_sale_add') == 1 ||
                            $this->session->userdata('p_sale_edit') == 1) {

                            echo '<a class="dropdown-item" href="' . site_url('sales/input_repeat') . '">Input Repeat</a>';

                        }

                        if ($this->session->userdata('p_sale_report') == 1 ||
                            $this->session->userdata('p_sale_delete') == 1) { 

                            echo '<a class="dropdown-item" href="' . site_url('sales/report_data') . '">Data Penjualan</a>';

                        }

                        ?>

                    </div>

                </li>

                 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Closing</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">

                        <?php
                        if ($this->session->userdata('p_update_stock_add') == 1 ||
                            $this->session->userdata('p_update_stock_edit') == 1) {

                            echo '<a class="dropdown-item" href="' . site_url('update_stock') . '?date=' . date('Y-m-d') . '">Update Stock</a>';

                        }

                        if ($this->session->userdata('p_buku_besar_report') == 1 ||
                            $this->session->userdata('p_buku_besar_approval') == 1) {

                            echo '<a class="dropdown-item" href="' . site_url('buku_besar') . '?date=' . date('Y-m-d') . '">Buku Besar</a>';
                            
                        }

                        ?>

                    </div>

                </li>
<!-- 
                <li class="nav-item">
                    <a class="nav-link" href="<?=site_url('matrix');?>">Matrix <span class="sr-only">(current)</span></a>
                </li> -->

                <!-- <li class="nav-item">
                    <a class="nav-link" href="<?=site_url('events');?>">Events <span class="sr-only">(current)</span></a>
                </li> -->

            </ul>
            <!-- /ul.navbar-nav -->

            <ul class="navbar-nav navbar-right">

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <?=$this->session->userdata('username');?>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?=site_url('users/form_change_password');?>"
                       class="nav-link"
                       data-toggle="tooltip" data-placement="bottom" title="<?=$this->session->userdata('username');?>">
                        <i class="fa fa-user"></i>
                        <span class="visible-xs">&nbsp;<?=$this->session->userdata('username');?></span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="javascript:void(0)" id="btn-signout"
                       class="nav-link"
                       data-toggle="tooltip" data-placement="bottom" title="Logout">
                        <i class="fa fa-power-off"></i>
                        <span class="visible-xs">&nbsp;Logout</span>
                    </a>
                </li>

            </ul>
            <!-- /ul.navbar-nav -->

        </div>
        <!-- /div.navbar-collaps -->

    </nav>
    <!-- /nav.navbar -->