<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentSecurityMerchantPortalGui\Dependency\Facade;

class AgentSecurityMerchantPortalGuiToSecurityFacadeBridge implements AgentSecurityMerchantPortalGuiToSecurityFacadeInterface
{
    /**
     * @var \Spryker\Zed\Security\Business\SecurityFacadeInterface
     */
    protected $securityFacade;

    /**
     * @param \Spryker\Zed\Security\Business\SecurityFacadeInterface $securityFacade
     */
    public function __construct($securityFacade)
    {
        $this->securityFacade = $securityFacade;
    }

    /**
     * @return bool
     */
    public function isUserLoggedIn(): bool
    {
        return $this->securityFacade->isUserLoggedIn();
    }
}
