<?php

test('route is registered', function () {
    expect(app()->router->getRoutes()->hasNamedRoute(config('requests.route_name')))->toBeTrue();
});