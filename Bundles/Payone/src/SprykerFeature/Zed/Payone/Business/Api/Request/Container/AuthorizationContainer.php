<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Api\Request\Container;

use Spryker\Shared\Payone\PayoneApiConstants;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\AbstractAuthorizationContainer;
use Spryker\Zed\Payone\Business\Api\Request\Container\Authorization\BusinessContainer;

class AuthorizationContainer extends AbstractAuthorizationContainer
{

    /**
     * @var string
     */
    protected $request = PayoneApiConstants::REQUEST_TYPE_AUTHORIZATION;

    /**
     * @var BusinessContainer
     */
    protected $business;

    /**
     * @param BusinessContainer $business
     *
     * @return self
     */
    public function setBusiness(BusinessContainer $business)
    {
        $this->business = $business;

        return $this;
    }

    /**
     * @return BusinessContainer
     */
    public function getBusiness()
    {
        return $this->business;
    }

}
