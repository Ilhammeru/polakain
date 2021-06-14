<style>
    #template-name {
        text-transform: uppercase;
    }

    #table-template tbody {
        display: block;
        overflow: auto;
        height: 310px;
        width: 100%;
        /* border-bottom: 1px solid rgba(0,0,0,0.2); */
    }

    #table-template thead,
    #table-template tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
        /* even columns width , fix width of table too*/
    }

    #table-template tbody tr td {
        /* border: 0.5px solid rgba(0,0,0,0.2)!important; */
    }

    .text-regular {
        text-decoration: none !important;
        font-weight: 400 !important;
    }
</style>


<div class="row">

    <div class="col-md-6">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Template Pola</h3>
            </div>
            <form id="form-template" method="post" action="<?= site_url('template_pola/submit_data'); ?>">
                <div class="card-body">

                    <div class="form-group">

                        <label for="template_name">Nama Template</label>
                        <input type="text" id="template-name" name="template_name" class="form-control text-right" required style="padding-right: 1.5rem">
                    </div>

                    <div class="form-group">

                        <label for="template_qty">Qty</label>
                        <input type="number" min="1" step="1" value="1" id="template-qty" name="template_qty" class="form-control text-right" required>
                    </div>

                    <div class="form-group">

                        <label for="template_qty">Qty Plastik</label>
                        <input type="number" min="1" step="1" value="1" id="template-plastik" name="template_plastik" class="form-control text-right" required>
                    </div>

                    <div class="form-group">

                        <label for="template_price">Harga</label>
                        <input type="text" id="template-price" name="template_price" class="form-control currency-rp" required style="padding-right: 1.5rem">

                    </div>

                </div>
                <div class="card-footer">
                    <button class="btn btn-primary float-right">Submit</button>
                </div>
            </form>
        </div>

    </div>

    <div class="col-md-6">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Data Template</h3>
            </div>
            <div class="card-body p-0">

                <table class="table table-sm m-0" id="table-template">
                    <thead>
                        <tr>
                            <th>Template</th>
                            <th>Qty</th>
                            <th>Plastik</th>
                            <th>Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

    </div>

</div>
<!--/.row -->

<script>
    $(document).ready(function() {
        get_data();
    });

    function get_data() {

        $.ajax({
            url: "<?= site_url('template_pola/get_data'); ?>",
            dataType: "json",
            beforeSend: function() {
                var loading = '<div class="text-bold p-4">Loading...</div>';
                $('#table-template tbody').html(loading);
            },
            success: function(data) {
                $('#table-template tbody').html('');

                if (data.length > 0) {

                    for (i = 0; i < data.length; i++) {

                        append_row(data[i]);
                    }
                }
            }
        });
    }

    function append_row(data) {

        var tableTemplate = document.getElementById('table-template').getElementsByTagName('tbody')[0];
        var countRow = tableTemplate.rows.lenght;

        var row = tableTemplate.insertRow(countRow);

        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);

        cell1.innerHTML = data.name;
        cell2.innerHTML = data.qty;
        cell3.innerHTML = data.qty_plastik;
        cell4.innerHTML = number_format_0(data.price);

        cell4.className = 'text-right pr-4';

        var checked = '';
        if (data.is_active == 1) {
            checked = 'checked';
        }

        var checkbox = '<input type="checkbox" id="status-' + data.id + '" onclick="change_status(' + data.id + ')" ' + checked + '> <label for="status-' + data.id + '" class="text-regular">Active</label>';

        cell5.innerHTML = checkbox;
    }

    function change_status(id) {

        var status = 0;
        if ($('#status-' + id).is(':checked')) {
            status = 1;
        }

        $.ajax({
            url: "<?= site_url('template_pola/change_status'); ?>",
            method: "post",
            data: {
                id: id,
                is_active: status
            },
            success: function() {

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Data berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        });
    }

    $('#form-template').validate({

        rules: {
            template_name: {
                required: true
            },
            template_qty: {
                required: true
            },
            template_price: {
                required: true
            }
        },
        messages: {
            template_name: {
                required: 'Kolom template harus diisi'
            },
            template_qty: {
                required: 'Kolom qty harus diisi'
            },
            template_price: {
                required: 'Kolom harga harus diisi'
            }
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {

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
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {

                            if (response == 'error-duplicate') {

                                setTimeout(function() {
                                    $('#template-name').addClass('is-invalid');
                                }, 1000);

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Template sudah terdaftar!'
                                });
                            } else {

                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: 'Data berhasil disimpan',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                form.reset();

                                $('#template-name').focus();

                                get_data();
                            }
                        }
                    });
                }
            });

        }
    });
</script>