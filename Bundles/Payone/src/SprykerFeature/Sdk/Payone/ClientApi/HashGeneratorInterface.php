<?php

namespace SprykerFeature\Sdk\Payone\ClientApi;
use SprykerFeature\Sdk\Payone\ClientApi\Request\AbstractRequest;


interface HashGeneratorInterface
{

    public function generateHash(AbstractRequest $request, $securityKey);

}