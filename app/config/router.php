<?php
//@todo vymysliet router, spravit config file
// Create the router
$router = new \Phalcon\Mvc\Router();

//Define a route
$router->add(
	"/person/:action/:id",
	array(
		"action" => 1,
		"id"     => 2
	)
);

$router->handle();