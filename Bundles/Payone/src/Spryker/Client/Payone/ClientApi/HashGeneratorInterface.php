<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payone\ClientApi;

use Spryker\Client\Payone\ClientApi\Request\AbstractRequest;

interface HashGeneratorInterface
{

    public function generateHash(AbstractRequest $request, $securityKey);

}
