@extends('layout.template')

@section('content')
    <div class="container mt-4 mb-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-warning text-dark">
                <h4><i class="fa-solid fa-location-dot"></i> Titik Kejadian</h4>
            </div>
            <div class="card-body bg-light">
                <table class="table table-hover table-bordered align-middle text-center" id="pointsTable">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelapor</th>
                            <th>Deskripsi Kejadian</th>
                            <th>Foto Kejadian</th>
                            <th>Dibuat</th>
                            <th>Diubah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($points as $index => $p)
                            <tr>
                                <td><span class="badge bg-info text-dark">{{ $index + 1 }}</span></td>
                                <td><i class="fa-solid fa-user-ninja"></i> {{ $p->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ Str::limit($p->decsription, 50, '...') }}</span>
                                </td>
                                <td>
                                    <img src="{{ asset('storage/images/' . $p->image) }}" alt="Foto Kejadian"
                                        class="rounded-circle shadow-sm img-thumbnail" width="100"
                                        title="Klik kanan > Lihat gambar">
                                </td>
                                <td><code>{{ $p->created_at->format('d M Y') }}</code></td>
                                <td><code>{{ $p->updated_at->format('d M Y') }}</code></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container mt-4 mb-4">
        <div class="row">
            <!-- Emergency Calls Polres -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-warning text-dark">
                        <h4><i class="fa-solid fa-shield-halved"></i> Emergency Calls Polres</h4>
                    </div>
                    <div class="card-body bg-light">
                        <table class="table table-hover table-bordered align-middle text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Polres</th>
                                    <th>Kontak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Polres Sleman</td>
                                    <td>(0274) 123456</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Polres Yogyakarta</td>
                                    <td>(0274) 654321</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Polres Bantul</td>
                                    <td>(0274) 789101</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Polres Kulon Progo</td>
                                    <td>(0274) 222333</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Polres Gunungkidul</td>
                                    <td>(0274) 444555</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Polsek Bulaksumur</td>
                                    <td>(0274) 557111</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Kantor Unit Satlantas Turjawali</td>
                                    <td>(0274) 513237</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Emergency Calls Rumah Sakit -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-warning text-dark">
                        <h4><i class="fa-solid fa-truck-medical"></i> Emergency Calls Rumah Sakit</h4>
                    </div>
                    <div class="card-body bg-light">
                        <table class="table table-hover table-bordered align-middle text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Rumah Sakit</th>
                                    <th>Kontak</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>RSUP Dr. Sardjito</td>
                                    <td>(0274) 567890</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>RS Bethesda</td>
                                    <td>(0274) 765432</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>RS PKU Muhammadiyah</td>
                                    <td>(0274) 412336</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>RS Panti Rapih</td>
                                    <td>(0274) 563333</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>RSUD Kota Yogyakarta</td>
                                    <td>(0274) 515865</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>RS UGM</td>
                                    <td>(0274) 4532000</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>RS Queen Latifa</td>
                                    <td>(0274) 658921</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <style>
        table.dataTable thead {
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }

        .card-header h4 {
            font-family: 'Fredoka', sans-serif;
            font-weight: 600;
        }

        img.img-thumbnail:hover {
            transform: scale(1.05);
            transition: all 0.3s ease-in-out;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>

    <style>
        body {
            background-color: #0b1f3a !important;
            /* biru dongker */
            color: #3877b6;
        }

        .card {
            background-color: #1e293b;
            color: rgb(177, 22, 22);
        }

        .card-header {
            background-color: #facc15;
            /* kuning cerah */
            color: #000000;
        }

        .table {
            background-color: #7a9ed6;
            color: rgb(79, 26, 94);
        }

        .dataTables_wrapper {
            background-color: #1e293b;
        }

        .table thead {
            background-color: #334155;
            color: #902659;
        }

        .container {
            background-color: transparent;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script>
        let tablepoints = new DataTable('#pointsTable');
    </script>
@endsection
