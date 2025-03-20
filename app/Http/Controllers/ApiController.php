<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use Illuminate\Http\Request;
use App\Models\PolygonsModel;
use App\Models\PolylinesModel;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel();
        $this->polylines = new PolylinesModel();
        $this->polygon = new PolygonsModel();
    }

    public function points()
    {
        $points_table = $this->points->geojson_points();

        return response()->json($points_table);
    }

    public function polylines()
    {
        $polylines = $this->polylines->geojson_polylines();

        return response()->json($polylines);
    }
    public function polygons()
    {
        $polygon = $this->polygon->geojson_polygons();

        return response()->json($polygon);
    }

}
