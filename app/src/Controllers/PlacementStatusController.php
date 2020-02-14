<?php
namespace App\Controllers;
use App\Models\PlacementStatus;

final class PlacementStatusController{

    public function checkIfPlacementIsDone($request, $response){
        $placement_status = PlacementStatus::first();
        $placement_done= $placement_status->placement_done;

            

        if ($placement_done)
        {
        
        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode('true', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }


        return $response->withStatus(200)
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode('false', JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

}

