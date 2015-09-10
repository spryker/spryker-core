<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Key;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;

interface HashGeneratorInterface
{

    /**
     * @param AbstractRequestContainer $request
     * @param string $securityKey
     *
     * @return string
     */
    public function generateParamHash(AbstractRequestContainer $request, $securityKey);

}
