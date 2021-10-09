<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Countries List</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
</head>
<body>
    <div class="container">
        <div class="row" style="margin-top: 45px">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Countries</div>
                    <div class="card-body">
                        <table class="table table-hover table-condensed" id="counties-table">
                            <thead>
                                <th>#</th>
                                <th>Country Name</th>
                                <th>Capital City</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Add New Country</div>
                    <div class="card-body">
                        <form action="{{ route('add.country') }}" method="post" id="add-country-form">
                            @csrf
                            <div class="form-group">
                                <label for="">Country Name</label>
                                <input type="text" class="form-control" name="country_name" placeholder="Enter country name">
                                <span class="text-danger error-text country_name_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Capital City</label>
                                <input type="text" class="form-control" name="capital_city" placeholder="Enter country city">
                                <span class="text-danger error-text capital_city_error"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('edit-country-modal')
    <script src="{{ asset('jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('toastr/toastr.min.js') }}"></script>
    <script>
        toastr.options.preventDuplicates = true;

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            // ADD NEW COUNTRY
            $('#add-country-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType:false,
                    beforeSend:function() {
                        $(form).find('span.error-text').text('');
                    },
                    success:function(data) {
                        if(data.code == 0) {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.'+prefix+'_error').text(val[0]);
                            });
                        } else {
                            $(form)[0].reset();
                            // alert(data.msg);
                            $('#counties-table').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        }
                    }
                })
            });

            // GET ALL COUNTRIES
            $('#counties-table').DataTable({
                processing: true,
                info: true,
                ajax: "{{ route('get.countries.list') }}",
                "pageLength" : 5,
                "aLengthMenu": [[5,10,15,20,-1],[5,10,15,20,"All"]],
                columns: [
                    // {data:'id', name:'id'},
                    {data:'DT_RowIndex', name:'DT_RowIndex'},
                    {data:'country_name', name:'country_name'},
                    {data:'capital_city', name:'capital_city'},
                    {data:'actions', name:'actions'},
                ]
            });

            // GET COUNTRIES
            $(document).on('click', '#editCountryBtn', function() {
                var country_id = $(this).data('id');
                // alert(country_id);
                $('.editCountry').find('form')[0].reset();
                $('.editCountry').find('span.error-text').text('');
                $.post('<?= route("get.country.details") ?>',{country_id : country_id}, function(data){
                    // alert(data.details.country_name);
                    $('.editCountry').find('input[name="cid"]').val(data.details.id);
                    $('.editCountry').find('input[name="country_name"]').val(data.details.country_name);
                    $('.editCountry').find('input[name="capital_city"]').val(data.details.capital_city);
                    $('.editCountry').modal('show');
                },'json');
            });


            //UPDATE COUNTRY DETAILS
            $('#update-country-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;
                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType:false,
                    beforeSend:function() {
                        $(form).find('span.error-text').text('');
                    },
                    success:function(data) {
                        if(data.code == 0) {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.'+prefix+'_error').text(val[0]);
                            });
                        } else {
                            $('.editCountry').modal('hide');
                            $('.editCountry').find('form')[0].reset();
                            $('#counties-table').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        }
                    }
                })
            });

            // DELETE COUNTRY RECORD
            $(document).on('click', '#deleteCountryBtn', function() {
                var country_id = $(this).data('id');
                var url = '<?= route("delete.country") ?>';
                // alert(country_id);
                swal.fire({
                    title: 'Are you sure?',
                    html: 'You want to <b>delete</b> this country',
                    showCancelButton: true,
                    showCloseButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonColor: '#d33',
                    confirmButtonColor: '#556ee6',
                    width: 300,
                    allowOutsideClick: false
                }).then(function(result) {
                    if(result.value) {
                        $.post(url, {country_id: country_id}, function(data) {
                            if(data.code == 1) {
                                $('#counties-table').DataTable().ajax.reload(null, false);
                                toastr.success(data.msg);
                            } else {
                                toastr.error(data.msg);
                            }
                        },'json');
                    }
                });
            });
        });
    </script>
</body>
</html>
