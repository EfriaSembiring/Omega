<x-app-layout>
	<x-slot name="title">Daftar Barang Keluar</x-slot>

	@if(session()->has('success'))
	<x-alert type="success" message="{{ session()->get('success') }}" />
	@endif

	<x-card>
		<x-slot name="title">Semua Barang Keluar</x-slot>
		<x-slot name="option">
			<button class="btn btn-primary add"><i class="fas fa-plus"></i> Tambah Keluaran</button>
		</x-slot>

		<div class="table-responsive">
		<table class="table table-hover mb-3">
			<thead>
				<th>Gambar</th>
				<th>Proyek</th>
				<th>Nama Barang</th>
				<th>Stok Keluar</th>
				<th>Tgl Keluar</th>
				<th style="width: 10%">Action</th>
			</thead>
			<tbody>
				@foreach ($data as $row)
					<tr>
                        <td>
                            <img src={{ $row->barang->image ?? 'https://bitsofco.de/content/images/2018/12/broken-1.png' }}
                                width="50" alt="">
                        </td>
						<td>{{ $row->proyek->nama_proyek }}</td>
						<td>{{ $row->barang->nama }}</td>
						<td>{{ $row->jumlah }}</td>
						<td>{{ date('d-F-Y', strtotime($row->tgl_brg_keluar)) }}</td>
						<td class="text-center">
							<button class="btn btn-sm btn-primary edit" data-id="{{ $row->id }}"><i class="fas fa-edit"></i></button>
							<form action="{{ route('admin.barang-keluar.destroy', $row->id) }}" style="display: inline-block;" method="POST">
							@csrf
							<button type="button" class="btn btn-sm btn-danger delete"><i class="fas fa-trash"></i></button>
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
			<h6 class="m-0 font-weight-bold text-primary">Tambahkan yang keluar</h6>
		</x-slot>
		<x-slot name="id">add</x-slot>


		<form action="{{ route('admin.barang-keluar.store') }}" method="post" class="form-group" >
			@csrf
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Proyek</label>
						<select name="proyek_id" class="form-control">
							<option value="">-- Pilih Proyek --</option>
							@foreach ($proyek as $row)
								<option value="{{ $row->id }}">{{ $row->nama_proyek }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Barang</label>
						<select name="barang_id" class="form-control">
							<option value="">-- Pilih Barang --</option>
							@foreach ($barang as $row)
								<option value="{{ $row->id }}">{{ $row->nama }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Stok</label>
						<input type="number" value="0" class="form-control" name="jumlah">
					</div>
				</div>

				<div class="col-md-4">
                    <div class="form-group">
                        <label for="">Tanggal Keluar</label>
                        <input type="date"  class="form-control" name="tgl_brg_keluar">
                    </div>
            </div>
			</div>
			<div class="form-group">
				<textarea name="catatan" id="" cols="30" rows="10" class="form-control" placeholder="Catatan"></textarea>
			</div>
			<button type="submit" class="btn btn-primary">Simpan</button>
		</form>
	</x-modal>

	{{-- edit model --}}
	<x-modal>
		<x-slot name="title">
			<h6 class="m-0 font-weight-bold text-primary">Edit Stok</h6>
		</x-slot>
		<x-slot name="id">edit</x-slot>


		<form action="{{ route('admin.barang-keluar.update') }}" method="post" id="edit" class="form-group" >
			@csrf
			<input type="hidden" name="id">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Proyek</label>
						<select name="proyek_id" class="form-control">
							<option value="">-- Pilih Proyek --</option>
							@foreach ($proyek as $row)
								<option value="{{ $row->id }}">{{ $row->nama_proyek }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="">Barang</label>
						<select name="barang_id" class="form-control">
							<option value="">-- Pilih Barang --</option>
							@foreach ($barang as $row)
								<option value="{{ $row->id }}">{{ $row->nama }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Stok</label>
						<input type="number" value="0" class="form-control" name="jumlah">
					</div>
				</div>

				
				<div class="col-md-4">
                    <div class="form-group">
                        <label for="">Tanggal Keluar</label>
                        <input type="date"  class="form-control" name="tgl_brg_keluar">
                    </div>
            </div>
			</div>
			<div class="form-group">
				<textarea name="catatan" id="" cols="30" rows="10" class="form-control" placeholder="Catatan"></textarea>
			</div>
			<button type="submit" class="btn btn-primary">Simpan</button>
		</form>
	</x-modal>



	<x-slot name="script">
		<script src="{{ asset('dist/vendor/datatables/jquery.dataTables.min.js') }}"></script>
		<script src="{{ asset('dist/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
		<script src="{{ asset('dist/js/simple.money.format.js') }}"></script>
		<script>

			$('.add').click(function() {
				$('#add').modal('show')
			})

			$('.edit').click(function() {
				const id = $(this).data('id')

				$.get(`{{ route('admin.barang-keluar.info') }}?id=${id}`, function(data) {
					$('#edit input[name="id"]').val(id)

					$(`#edit select[name="barang_id"] option[value="${data.barang.id}"]`).attr('selected', 'true')
					$(`#edit select[name="proyek_id"] option[value="${data.proyek.id}"]`).attr('selected', 'true')
					$('#edit input[name="jumlah"]').val(data.jumlah)
					$('#edit input[name="tgl_brg_keluar"]').val(data.tgl_brg_keluar)
					$('#edit textarea[name="catatan"]').val(data.catatan)
				})

				$('#edit').modal('show')
			})

			$('.delete').click(function(e){
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

			$(document).ready(function () {
		      $('table').DataTable();
		    });
		</script>
	</x-slot>
</x-app-layout>
