<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthenticationMerchantPortalGui\Dependency\Facade;

interface AuthenticationMerchantPortalGuiToAuthFacadeInterface
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function login($username, $password);

    /**
     * @return void
     */
    public function logout();

    /**
     * @param string $token
     *
     * @return bool
     */
    public function isAuthenticated($token);

    /**
     * @return bool
     */
    public function hasCurrentUser();

    /**
     * @return string
     */
    public function getCurrentUserToken();
}
