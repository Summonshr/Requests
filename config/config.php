<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    // remove this field,  if you want to register route yourself
    'route_name' => 'resource',
    'default_method' => function($method) {
        return match ($method) {
            'store' => 'POST',
            'create' => 'POST',
            'edit' => 'PUT',
            'destroy' => 'DELETE',
            'show' => 'GET',
            default => $method
        };
    },
];
