<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ $title }}</title>
    <link href="{{ asset('dist/img/logo/LogoOmega1.png') }}" rel="shortcut icon" type="image/x-icon">
    <link href="{{ asset('dist/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dist/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dist/vendor/swal2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('dist/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/css/ruang-admin.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{ $head ?? '' }}
</head>

<body id="page-top">
    <div id="wrapper">

        {{-- sidebar --}}
        <x-sidebar></x-sidebar>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                {{-- topbar --}}
                <x-topbar></x-topbar>

                {{-- container fluid --}}
                <div class="container-fluid" id="container-wrapper">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </div>

                    {{-- Main content --}}
                    {{ $slot }}

                    {{-- Modal Logout --}}
                    <x-modal-logout />


                    <x-modal>
                        <x-slot name="title">
                            <h6 class="m-0 font-weight-bold text-primary">Semua notifikasi</h6>
                        </x-slot>
                        <x-slot name="id">show-all-notif</x-slot>
                    </x-modal>

                </div>
            </div>

            {{-- Footer --}}
            <footer class="sticky-footer bg-white mt-3">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>copyright &copy;
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    {{-- Scroll to top --}}
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="{{ asset('dist/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('dist/js/ruang-admin.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('dist/vendor/swal2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{ $script ?? '' }}


    @if (Auth::user()->roles[0]->name == 'Admin')
        <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-app.js"></script>
        <script src="https://www.gstatic.com/firebasejs/8.0.0/firebase-database.js"></script>
        <script type="text/javascript">
            const firebaseConfig = {
                apiKey: "AIzaSyC_BVaU0Dk6mgT0-abwFS0Ccr3696Ms8jc",
                authDomain: "inventoryproject-43df6.firebaseapp.com",
                projectId: "inventoryproject-43df6",
                storageBucket: "inventoryproject-43df6.appspot.com",
                messagingSenderId: "254831582136",
                appId: "1:254831582136:web:9923205ce7440619890d4d"
            };

            // Initialize Firebase
            const app = firebase.initializeApp(firebaseConfig);
            var database = firebase.database();
            database.ref("notication/proyek").orderByChild('created_at').limitToLast(5).on('value', function(snapshot) {
                var value = snapshot.val();
                var htmls = [];
                var count = 0;

                if (value != null) {
                    $.each(value, function(index, value) {
                        if (value && value.isRead === 'no') {
                            count++;
                        }

                        if (value) {
                            htmls.push('<a class="dropdown-item" href="{{ route('admin.proyek.index') }}">' +
                                '<p class="font-weight-bold m-0">' + value.title + '</p>' +
                                '<small class="text-gray-500">Jumlah barang : ' + value.jumlah +
                                // ' | Sisa barang : ' + (value.sisa ? value.sisa : ' ') +
                                '</small>' +
                                '</a>' +
                                '<div class="dropdown-divider"></div>');
                        }
                    });
                    // htmls.push('<button class="btn btn-primary show-all-notif"><i class="fas fa-plus"></i> Tambah</button>');
                } else {
                    htmls.push('<a class="dropdown-item" href="#">' +
                        '<p class="m-0 text-gray-500">Notifikasi kosong</p></a>');
                }
                $("#notif-counter").html(count)
                $('#list-notif').html(htmls.reverse());
            });

            $('.nav-notif').click(function() {
                console.log('reset')
                database.ref("notication/proyek").orderByChild('created_at').limitToLast(5).on('value', function(
                    snapshot) {
                    var value = snapshot.val();
                    $.each(value, function(index, value) {
                        if (value != null && value.isRead === 'no') {
                            let data = database.ref('notication/proyek/' + value.id);
                            data.update({
                                'isRead': 'yes'
                            })
                        }
                    });
                    // htmls.push('<button class="btn btn-primary show-all-notif"><i class="fas fa-plus"></i> Tambah</button>');
                });
                // $("#notif-counter").html(0)
                // console.log('first')
                // $('#list-all-notification').modal('show')
            })
        </script>
    @endif

</body>

</html>
