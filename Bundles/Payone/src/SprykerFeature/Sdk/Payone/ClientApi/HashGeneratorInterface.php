<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\Payone\ClientApi;
use SprykerFeature\Sdk\Payone\ClientApi\Request\AbstractRequest;


interface HashGeneratorInterface
{

    public function generateHash(AbstractRequest $request, $securityKey);

}