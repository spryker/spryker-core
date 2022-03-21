<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Shared\Kernel\ClassResolver\ResolverCacheFactoryInterface;
use Spryker\Shared\Kernel\ClassResolver\ResolverCacheManager;
use Spryker\Shared\Kernel\Validator\RedirectUrlValidator;
use Spryker\Shared\Kernel\Validator\RedirectUrlValidatorInterface;

/**
 * @method \Spryker\Zed\Kernel\KernelConfig getConfig()
 * @method \Spryker\Zed\Kernel\Business\KernelFacadeInterface getFacade()
 */
class KernelCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\ResolverCacheFactoryInterface
     */
    public function createResolverCacheManager(): ResolverCacheFactoryInterface
    {
        return new ResolverCacheManager();
    }

    /**
     * @return \Spryker\Shared\Kernel\Validator\RedirectUrlValidatorInterface
     */
    public function createRedirectUrlValidator(): RedirectUrlValidatorInterface
    {
        return new RedirectUrlValidator(
            $this->getConfig()->getDomainsAllowedForRedirect(),
            $this->getConfig()->isStrictDomainRedirectEnabled(),
        );
    }
}
