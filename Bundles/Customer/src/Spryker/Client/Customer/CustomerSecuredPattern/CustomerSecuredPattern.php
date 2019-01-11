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
    protected $customerSecuredPatternRulePlugins;

    /**
     * @param \Spryker\Client\Customer\CustomerConfig $customerConfig
     * @param \Spryker\Client\CustomerExtension\Dependency\Plugin\CustomerSecuredPatternRulePluginInterface[] $customerSecuredPatternRulePlugins
     */
    public function __construct(
        CustomerConfig $customerConfig,
        array $customerSecuredPatternRulePlugins
    ) {
        $this->customerConfig = $customerConfig;
        $this->customerSecuredPatternRulePlugins = $customerSecuredPatternRulePlugins;
    }

    /**
     * @return string
     */
    public function getCustomerSecuredPattern(): string
    {
        $customerSecuredPattern = $this->customerConfig->getCustomerSecuredPattern();

        foreach ($this->customerSecuredPatternRulePlugins as $customerSecuredPatternRulePlugin) {
            if ($customerSecuredPatternRulePlugin->isApplicable()) {
                $customerSecuredPattern = $customerSecuredPatternRulePlugin->execute($customerSecuredPattern);
            }
        }

        return $customerSecuredPattern;
    }
}
