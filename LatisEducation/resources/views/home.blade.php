@extends('layouts.admin')

@section('header', 'Dashboard')

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('content')
<div id="controller">
    <a class="btn btn-primary" @click="addData()" >Create New Student</a>
    <div class="mb-3">
        <label for="lembagaFilter">Filter by Lembaga:</label>
        <select id="lembagaFilter" class="form-control" @change="filterLembaga">
            <option value="Latis Education && Tutor Indonesia">All</option>
            <option value="Latis Education">Latis Education</option>
            <option value="Tutor Indonesia">Tutor Indonesia</option>
        </select>
    </div>
    <div class="card-body">
        <table id="datatable" class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th style="width: 10px">Lembaga</th>
                    <th class="text-center">NIS</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Photo</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Delete</th>
                </tr>
            </thead>    
        </table>
    </div> 
    <div class="mb-3">
        <button class="btn btn-success" @click="exportToExcel">Export to Excel</button>
    </div>
    <img src="C:\xampp\tmp\php682D.tmp" alt="">
    
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Student</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" :action="actionUrl" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="PUT" v-if="editStatus" >
                        <div class="card-body">
                            <div class="form-group">
                                <label>Lembaga</label>
                                <br>
                                <select name="lembaga" class="form-control form-select form-select-lg" :value="data.lembaga">
                                <option value="Latis Education">Latis Education</option>
                                <option value="Tutor Indonesia">Tutor Indonesia</option>
                                </select>
                                <br>
                                @error('lembaga')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <label>NIS</label>
                                <input type="text" name="nis" class="form-control" :value="data.nis" placeholder="Enter nis" >
                                @error('nis')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" :value="data.nama" placeholder="Enter nama" >
                                @error('nama')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" :value="data.email" placeholder="Enter email" >
                                @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <label>Photo</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="photo" name="photo" :value="data.photo">
                                        <label class="custom-file-label">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<!-- Page specific script -->
<script type="text/javascript">
    var actionUrl = "{{ url('students') }}";
    var apiUrl = "{{ url('api/students') }}";
    
    var columns = [
        {data: 'DT_RowIndex',class: 'text-center', orderable: false},
        {data: 'lembaga',class: 'text-center', orderable: false},
        {data: 'nis',class: 'text-center', orderable: false},
        {data: 'nama',class: 'text-center', orderable: false},
        {data: 'email',class: 'text-center', orderable: false},
        {
            data: 'photo',
            class: 'text-center',
            orderable: false,
            render: function(data) {
                return data ? `<img src="{{ asset('storage') }}/${data}" alt="Student Photo" style="width: 50px;">` : '';
            }
        },
        {render: function(index, row, data, meta) {
            return `<a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">Edit</a>`;
            },
            orderable: false,
            class: "text-center"
        },
        {render: function(index, row, data, meta) {
            return `<a href="#" class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">Delete</a>`;
            },
            orderable: false,
            class: "text-center"
        }
    ];

    var controller = new Vue({
        el: '#controller',
        data: {
            datas: [],
            data: {},
            actionUrl,
            apiUrl,
            editStatus:false,
        },
        mounted: function() {
            this.datatable();
        },
        methods: {
            datatable() {
                const _this = this;
                _this.table = $('#datatable').DataTable({
                    ajax: {
                        url: _this.apiUrl,
                        type: 'GET',
                    },
                    columns: columns
                }).on('xhr', function(){
                    _this.datas = _this.table.ajax.json().data;
                });
            },
            addData() {
                this.data = {};
                this.editStatus = false;
                this.actionUrl = '{{ url('students') }}';
                $('#modal-default').modal();
            },
            editData(event, row) {
                this.data = this.datas[row];
                this.editStatus = true;
                this.actionUrl = '{{ url('students') }}' + '/' + this.data.id;
                $('#modal-default').modal();
            },
            deleteData(event, id) {
                if (confirm("Are you sure ?")) {
                    const _this = this;
                    $(event.target).closest('tr').remove();
                    axios.post(this.actionUrl + '/' + id, { _method: 'DELETE' })
                    .then(response => {
                        alert('Data has been removed');
                    });
                }
            },
            filterLembaga: function(event) {
                var lembaga = event.target.value;
                var table = $('#datatable').DataTable();
                
                if (lembaga === 'Latis Education && Tutor Indonesia') {
                    table.columns(1).search('').draw();
                } else {
                    table.columns(1).search(lembaga).draw();
                }
            },
            exportToExcel() {
                var table = $('#datatable').DataTable();
                new $.fn.dataTable.Buttons(table, {
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            title: 'Exported Data',
                            text: 'Export to Excel',
                            className: 'btn btn-success',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        }
                    ]
                }).container().appendTo($('.btn-export'));
                table.buttons(0).trigger();
            }
        }
    });
</script>
@endsection
