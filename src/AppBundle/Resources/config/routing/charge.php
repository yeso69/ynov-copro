<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('charge_index', new Route(
    '/',
    array('_controller' => 'AppBundle:Charge:index'),
    array(),
    array(),
    '',
    array(),
    array('GET')
));

$collection->add('charge_show', new Route(
    '/{id}/show',
    array('_controller' => 'AppBundle:Charge:show'),
    array(),
    array(),
    '',
    array(),
    array('GET')
));

$collection->add('charge_new', new Route(
    '/new',
    array('_controller' => 'AppBundle:Charge:new'),
    array(),
    array(),
    '',
    array(),
    array('GET', 'POST')
));

$collection->add('charge_edit', new Route(
    '/{id}/edit',
    array('_controller' => 'AppBundle:Charge:edit'),
    array(),
    array(),
    '',
    array(),
    array('GET', 'POST')
));

$collection->add('charge_delete', new Route(
    '/{id}/delete',
    array('_controller' => 'AppBundle:Charge:delete'),
    array(),
    array(),
    '',
    array(),
    array('DELETE')
));

return $collection;
