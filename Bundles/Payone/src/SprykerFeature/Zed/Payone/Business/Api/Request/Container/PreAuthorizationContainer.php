<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;

class PreAuthorizationContainer extends AbstractAuthorizationContainer
{

    /**
     * @var string
     */
    protected $request = self::REQUEST_TYPE_PREAUTHORIZATION;

}
