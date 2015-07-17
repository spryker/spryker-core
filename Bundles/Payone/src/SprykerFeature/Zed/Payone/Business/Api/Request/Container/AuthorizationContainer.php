<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer;

class AuthorizationContainer extends AbstractAuthorizationContainer
{

    /**
     * @var string
     */
    protected $request = self::REQUEST_TYPE_AUTHORIZATION;

    /**
     * @var \SprykerFeature\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer
     */
    protected $business;

    /**
     * @param Authorization\BusinessContainer $business
     */
    public function setBusiness(BusinessContainer $business)
    {
        $this->business = $business;
    }

    /**
     * @return null|Authorization\BusinessContainer
     */
    public function getBusiness()
    {
        return $this->business;
    }

}
