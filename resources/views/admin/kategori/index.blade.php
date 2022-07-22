<x-app-layout>
    <x-slot name="title">Daftar Kategori Barang</x-slot>

    @if (session()->has('success'))
        <x-alert type="success" message="{{ session()->get('success') }}" />
    @endif

    <x-card>
        <x-slot name="title">Semua Kategori Barang</x-slot>
        <x-slot name="option">
            <div>
                <button class="btn btn-primary add"><i class="fas fa-plus"></i> Tambah Kategori Barang</button>
            </div>
        </x-slot>
        <div class="table-responsive">
            <table class="display table table-striped table-hover" id="daftar">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th style="width: 10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->nama }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary edit" data-id="{{ $row->id }}"><i
                                        class="fas fa-edit"></i></button>
                                <form action="{{ route('admin.kategori.destroy', $row->id) }}"
                                    style="display: inline-block;" method="POST">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-danger delete"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </x-card>

    {{-- add model --}}
    <x-modal>
        <x-slot name="title">
            <h6 class="m-0 font-weight-bold text-primary">Tambahkan Kategori Barang</h6>
        </x-slot>
        <x-slot name="id">add</x-slot>


        <form action="{{ route('admin.kategori.store') }}" method="post" class="form-group">
            @csrf
            <div class="form-group">
                <label for="">Nama Kategori Barang</label>
                <input type="text" class="form-control" name="nama" required="">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </x-modal>

    {{-- edit model --}}
    <x-modal>
        <x-slot name="title">
            <h6 class="m-0 font-weight-bold text-primary">Edit Kategori Barang</h6>
        </x-slot>
        <x-slot name="id">edit</x-slot>


        <form action="{{ route('admin.kategori.update') }}" method="post" id="edit" class="form-group">
            @csrf
            <input type="hidden" name="id" value="">
            <div class="form-group">
                <label for="">Nama Kategori Barang</label>
                <input type="text" class="form-control" name="nama" required="">
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

                $.get(`{{ route('admin.kategori.info') }}?id=${id}`, function(data) {
                    $('#edit input[name="id"]').val(id)

                    $('#edit input[name="nama"]').val(data.nama)
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
            });
        </script>
    </x-slot>
</x-app-layout>
