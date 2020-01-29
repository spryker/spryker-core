<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Dependency\Facade;

class MerchantUserToAuthFacadeBridge implements MerchantUserToAuthFacadeInterface
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
     * @param string $email
     *
     * @return bool
     */
    public function requestPasswordReset($email)
    {
        return $this->authFacade->requestPasswordReset($email);
    }
}
