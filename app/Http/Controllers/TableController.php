<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointsModel;
use App\Models\PolylinesModel;
use App\Models\PolygonsModel;

class TableController extends Controller
{

    public function __construct ()
    {
        $this->points = new PointsModel();
        $this->polylines = new PolylinesModel();
        $this->polygon = new PolygonsModel();

    }

    public function index()
    {
    $data = [
        'title' => 'Table',
        'points' => $this->points->all(),
        'polylines' => $this->polylines->all(),
        'polygon' => $this->polygon->all(),
    ];

    return view('table', $data);
    }
}
