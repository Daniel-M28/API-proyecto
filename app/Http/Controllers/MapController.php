<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MapController extends Controller
{
    public function index()
    {
        return view('map');
    }

    public function geocode(Request $request)
    {
        $address = $request->input('address');
    
        $client = new Client();
        $response = $client->get("https://nominatim.openstreetmap.org/search?format=json&q={$address}");
    
        $data = json_decode($response->getBody(), true);
    
        if (!empty($data)) {
            $coordinates = [
                'lat' => $data[0]['lat'],
                'lon' => $data[0]['lon'],
            ];
    
            return response()->json($coordinates);
        } else {
            return response()->json(['error' => 'No se encontraron coordenadas para la dirección proporcionada'], 404);
        }
    }
    
}
