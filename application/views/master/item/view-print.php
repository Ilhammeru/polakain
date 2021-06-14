<style>
    .label-item {
        font-weight: normal !important;
    }

    .field-result {
        margin-top: 2em;
        display: none;
    }

    .field-id-print,
    .field-value-print {
        border-radius: 4px;
        border: 1px solid #e6e6e6;
    }

    #table-selection,
    .card-footer {
        display: none;
    }

    .fas.fa-times {
        color: red;
    }

    .td-delete {
        cursor: pointer;
    }

    .td-name {
        max-width: 12em;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
</style>

<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
        <div class="list-item">
            <div class="card">
                <div class="card-header text-center">
                    <h5>List Item</h5>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <select class="custom-select" id="category" onchange="get_item()">
                            <option value="">-- Pilih kategori --</option>
                            <?php for ($i = 0; $i < count($category); $i++) : ?>
                                <option value="<?= $category[$i]; ?>"><?= $category[$i]; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="field-result">
                        <div class="table-responseive">
                            <table class="table" id="table-list-item">
                                <thead>
                                    <tr>
                                        <th class="th-name" onclick="sorting('ascending', 1)">Item <i class="fas fa-sort"></i></th>
                                        <th class="th-code" onclick="sorting('ascending', 1)">Code <i class="fas fa-sort"></i></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="target-item">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8">
        <div class="card card-result-selection">
            <div class="card-header text-center">
                <h5>Daftar Print Item</h5>
            </div>
            <form action="" id="form-print-barcode">
                <div class="card-body">
                    <div class="text-center warning-text">
                        Tidak ada item yang dipilih
                    </div>
                    <table class="table" id="table-selection">
                        <thead>
                            <tr>
                                <th colspan="2">Item</th>
                                <th></th>
                                <th>Jumlah Print</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>

        <div class="target"></div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('form').submit((e) => {
            e.preventDefault();

            var form = $('#form-print-barcode').serialize();

            $.ajax({
                type: 'post',
                data: form,
                url: "<?= site_url('item/process_print'); ?>",
                dataType: 'text',
                success: function(response) {
                    if (response == 'array-null') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Semua kolom harus diisi',
                            text: 'Kolom jumlah masih ada yang kosong',
                        });
                    } else if (response == 'array-max') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Mencapai jumlah maksimal',
                            text: 'Maksimal hanya 15 baris barcode untuk di print',
                        });
                    } else {
                        w = window.open(window.location.href, "_blank");
                        w.document.open();
                        w.document.write(response);
                        w.document.close();
                    }
                }
            })
        });
    });

    function get_item() {
        var post = $("#category").val();

        $.ajax({
            type: 'post',
            data: {
                category: post
            },
            url: '<?= site_url('item/get_print_item'); ?>',
            dataType: 'json',
            success: function(response) {
                var uncheck = '<?= base_url('assets/images/uncheck.png'); ?>';

                var tr = '';
                for (var i = 0; i < response.name.length; i++) {
                    var code = "'" + response.code[i] + "'";
                    var name = "'" + response.name[i] + "'";

                    tr += '<tr id="tr-check-' + response.id[i] + '" data-check="0">' +
                        '<td class="td-name" onclick="set_item(' + code + ', ' + response.id[i] + ', ' + name + ')">' + response.name[i] + '</td>' +
                        '<td onclick="set_item(' + code + ', ' + response.id[i] + ', ' + name + ')">' + response.code[i] + '</td>' +
                        '<td onclick="set_item(' + code + ', ' + response.id[i] + ', ' + name + ')" id="field-checkbox-' + response.id[i] + '"><img src="' + uncheck + '" style="width: 20%; height: auto;"></td>' +
                        '</tr>';
                }

                $('.target-item').html(tr);

                $('.field-result').show();

                validation_item();
            }
        })
    }

    function validation_item() {
        var checkbox = '<?= base_url('assets/images/check.png'); ?>';

        var active = $('.active-item-row').map(function() {
            return $(this).attr('active-item-row');
        }).toArray();

        for (var i = 0; i < active.length; i++) {
            $('#field-checkbox-' + active[i]).html('<img src="' + checkbox + '" style="width: 20%; height: auto;" />');
        }
    }

    function set_item(code, id, name) {
        var checkbox = '<?= base_url('assets/images/check.png'); ?>';
        var uncheck = '<?= base_url('assets/images/uncheck.png'); ?>';

        var check = check_item(id);

        if (check == false) {
            // change attribute 

            var table = document.getElementById('table-selection').getElementsByTagName('tbody')[0];

            var length = table.rows.length;

            var row = table.insertRow(length);

            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);
            var cell6 = row.insertCell(5);

            cell1.innerHTML = name;
            cell2.innerHTML = '3 x ';
            cell3.innerHTML = '<input type="text" class="field-id-print" id="field-value-print-' + id + '" name="field-value-print[]">';
            cell4.innerHTML = '<i class="fas fa-times"></i>';
            cell5.innerHTML = '<input type="hidden" class="field-id-print" id="field-id-print-' + id + '" name="field-id-print[]">';
            cell6.innerHTML = '<input type="hidden" class="field-code-print" id="field-code-print-' + id + '" name="field-code-print[]" value="' + code + '">';

            cell1.setAttribute('colspan', '2');
            cell2.classList.add('text-right');
            cell4.classList.add('text-left');
            cell4.classList.add('td-delete');
            cell4.setAttribute('onclick', 'delete_item(' + id + ')');

            row.classList.add('row-' + id);
            row.classList.add('active-item-row');
            row.setAttribute('active-item-row', id);

            //change image
            $('#field-checkbox-' + id).html('<img src="' + checkbox + '" style="width: 25%; height: auto;">');

            //fill input
            $('#field-id-print-' + id).val(id);

            check_table();
        } else {
            $('.row-' + id).remove();

            //change image
            $('#field-checkbox-' + id).html('');

            // clear input
            $('#field-id-print-' + id).val('');

            check_table();
        }
    }

    function delete_item(id) {
        $('.row-' + id).remove();
        check_table();

        //change image
        $('#field-checkbox-' + id).html('');
    }

    function check_table() {
        var row = document.getElementById('table-selection');
        var length = row.rows.length;

        if (length == 1) {
            $('.warning-text').show();
            $('#table-selection').hide();
            $('.card-footer').hide();
        } else {
            $('#table-selection').show();
            $('.warning-text').hide();
            $('.card-footer').show();
        }
    }

    function check_item(id) {
        var check = $('#tr-check-' + id).attr('data-check');

        if (check == 0) {
            $('#tr-check-' + id).attr('data-check', 1);

            return false;
        } else {
            $('#tr-check-' + id).attr('data-check', 0);

            return true;
        }
    }

    function sorting(key, row) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("table-list-item");
        switching = true;


        while (switching) {

            switching = false;
            rows = table.rows; // get tr element

            for (i = 1; i < (rows.length - 1); i++) {

                shouldSwitch = false;

                x = rows[i].getElementsByTagName("TD")[row]; // td title
                y = rows[i + 1].getElementsByTagName("TD")[row]; // td title

                if (key == "ascending") {

                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {

                        shouldSwitch = true;
                        break;
                    }

                } else if (key == "descending") {

                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {

                        shouldSwitch = true;
                        break;
                    }

                }

            }

            if (shouldSwitch) {

                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]); //change posititon
                switching = true;

                // if true then do increment (for all element)
                switchcount++;

            } else {

                if (switchcount == 0 && key == "ascending") {

                    key = "descending";
                    switching = true;

                }

            }

        }
    }
</script>