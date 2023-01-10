<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- NAVBAR --}}
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="/home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('mahasiswa') ? 'active' : '' }}"
                                href="/mahasiswa">Mahasiswa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('pegawai') ? 'active' : '' }}"
                                href="/pegawai">Pegawai</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</head>

<body>
    <div class="container">
        @if (session('sukses'))
            <div class="alert alert-success" role="alert">
                {{ session('sukses') }}
            </div>
        @endif
        <center>
            <h1 class="pt-3">Data Mahasiswa</h1>
        </center>
        <div class="row">
            <div class="col-4 my-4">
                <a href="/mahasiswa/exportpdf" class="btn btn-sm btn-success">Export PDF</a>
            </div>

            {{-- form search data --}}
            <div class="col-4 my-4">
                @csrf
                <form class="d-flex" action="/mahasiswa/cari" method="GET">
                    <input class="form-control me-2" type="text" name="cari" 
                    placeholder="Cari data mahasiswa .." value="{{ old('cari') }}">
                    <button class="btn btn-outline-success" type="submit">Cari</button>
                </form>
            </div>

            <!-- Button trigger modal -->
            <div class="col-4 my-4" align="right">
                <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                    data-target="#exampleModal">
                    Tambah Data
                </button>
            </div>
        </div>


        {{-- pemberitahuan jika data tidak ditemukan --}}
        @if ($mahasiswa->count() > 0)
        @else
            <center>
                <font color="red">
                    <p>!! Tidak ditemukan data yang sesuai dengan kata kunci !!</p>
                </font>
            </center>
        @endif
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th>NAMA</th>
                    <th>NIM</th>
                    <th>ALAMAT</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            @foreach ($mahasiswa as $mhs)
                <tbody>
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $mhs->nama }}</td>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $mhs->alamat }}</td>
                        <td><a href="/mahasiswa/{{ $mhs->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                            <a href="/mahasiswa/delete/{{ $mhs->id }}" class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin mau hapus data?')">Delete</a>
                        </td>
                    </tr>
                </tbody>
            @endforeach
        </table>
        <div>
            Current Page: {{ $mahasiswa->currentPage() }}<br>
            Jumlah Data: {{ $mahasiswa->total() }}<br>
            Data perhalaman: {{ $mahasiswa->perPage() }}<br>
            <br>
            {{ $mahasiswa->links() }}
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/mahasiswa/create" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nama</label>
                            <input name="nama" type="text" class="form-control" id="exampleInputEmail1"
                                aria-describedby="EmailHelp" placeholder="Nama">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">NIM</label>
                            <input name="nim" type="text" class="form-control" id="exampleInputEmail1"
                                aria-describedby="EmailHelp" placeholder="NIM">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Alamat</label>
                            <textarea name="alamat" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
    </script>
</body>

</html>
