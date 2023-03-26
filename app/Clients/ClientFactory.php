<?php

namespace App\Clients;

use Illuminate\Support\Str;
use App\Models\Retailer;

class ClientFactory
{
    public function make(Retailer $retailer): Client
    {
        $class = "App\\Clients\\" . Str::studly($retailer->name);

        if (!class_exists($class)) {
            throw new ClientException(sprintf('Client %s not found', $retailer->name));
        }

        return new $class;
    }
}
