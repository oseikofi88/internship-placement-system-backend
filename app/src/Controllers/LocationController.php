<?php
namespace App\Controllers;

use App\Models\Location;


final class LocationController
{



    public function registerLocation($request, $response, $args)
    {
       $data = $request->getParsedBody();
       $location = new Location();
       $location->name = $data['name'];
       $location->longitude = $data['longitude'];
       $location->latitude = $data['latitude'];
       
        $location->save();
            return $response->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode($location, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        

      }

    }
