<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\Model;

use Spryker\Client\Customer\CustomerConfig;

class CustomerSecuredPattern implements CustomerSecuredPatternInterface
{
    /**
     * @var \Spryker\Client\Customer\CustomerConfig
     */
    protected $customerConfig;

    /**
     * @var \Spryker\Client\PermissionExtension\Dependency\Plugin\CustomerSecuredPatternPermissionPluginInterface[]
     */
    protected $customerSecuredPatternPermissionPlugins;

    /**
     * @param \Spryker\Client\Customer\CustomerConfig $customerConfig
     * @param \Spryker\Client\PermissionExtension\Dependency\Plugin\CustomerSecuredPatternPermissionPluginInterface[] $securedPatternPermissionPlugins
     */
    public function __construct(
        CustomerConfig $customerConfig,
        array $securedPatternPermissionPlugins
    ) {
        $this->customerConfig = $customerConfig;
        $this->customerSecuredPatternPermissionPlugins = $securedPatternPermissionPlugins;
    }

    /**
     * @return string
     */
    public function getCustomerSecuredPatternForUnauthenticatedCustomerAccess(): string
    {
        $securedPattern = $this->customerConfig->getCustomerSecuredPattern();

        foreach ($this->customerSecuredPatternPermissionPlugins as $customerSecuredPatternPermissionPlugin) {
            if ($customerSecuredPatternPermissionPlugin->isApplicable()) {
                $securedPattern = $customerSecuredPatternPermissionPlugin->execute($securedPattern);
            }
        }

        return $securedPattern;
    }
}
