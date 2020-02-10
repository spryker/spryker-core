<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUserAuthGuiPage\Dependency\Facade;

class MerchantUserAuthGuiPageToAuthFacadeBridge implements MerchantUserAuthGuiPageToAuthFacadeInterface
{
    /**
     * @var \Spryker\Zed\Auth\Business\AuthFacadeInterface
     */
    protected $authFacade;

    /**
     * @param \Spryker\Zed\Auth\Business\AuthFacadeInterface $authFacade
     */
    public function __construct($authFacade)
    {
        $this->authFacade = $authFacade;
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function login($username, $password)
    {
        return $this->authFacade->login($username, $password);
    }

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isAuthenticated($token)
    {
        return $this->authFacade->isAuthenticated($token);
    }

    /**
     * @return bool
     */
    public function hasCurrentUser()
    {
        return $this->authFacade->hasCurrentUser();
    }

    /**
     * @return string
     */
    public function getCurrentUserToken()
    {
        return $this->authFacade->getCurrentUserToken();
    }
}
