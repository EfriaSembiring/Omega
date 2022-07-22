<x-app-layout>
    <x-slot name="title">Daftar Kebutuhan Barang Proyek</x-slot>

    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif

    <x-card>
        <x-slot name="title">Semua Kebutuhan Barang Proyek</x-slot>
        <x-slot name="option">
            <div>
                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Filter Proyek
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="{{ route('admin.proyek.index') }}" class="dropdown-item">Semua Proyek</a>
                    @foreach ($proyeks as $row)
                        <a class="dropdown-item"
                            href="{{ route('admin.proyek.index') }}?filter_proyek={{ $row->nama_proyek }}">{{ $row->nama_proyek }}</a>
                    @endforeach
                </div>
                @if (Auth::user()->roles[0]->name == 'Estimator')
                    <button class="btn btn-primary add"><i class="fas fa-plus"></i> Tambah</button>
                @endif
            </div>
        </x-slot>

        <div class="table-responsive">
            <table class="display table table-striped table-hover" id="daftar">
                <thead>
                    <tr>
                        @if (Auth::user()->roles[0]->name == 'Admin')
                            <th>Estimator</th>
                        @endif
                        <th>Nama Proyek</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Barang</th>
                        @if (Auth::user()->roles[0]->name == 'Estimator')
                            <th style="width: 10%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            @if (Auth::user()->roles[0]->name == 'Admin')
                                <td>{{ $row->user->name }}</td>
                            @endif
                            <td>{{ $row->proyek_name }}</td>
                            <td>{{ $row->barang_name }}</td>
                            <td>{{ $row->jumlah }}</td>
                            @if (Auth::user()->roles[0]->name == 'Estimator')
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary edit" data-id="{{ $row->id }}"><i
                                            class="fas fa-edit"></i></button>
                                    <form action="{{ route('admin.proyek.destroy', $row->id) }}"
                                        style="display: inline-block;" method="POST">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-danger delete"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </x-card>

    {{-- add model --}}
    <x-modal>
        <x-slot name="title">
            <h6 class="m-0 font-weight-bold text-primary">Tambahkan Kebutuhan Barang</h6>
        </x-slot>
        <x-slot name="id">add</x-slot>


        <form action="{{ route('admin.proyek.store') }}" method="post" class="form-group">
            @csrf
            {{-- <div class="form-group">
                <label for="">Nama Proyek</label>
                <input type="text" class="form-control" name="nama_proyek" required="">
            </div> --}}
            <div class="form-group">
                <label for="">Nama Proyek</label><br>
                <input hidden name="proyek_id" id="proyek_id">
                <select id="nama-proyek-select" id="proyek_name" name="nama_proyek" class="form-control" style="width:100%" required>
                <option value="">--- Masukan/Pilih Proyek ---</option>
                    @foreach ($proyeks as $row)
                        <option value="{{ $row->id }}">{{ $row->nama_proyek }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Barang</label>
                <select name="barang_id" class="form-control">
                    <option value="">--- Pilih Barang ---</option>
                    @foreach ($barangs as $row)
                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Jumlah Barang</label>
                <input type="number" class="form-control" name="jumlah" required="">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </x-modal>

    {{-- edit model --}}
    <x-modal>
        <x-slot name="title">
            <h6 class="m-0 font-weight-bold text-primary">Edit Barang</h6>
        </x-slot>
        <x-slot name="id">edit</x-slot>


        <form action="{{ route('admin.proyek.update') }}" method="post" id="edit" class="form-group">
            @csrf
            <input type="hidden" name="id" value="">
            <div class="form-group">
                <label for="">Nama Proyek</label>
                <input type="text" class="form-control" name="nama_proyek" required="">
            </div>
            <div class="form-group">
                <label for="">Barang</label>
                <select name="barang_id" class="form-control">
                    <option value="">--- Pilih Barang ---</option>
                    @foreach ($barangs as $row)
                        <option value="{{ $row->id }}">{{ $row->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Jumlah Barang</label>
                <input type="number" class="form-control" name="jumlah">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </x-modal>

    <x-slot name="script">
        <script src="{{ asset('dist/vendor/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dist/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
        <script>
            $('.add').click(function() {
                $('#add').modal('show')
            })

            $('.edit').click(function() {
                const id = $(this).data('id')

                $.get(`{{ route('admin.proyek.info') }}?id=${id}`, function(data) {
                    $('#edit input[name="id"]').val(id)
                    $('#edit input[name="nama_proyek"]').val(data.proyek.nama_proyek)
                    $('#edit input[name="jumlah"]').val(data.proyek.jumlah)
                    $(`#edit option[value="${data.barang.id}"]`).attr('selected', 'true')
                })

                $('#edit').modal('show')
            })

            $('.delete').click(function(e) {
                e.preventDefault()
                Swal.fire({
                    title: 'Ingin menghapus?',
                    text: 'Data akan dihapus permanen',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).parent().submit()
                    }
                })

            })

            $(document).ready(function() {
                $('#daftar').DataTable();

                $("#nama-proyek-select").select2({
                    tags: true
                });

                $('#nama-proyek-select').on('change', function() {
                    var val = $("#nama-proyek-select option:selected").val();

                    let listProyek = {!! str_replace("'", "\'", json_encode($proyeks)) !!};
                    let listProyekId = listProyek.map(function (c) { return c.id; });

                    console.log(listProyekId)
                    console.log(listProyekId.indexOf(val) !== -1)
                    console.log(typeof val)

                    if (val != "")
                    {
                        $("#proyek_id").val(val);
                    }
                })
            });
        </script>
    </x-slot>
</x-app-layout>
