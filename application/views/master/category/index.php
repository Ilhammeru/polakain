
    <style>
        html {
            --dim-gray: #5e5e5e;
        }
        .container {
            color: var(--dim-gray) !important;
        }
        .container {
            height: 600px;
        } 
        .b-default {
            border: 1px solid #eaeaea;
        }
        .content-wrapper {
            background-color: white;
        }
        .table th, 
        .table td {
            padding:  .25rem !important;
        }
        .table thead th,
        .table tbody td {
            border-bottom: 1px solid #eaeaea;
            border-right: 1px solid #eaeaea;
            border-left: 1px solid #eaeaea;
        }
        .table tbody {
            display: block;
            overflow: auto;
            height: 400px;
            width: 100%;
            /* border-bottom: 1px solid rgba(0,0,0,0.2); */
        }
        .table thead, .table tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;/* even columns width , fix width of table too*/
        }
        .table>:not(:last-child)>:last-child>* {
            border-bottom-color: transparent !important;
        }
        .input-danger {
            border: 1px solid red !important;
        }
        .form-control {
            border: 1px solid #eaeaea;
        }
        .form-control:focus {
            border: 1px solid #eaeaea;
            box-shadow: none;
        }
        .bg-success {
            background-color: #28a745;
            color: white;
        }
    </style>

    <div class="col-8 offset-2">
        <div class="container b-default p-4">

            <div class="row">

                <div class="col-12 mb-4">
                    <h3>Master Category & Color</h3>
                </div>  

                <div class="col-6">
                    
                    <form id="form-category" class="form-inline">
                        <div class="form-group mb-2">
                            <label for="input_category" class="sr-only">Category</label>
                            <input type="text" class="form-control" name="input_category" id="input_category" placeholder="New category" required>
                        </div>
                        <button type="submit" id="btn-category" class="btn btn-default mb-2 ml-2"><i class="fa fa-check" style="color: #28a745"></i></button>
                    </form>

                    <form id="form-color" class="form-inline">
                        <div class="form-group mb-2">
                            <label for="input_color" class="sr-only">Color</label>
                            <input type="text" class="form-control" name="input_color" id="input_color" placeholder="New color" required>
                        </div>
                        <button type="submit" id="btn-color" class="btn btn-default mb-2 ml-2"><i class="fa fa-check" style="color: #28a745"></i></button>
                    </form>

                </div>
                <!--/.col -->

                <div class="col-6">

                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-category-tab" data-toggle="pill" href="#pills-category" role="tab" aria-controls="pills-category" aria-selected="true">Category</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-color-tab" data-toggle="pill" href="#pills-color" role="tab" aria-controls="pills-color" aria-selected="false">Color</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-category" role="tabpanel" aria-labelledby="pills-category-tab">
                            
                            <div id="loading-category"></div>
                            <table class="table" id="table-category">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                    </tr>
                                </thead>
                                <tbody><tr><td>Data tidak tersedia</td></tr></tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="pills-color" role="tabpanel" aria-labelledby="pills-color-tab">
                            
                            <div id="loading-color"></div>
                            <table class="table" id="table-color">
                                <thead>
                                    <tr>
                                        <th>Color</th>
                                    </tr>
                                </thead>
                                <tbody><tr><td>Data tidak tersedia</td></tr></tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!--/.col -->

            </div>
            <!--/.row -->

        </div>
        <!--/.container -->
    </div>

    <script>

        $(document).ready( function () {

            $('input:first').focus();

            get_data_category();
            get_data_color();
        });

        function get_data_category() {

            $.ajax({
                url: "<?=site_url('category/get_data_category');?>",
                dataType: "json",
                beforeSend: function ()  {
                	var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                	$('#loading-category').html(loading);
                },
                success: function (data) {
                    $('#loading-category').html('');

                    if (data.length > 0 )  {
                        $('#table-category tbody').html('');

                        for  (i = 0; i < data.length;  i++) {
                            append_row_category(data[i]);
                        }
                    }
                }
            });
        }

        function append_row_category(data) {
            
            var table = document.getElementById('table-category').getElementsByTagName('tbody')[0];
            var countRow = table.rows.length;

			var row = table.insertRow(countRow);

			var cell1 = row.insertCell(0);

            cell1.innerHTML = data.name;
        }

        function get_data_color() {

            $.ajax({
                url: "<?=site_url('category/get_data_color');?>",
                dataType: "json",
                beforeSend: function ()  {
                	var loading = '<div class="overlay text-center"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>';
                	$('#loading-color').html(loading);
                },
                success: function (data) {
                    $('#loading-color').html('');

                    if (data.length > 0 )  {
                        $('#table-color tbody').html('');

                        for  (i = 0; i < data.length;  i++) {
                            append_row_color(data[i]);
                        }
                    }
                }
            });
        }

        function append_row_color(data) {
            
            var table = document.getElementById('table-color').getElementsByTagName('tbody')[0];
            var countRow = table.rows.length;

			var row = table.insertRow(countRow);

			var cell1 = row.insertCell(0);

            cell1.innerHTML = data.name;
        }

        $('#form-category').submit( function (e) {
            e.preventDefault();

            $.ajax({
                url: "<?=site_url('category/save_category');?>",
                type: "post",
                data: $('#form-category').serialize(),
                success: function (response) {

                    $('#input_category').removeClass('input-danger');

                    if (response == 'error-null') {
                        $('#input_category').addClass('input-danger');
                        $('#input_category').focus();
                    } else if (response == 'error-duplicate') {
                        $('#input_category').addClass('input-danger');
                        $('#input_category').focus();
                    } else {

                        $('#input_category').addClass('bg-success');

                        setTimeout( function () {

                            $('#input_category').removeClass('bg-success');
                            $('#input_category').val('');
                            $('#input_category').focus();

                            get_data_category();
                        }, 1500);
                    }
                }
            });
        });

        $('#form-color').submit( function (e) {
            e.preventDefault();

            $.ajax({
                url: "<?=site_url('category/save_color');?>",
                type: "post",
                data: $('#form-color').serialize(),
                success: function (response) {

                    $('#input_color').removeClass('input-danger');

                    if (response == 'error-null') {
                        $('#input_color').addClass('input-danger');
                        $('#input_color').focus();
                    } else if (response == 'error-duplicate') {
                        $('#input_color').addClass('input-danger');
                        $('#input_color').focus();
                    } else {

                        $('#input_color').addClass('bg-success');

                        setTimeout( function () {

                            $('#input_color').removeClass('bg-success');
                            $('#input_color').val('');
                            $('#input_color').focus();

                            get_data_color();
                        }, 1500);
                    }
                }
            });
        });

    </script>