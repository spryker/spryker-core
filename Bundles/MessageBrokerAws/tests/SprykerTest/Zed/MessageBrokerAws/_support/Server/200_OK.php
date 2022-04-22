<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

$body = $request->getContent();

$response = new Response($body, 200);
$response->send();
