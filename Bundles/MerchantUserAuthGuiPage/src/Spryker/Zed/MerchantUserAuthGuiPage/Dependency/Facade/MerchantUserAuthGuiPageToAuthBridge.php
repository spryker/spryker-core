<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserAuthGuiPage\Dependency\Facade;

class MerchantUserAuthGuiPageToAuthBridge implements MerchantUserAuthGuiPageToAuthBridgeInterface
{
    /**
     * @var \Spryker\Zed\Auth\Business\AuthFacadeInterface
     */
    private $authFacade;

    /**
     * @param \Spryker\Zed\Auth\Business\AuthFacadeInterface $authFacade
     */
    public function __construct($authFacade)
    {
        $this->authFacade = $authFacade;
    }

    /**
     * @inheritDoc
     */
    public function login($username, $password)
    {
        return $this->authFacade->login($username, $password);
    }

    /**
     * @inheritDoc
     */
    public function isAuthenticated($token)
    {
        return $this->authFacade->isAuthenticated($token);
    }

    /**
     * @inheritDoc
     */
    public function hasCurrentUser()
    {
        return $this->authFacade->hasCurrentUser();
    }

    /**
     * @inheritDoc
     */
    public function getCurrentUserToken()
    {
        return $this->authFacade->getCurrentUserToken();
    }
}
