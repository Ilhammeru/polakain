				
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

                   	}
                });

                $(".select2").val('').trigger('change') ;