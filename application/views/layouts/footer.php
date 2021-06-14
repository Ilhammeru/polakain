
            </div>
            <!-- /div.container-fluid -->

        </section>
        <!-- /section.content -->

    </div>
    <!-- /div.content-wrapper -->

</div>
<!-- /div.wrapper -->

<a id="back-to-top" href="#" class="btn btn-primary back-to-top-left" role="button" aria-label="Scroll to top">
	<i class="fas fa-chevron-up text-light"></i>
</a>

<div id="modal-placehorder"></div>

<!-- Modal -->
<div class="modal fade" id="modal-overlay" tabindex="-1"  aria-hidden="true">

	<div class="modal-dialog modal-sm modal-dialog-centered">

		<div class="modal-content">
			<div class="modal-body">
				<div class="text-center">
					<div class="spinner-border" style="width: 10rem; height: 10rem;" role="status" aria-hidden="true"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

	$(document).ready( function () {

		$('.select2').select2();

		$('.daterangepicker').daterangepicker({
			drops: 'down',
        	todayHighlight : true,
            autoUpdateInput: false,
        	locale: {
		    	cancelLabel: 'Clear'
		    }
		});

        $('.daterangepicker2').daterangepicker({
			drops: 'down',
        	todayHighlight : true,
        	locale: {
		    	cancelLabel: 'Clear'
		    }
		});

        $('.currency-rp').inputmask("numeric", {

            radixPoint: ".",
            groupSeparator: ",",
            digits: 0,
            autoGroup: true,
            prefix: '',
            rightAlign: true,
            allowMinus: false,
            oncleared: function () {
                self.value('');
            }

        });

	});

	function currency_rp() {
		
		$('.currency-rp').inputmask("numeric", {

            radixPoint: ".",
            groupSeparator: ",",
            digits: 2,
            autoGroup: true,
            prefix: '',
            rightAlign: true,
            allowMinus: false,
            oncleared: function () {
                self.value('');
            }

        });
	}

    function single_datepicker() {

        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

        $('.single-datepicker').daterangepicker({
            singleDatePicker: true,
            drops: 'down',
            todayHighlight : true,
            showDropdowns: true,
            minDate: today,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        });

    }

    function single_datepicker2(date) {

        $('.single-datepicker').daterangepicker({
            singleDatePicker: true,
            drops: 'down',
            startDate: date,
            todayHighlight : true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        });

    }

    function number_format(x, decimal = false) {
        var dcml;
        if(decimal == false){
            dcml = 2;
        }else{
            dcml = decimal;
        }
        number = parseFloat(x).toFixed(dcml).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        //str = 'Rp ' + number;
        return number;
    }

    function number_format_0(x) {
        var dcml = 0;
        number = parseFloat(x).toFixed(dcml).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        //str = 'Rp ' + number;
        return number;
    }

    function number_format_2(x) {
        number = parseFloat(x).toFixed().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        str = 'Rp ' + number;
        return str;
    }

</script>

</body>
</html>