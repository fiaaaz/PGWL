@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    <!-- Modal Edit Point-->
    <div class="modal fade" id="EditPointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('points.update', $id) }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill Point Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="decsription" class="form-label">Description</label>
                            <textarea class="form-control" id="decsription" name="decsription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geom_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_point" name="geom_point" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
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

    <!-- Modal Create Polyline-->

    <!-- Modal Create Polygon-->
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        var map = L.map('map').setView([-7.7699170401252955, 110.37783827806732], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: false
            }
        });

        map.addControl(drawControl);

        map.on('draw:edited', function(e) {
            var layers = e.layers;

            layers.eachLayer(function(layer) {
                var drawnJSONObject = layer.toGeoJSON();
                console.log(drawnJSONObject);

                var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);
                console.log(objectGeometry);

                // layer properties
                var properties = drawnJSONObject.properties;
                console.log(properties);

                // menampilkan data yang sudah ada ke modal
                $('#name').val(properties.name);
                $('#decsription').val(properties.description);
                $('#geom_point').val(objectGeometry);
                $('#preview-image-point').attr('src', "{{ asset('storage/images') }}/" + properties.image);

                drawnItems.addLayer(layer);
                //menampilkan modal edit
                $('#EditPointModal').modal('show');
            });
        });

        var point = L.geoJson(null, {
            onEachFeature: function(feature, layer) {

                drawnItems.addLayer(layer);

                var properties = feature.properties;
                var objectGeometry = Terraformer.geojsonToWKT((feature.geometry));

                layer.on({
                    click: function(e) {
                        // menampilkan data yang sudah ada ke modal
                        $('#name').val(properties.name);
                        $('#decsription').val(properties.description);
                        $('#geom_point').val(objectGeometry);
                        $('#preview-image-point').attr('src', "{{ asset('storage/images') }}/" +
                            properties.image);

                        drawnItems.addLayer(layer);
                        //menampilkan modal edit
                        $('#editPointModal').modal('show');
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name).openTooltip();
                    }
                });
            }
        });

        $.getJSON("{{ route('api.point', $id) }}", function(data) {
            point.addData(data);
            map.addLayer(point);
            map.fitBounds(point.getBounds(), {
                padding: [100, 100]
            });
        });
    </script>
@endsection
