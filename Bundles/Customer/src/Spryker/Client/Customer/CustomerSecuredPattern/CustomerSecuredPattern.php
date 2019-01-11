<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Customer\CustomerSecuredPattern;

use Spryker\Client\Customer\CustomerConfig;

class CustomerSecuredPattern implements CustomerSecuredPatternInterface
{
    /**
     * @var \Spryker\Client\Customer\CustomerConfig
     */
    protected $customerConfig;

    /**
     * @var \Spryker\Client\CustomerExtension\Dependency\Plugin\CustomerSecuredPatternRulePluginInterface[]
     */
    protected $customerSecuredPatternPermissionPlugins;

    /**
     * @param \Spryker\Client\Customer\CustomerConfig $customerConfig
     * @param \Spryker\Client\CustomerExtension\Dependency\Plugin\CustomerSecuredPatternRulePluginInterface[] $customerSecuredPatternPermissionPlugins
     */
    public function __construct(
        CustomerConfig $customerConfig,
        array $customerSecuredPatternPermissionPlugins
    ) {
        $this->customerConfig = $customerConfig;
        $this->customerSecuredPatternPermissionPlugins = $customerSecuredPatternPermissionPlugins;
    }

    /**
     * @return string
     */
    public function getCustomerSecuredPatternForUnauthenticatedCustomerAccess(): string
    {
        $customerSecuredPattern = $this->customerConfig->getCustomerSecuredPattern();

        foreach ($this->customerSecuredPatternPermissionPlugins as $customerSecuredPatternPermissionPlugin) {
            if ($customerSecuredPatternPermissionPlugin->isApplicable()) {
                $customerSecuredPattern = $customerSecuredPatternPermissionPlugin->execute($customerSecuredPattern);
            }
        }

        return $customerSecuredPattern;
    }
}
