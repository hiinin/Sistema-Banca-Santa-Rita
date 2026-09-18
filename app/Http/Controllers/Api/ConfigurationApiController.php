<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConfigurationResource;
use App\Models\Configuration;

class ConfigurationApiController extends Controller
{
    /**
     * Display the public configuration of the newsstand.
     */
    public function show(): ConfigurationResource
    {
        $config = Configuration::current();

        return new ConfigurationResource($config);
    }
}
