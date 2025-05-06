<?php

namespace App\Http\Controllers;

use App\Models\PolygonsModel;
use Illuminate\Http\Request;

class PolygonsController extends Controller
{

    public function __construct()
    {
        $this->polygon = new PolygonsModel();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // validate request
       $request->validate(
        [
            'name' => 'required|unique:polygon,name',
            'description' => 'required',
            'geom_polygon' => 'required',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ],
        [
            'name.required' => 'Name is required',
            'name.unique' => 'Name already exists',
            'description.required' => 'Description is required',
            'geom_polygon.required' => 'Geometry point is required',
        ]
    );

    // Create iage directory if not exists
    if (!is_dir('storage/images')) {
        mkdir('./storage/images', 0777);
     }

    // Get image file
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
          } else {
            $name_image = null;
          }

    $data = [
        'geom' => $request->geom_polygon,
        'name' => $request->name,
        'description' => $request->description,
        'image' => $name_image,
    ];

    // Create data
    if (!$this->polygon->create($data)) {
        return redirect()->route('map')->with('error', 'Polygons failed to add');
    }

    // Redirect to map
    return redirect()->route('map')->with('success', 'Polygons has been added');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile =$this->polygon->find($id)->image;

        if (!$this->polygon->destroy($id)) {
            return redirect()->route('map')->with('eror', 'polygon failed to delete');
        }

        //Delete image file
        if ($imagefile != null) {
            if (file_exists('./storage/images/' . $imagefile)) {
                 unlink('./storage/images/' .$imagefile);
            }
        }

        return redirect()->route('map')->with('success', 'polygon has been delete');
    }
}
