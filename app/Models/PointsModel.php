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
        $points_table = $this->select(DB::raw('
        points_tables.id,
        st_asgeojson(points_tables.geom) as geom,
        points_tables.name,
        points_tables.decsription,
        points_tables.created_at,
        points_tables.updated_at,
        points_tables.image,
        points_tables.user_id,
        users.name as user_created'))
            ->join('users', 'points_tables.user_id', '=', 'users.id')
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
                    'id' => $p->id,
                    'name' => $p->name,
                    'decsription' => $p->decsription,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'image' => $p->image,
                    'user_created' => $p->user_created,
                    'user_id' => $p->user_id,
                ],
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }

    public function geojson_point($id)
{
    $points_table = $this->select(DB::raw('
        id,
        st_asgeojson(points_tables.geom) as geom,
        name,
        decsription,
        created_at,
        updated_at,
        image
    '))
        ->where('id', $id)
        ->get();

    $geojson = [
        'type' => 'FeatureCollection',
        'features' => [],
    ];

    foreach ($points_table as $p) {
        $feature = [
            'type' => 'Feature',
            'geometry' => json_decode($p->geom),
            'properties' => [
                'id' => $p->id,
                'name' => $p->name,
                'decsription' => $p->decsription,
                'created_at' => $p->created_at,
                'updated_at' => $p->updated_at,
                'image' => $p->image,
            ],
        ];

        $geojson['features'][] = $feature;
    }

    return $geojson;
}
}
