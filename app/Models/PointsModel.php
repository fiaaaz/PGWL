<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PointsModel extends Model
{
    protected $table = 'points_tables';

    protected $guarded = ['id'];

    public function geojson_points()
    {
        $points_table = $this->select(DB::raw('st_asgeojson(geom) as geom, name, decsription, created_at, updated_at, image'))
            ->get();

        // Buat struktur GeoJSON
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points_table as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom), // Ubah string JSON menjadi objek
                'properties' => [
                    'name' => $p->name,
                    'decsription' => $p->decsription,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'image' => $p->image,
                ],
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }
}
