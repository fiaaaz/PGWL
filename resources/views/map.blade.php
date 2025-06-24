@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        <link rel="stylesheet" href="..."><style>body {
            background-color: #1e1e2f;
            color: white;
        }

        .modal-content {
            background-color: #2c2f4a;
        }

        .alert {
            background-color: rgba(255, 255, 255, 0.05);
        }

        #map {
            border: 2px solid #444;
        }
    </style>
    <style>
        body {
            background-color: #0b1f3a !important;
            /* biru dongker */
            color: #3877b6;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-3 px-4">
        <div class="row">
            {{-- Sidebar Statistik --}}
            <div class="col-md-3 mb-3">
                <div class="text-white">
                    <div class="alert bg-primary shadow-sm">
                        <strong>Total Titik:</strong> <span id="total-count">0</span>
                    </div>
                    <div class="alert bg-info shadow-sm">
                        <strong>Laporan Hari Ini:</strong> <span id="today-count">0</span>
                    </div>
                </div>
            </div>

            {{-- Kolom Peta --}}
            <div class="col-md-9">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fa-solid fa-map-location-dot"></i> Peta Kejadian</h5>
                </div>
                <div class="border rounded shadow" style="width: 100%; height: 80vh; overflow: hidden;">
                    <div id="map" style="width: 100%; height: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tetap di Bawah --}}
    <!-- Modal Create Point-->
    <div class="modal fade" id="CreatePointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('points.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pelapor</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill Point Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="decsription" class="form-label">Deskrpsi Kejadian</label>
                            <textarea class="form-control" id="decsription" name="decsription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geom_point" class="form-label">Koordinat TKP</label>
                            <textarea class="form-control" id="geom_point" name="geom_point" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Photo Kejadian</label>
                            <input type="file" class="form-control" id="image_point" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail"
                                width="300">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $.getJSON("{{ route('api.points') }}", function(data) {
            point.addData(data);
            map.addLayer(point);

            // === Statistik Real-time ===
            const totalCount = data.features.length;
            const uniqueNames = new Set();
            const today = new Date().toISOString().slice(0, 10); // yyyy-mm-dd
            let todayCount = 0;

            data.features.forEach(f => {
                uniqueNames.add(f.properties.name);

                const createdAt = f.properties.created_at.slice(0, 10);
                if (createdAt === today) {
                    todayCount++;
                }
            });

            document.getElementById('total-count').innerText = totalCount;
            document.getElementById('unique-reporters').innerText = uniqueNames.size;
            document.getElementById('today-count').innerText = todayCount;
        });

        if (map !== undefined) {
            map.remove();
        }

        var map = L.map('map').setView([-7.7699170401252955, 110.37783827806732], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                polyline: false,
                polygon: false,
                rectangle: false,
                circle: false,
                marker: true,
                circlemarker: false
            },
            edit: false
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            if (type === 'marker') {
                $('#geom_point').val(objectGeometry);
                $('#CreatePointModal').modal('show');
            }

            drawnItems.addLayer(layer);
        });


        var point = L.geoJson(null, {
            onEachFeature: function(feature, layer) {

                var routedelete = "{{ route('points.destroy', ':id') }}"
                routedelete = routedelete.replace(':id', feature.properties.id);

                var routeedit = "{{ route('points.edit', ':id') }}"
                routeedit = routeedit.replace(':id', feature.properties.id);

                var popupContent = "Name: " + feature.properties.name + "<br>" +
                    "Description: " + feature.properties.decsription + "<br>" +
                    "Created: " + feature.properties.created_at + "<br>" +
                    "<img src='{{ asset('storage/images') }}/" + feature.properties.image +
                    "' width='200' alt=''>" +
                    "<br>" +
                    "<div class='row mt-4' >" +
                    "<div class='col-6 text-end'>" +
                    "<a href='" + routeedit +
                    "' class='btn-warning btn-lg'><i class='fa-solid fa-pen-to-square'></i></a>" +
                    "</div>" +
                    " <div class='col-6'>" +
                    "<form method ='POST' action='" + routedelete + "'>" +
                    '@csrf' + '@method('DELETE')' +

                    "<button type='submit' class='btn btn-danger' onclick='return confirm(`Are you sure?`)' btn-sm'><i class='fa-solid fa-trash-can'></i></button>" +
                    "</form>" +
                    "</div>" +
                    "</div>" +
                    "</div>" + "<br>" + "<p>Dibuat Oleh: " + feature.properties.user_created + "</p>";

                layer.on({
                    click: function(e) {
                        layer.bindPopup(popupContent).openPopup();
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name).openTooltip();
                    }
                });
            }
        });

        $.getJSON("{{ route('api.points') }}", function(data) {
            point.addData(data);
            map.addLayer(point);
        });

        var baseMaps = {
            "OpenStreetMap": L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png')
        };

        var overlayMaps = {
            "Points": point
        };

        L.control.layers(baseMaps, overlayMaps, {
            collapsed: false
        }).addTo(map);
    </script>
@endsection
