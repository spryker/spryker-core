<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Agent\Sanitizer;

use Generated\Shared\Transfer\CustomerTransfer;

class CustomerImpersonationSanitizer implements CustomerImpersonationSanitizerInterface
{
    /**
     * @var \Spryker\Client\AgentExtension\Dependency\Plugin\CustomerImpersonationSanitizerPluginInterface[]
     */
    protected $customerImpersonationSanitizerPlugins;

    /**
     * @param \Spryker\Client\AgentExtension\Dependency\Plugin\CustomerImpersonationSanitizerPluginInterface[] $customerImpersonationSanitizerPlugins
     */
    public function __construct(array $customerImpersonationSanitizerPlugins)
    {
        $this->customerImpersonationSanitizerPlugins = $customerImpersonationSanitizerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function sanitizeCustomerImpersonation(CustomerTransfer $customerTransfer): void
    {
        foreach ($this->customerImpersonationSanitizerPlugins as $customerImpersonationSanitizerPlugin) {
            $customerImpersonationSanitizerPlugin->sanitize($customerTransfer);
        }
    }
}
