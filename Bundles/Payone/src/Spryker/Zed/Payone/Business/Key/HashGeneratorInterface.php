<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Key;

use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface HashGeneratorInterface
{

    /**
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $request
     * @param string $securityKey
     *
     * @return string
     */
    public function generateParamHash(AbstractRequestContainer $request, $securityKey);

}
