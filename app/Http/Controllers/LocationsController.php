<?php

namespace App\Http\Controllers;

use App\Location;
use App\ImageCollection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationsController extends Controller
{
	/**
     * Constructor, add authorization middleware to controller
     */    
	public function __construct()
	{
		$this->middleware('admin', ['except' => ['index', 'show']]);
	}	
	
	/**
     * Display a listing of the locations.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$locations = Location::all();
		
		return view('locations.index', ['locations' => $locations]);
    }

    /**
     * Show the form for creating a new location.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Store a newly created location in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$location = new Location();
		
		$location->fillData($request->all()); 
		
		Auth::user()->locations()->save($location);
				
		return redirect('locations'); 
    }

    /**
     * Display the specified location.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
		// Temporary! обработка страничек администратора, формат: .blade.php, images: array	via ImageCollection
		if ($location->id < 9) {
			
			$collection = new ImageCollection();
			$images = $collection->get($location->page);
			
			return view('locations.locations.' . ($location->page ? : 'default'), compact('images'));	
		
		// обработка страничек пользователей, формат: .html, images: embedded  links
		} else {			
		
			$contents = $location->getContents();
			
			return view('locations.locations.default', compact('location', 'contents'));
		}
	}

    /**
     * Show the form for editing the specified location.
     *
     * @param  \App\Location $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
		return view('locations.edit', compact('location'));
    }

    /**
     * Update the specified location in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
		$location->fillData($request->all()); 		
		
		$location->save(); 

		return redirect('locations'); 		
    }

    /**
     * Remove the specified location from storage.
     *
     * @param  \App\Location $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {        
		// delete location page file
		if($location->page) {			
			$filePage = base_path() . '/resources/views/locations/locations/' . $location->page . '.html';
			unlink($filePage);
		}
		
		// delete location image file
		if($location->image) {
			$fileImage = base_path() . '/public/media/' . $location->image;
			unlink($fileImage);
		}
		
		$location->delete();
		
		return redirect('locations');
    }		
}
