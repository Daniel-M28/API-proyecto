<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class UbicacionController extends Controller
{
    public function obtenerUbicacion($direccion)
    {
        $url = "https://nominatim.openstreetmap.org/search?q={$direccion}&format=json";

        $response = Http::get($url);

        $datosUbicacion = $response->json();

        // Procesa los datos de ubicación según tus necesidades
        // ...

        return $datosUbicacion;
    }
}
