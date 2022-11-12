@extends('layouts.app')
@section('css')
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="container">
        <h1>Show files </h1>
        <table class="table table-bordered data-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Size</th>
                <th width="100px">Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection

@section('js')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">
        $(function () {

            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('zip.index') }}",
                columns: [
                    {data: 'file_name'},
                    {data: 'type'},
                    {data: 'size'},
                    {data: 'id'},
                ],
                columnDefs: [
                    {
                        targets: 3,
                        render: (row, type, data) => {
                            return `<div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1${data.id}" data-bs-toggle="dropdown" aria-expanded="false">
                                    :
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1${data.id}">
                                    <li><a class="dropdown-item" data-type="delete" data-id="${data.id}" href="#">Delete</a></li>
                                    <li><a class="dropdown-item" data-type="download" data-id="${data.id}" href="#">Download</a></li>
                                </ul>
                            </div>`

                        }
                    }
                ],

            });

        });
        $(document).on('click', '.dropdown-item', function (e) {
            e.preventDefault();
            let id = $(this).data('id')
            if ($(this).data('type') == 'delete') {
                axios.post(`files/delete/${id}`).then(res => {
                    $('.data-table').DataTable().ajax.reload();
                })
            }
            if ($(this).data('type') == 'download') {
                axios.get(`/json`, {
                    params: {
                        id: id
                    }
                }).then(res => {
                    let dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(res.data));
                    let el = document.createElement('a');
                    el.setAttribute("href", dataStr);
                    el.setAttribute("download", "file.json");
                    el.click();
                    el.remove();
                })
            }
        });

    </script>
@endsection
