@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #map {
            width: 100%;
            height: calc(90vh - 56px);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        body {
            background-color: #0b1f3a !important;
            color: #3877b6;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4 mb-4">
        <div class="card-header bg-primary text-white">
            <h5><i class="fa-solid fa-map-location-dot"></i> Peta Kejadian</h5>
        </div>
        <div class="border rounded shadow" style="width: 100%; height: 80vh;">
            <div id="map" style="width: 100%; height: 100%;"></div>
        </div>
    </div>

    <!-- Modal Edit Point -->
    <div class="modal fade" id="editPointModal" tabindex="-1" aria-labelledby="editPointModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPointModalLabel">Edit Titik Kejadian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Pelapor</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama pelapor" required>
                        </div>
                        <div class="mb-3">
                            <label for="decsription" class="form-label">Deskripsi Kejadian</label>
                            <textarea class="form-control" id="decsription" name="decsription" rows="3" placeholder="Deskripsi..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geom_point" class="form-label">Geometry (WKT)</label>
                            <textarea class="form-control" id="geom_point" name="geom_point" rows="3" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image_point" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="image_point" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                            <img src="https://via.placeholder.com/300" alt="Preview" id="preview-image-point" class="img-thumbnail mt-2" width="300">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        if (L.DomUtil.get('map') != null) {
            L.DomUtil.get('map')._leaflet_id = null;
        }

        const map = L.map('map').setView([-7.769917, 110.377838], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        const drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: false
            }
        });
        map.addControl(drawControl);

        const pointLayer = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                drawnItems.addLayer(layer);

                const props = feature.properties;
                const geometryWKT = Terraformer.geojsonToWKT(feature.geometry);

                layer.on({
                    click: function() {
                        $('#name').val(props.name || '');
                        $('#description').val(props.description || '');
                        $('#geom_point').val(geometryWKT);
                        $('#preview-image-point').attr(
                            'src',
                            props.image ? "{{ asset('storage/images') }}/" + props.image : 'https://via.placeholder.com/300'
                        );

                        const updateUrl = `{{ url('points') }}/${props.id}`;
                        $('#editForm').attr('action', updateUrl);

                        new bootstrap.Modal(document.getElementById('editPointModal')).show();
                    },
                    mouseover: function() {
                        layer.bindTooltip(props.name || 'Tanpa Nama').openTooltip();
                    }
                });
            }
        });

        $.getJSON("{{ route('api.point', $id) }}", function(data) {
            pointLayer.addData(data);
            map.addLayer(pointLayer);
            map.fitBounds(pointLayer.getBounds(), {
                padding: [100, 100]
            });
        });
    </script>
@endsection
