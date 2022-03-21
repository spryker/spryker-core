<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

/**
 * @method \Spryker\Shared\Kernel\KernelConfig getSharedConfig()
 */
class KernelConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Checks if strict domain redirect is enabled.
     * - When enabled, only the domains from the list returned by {@link \Spryker\Yves\Kernel\KernelConfig::getDomainsAllowedForRedirect()} are allowed for redirects.
     *
     * @api
     *
     * @return bool
     */
    public function isStrictDomainRedirectEnabled(): bool
    {
        return $this->getSharedConfig()->isStrictDomainRedirectEnabled();
    }

    /**
     * Specification:
     * - Gets the list of domains/subdomains allowed for redirects.
     *
     * @api
     *
     * @return array<string>
     */
    public function getDomainsAllowedForRedirect(): array
    {
        return $this->getSharedConfig()->getDomainsAllowedForRedirect();
    }
}
