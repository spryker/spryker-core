<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Anonymizer;

use Generated\Shared\Transfer\CustomerTransfer;

class CustomerAnonymizer implements CustomerAnonymizerInterface
{

    /**
     * @var \Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface[]
     */
    protected $plugins;

    /**
     * CustomerAnonymizer constructor.
     *
     * @param \Spryker\Zed\Customer\Dependency\Plugin\CustomerAnonymizerPluginInterface[] $customerAnonymizerPlugins
     */
    public function __construct(array $customerAnonymizerPlugins)
    {
        $this->plugins = $customerAnonymizerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function process(CustomerTransfer $customerTransfer)
    {
        foreach ($this->plugins as $plugin) {
            $customerTransfer = $plugin->processCustomer($customerTransfer);
        }

        return $customerTransfer;
    }

}
