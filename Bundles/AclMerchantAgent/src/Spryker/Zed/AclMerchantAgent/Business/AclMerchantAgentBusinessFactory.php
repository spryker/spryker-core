<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantAgent\Business;

use Spryker\Zed\AclMerchantAgent\AclMerchantAgentDependencyProvider;
use Spryker\Zed\AclMerchantAgent\Business\Checker\MerchantAgentAclAccessChecker;
use Spryker\Zed\AclMerchantAgent\Business\Checker\MerchantAgentAclAccessCheckerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @method \Spryker\Zed\AclMerchantAgent\AclMerchantAgentConfig getConfig()
 */
class AclMerchantAgentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AclMerchantAgent\Business\Checker\MerchantAgentAclAccessCheckerInterface
     */
    public function createMerchantAgentAclAccessChecker(): MerchantAgentAclAccessCheckerInterface
    {
        return new MerchantAgentAclAccessChecker($this->getConfig(), $this->getAuthorizationCheckerService());
    }

    /**
     * @return \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    public function getAuthorizationCheckerService(): AuthorizationCheckerInterface
    {
        return $this->getProvidedDependency(AclMerchantAgentDependencyProvider::SERVICE_SECURITY_AUTHORIZATION_CHECKER);
    }
}
