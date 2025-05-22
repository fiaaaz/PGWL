<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolygonsModel extends Model
{
    protected $table = 'polygon';
    protected $guarded = ['id'];

    public function geojson_polygons()
    {
        $polygons = $this->select(DB::raw('
        polygon.id,
        st_asgeojson(polygon.geom) as geom,
        polygon.name,
        polygon.description,
        polygon.image,
        polygon.user_id,
        users.name as user_created,
        st_area(geom,true) as area_m,
        st_area(geom,true)/1000000 as area_km,
        st_area(geom,true)/10000 as area_hektar,
        polygon.created_at,
        polygon.updated_at
    '))
            ->leftJoin('users', 'polygon.user_id', '=', 'users.id')
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygons as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'area_m' => (float) $p->area_m,
                    'area_km' => (float) $p->area_km,
                    'area_hektar' => (float) $p->area_hektar,
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

    public function geojson_polygon($id)
    {
        $polygons = $this->select(DB::raw('id, st_asgeojson(geom) as geom, name, description, image,
        st_area(geom,true) as area_m, st_area(geom,true)/1000000 as area_km,
        st_area(geom,true)/10000 as area_hektar, created_at, updated_at'))
            ->where('id', $id)->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygons as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'area_m' => (float) $p->area_m,
                    'area_km' => (float) $p->area_km,
                    'area_hektar' => (float) $p->area_hektar,
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
