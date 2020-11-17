<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface;

class ProductConfiguratorRequestDataCustomerExpander implements ProductConfiguratorRequestDataExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCustomerClientInterface $customerClient
     */
    public function __construct(ProductConfigurationToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function expand(ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer): ProductConfiguratorRequestDataTransfer
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if ($customerTransfer) {
            $productConfiguratorRequestDataTransfer->setCustomerReference(
                $customerTransfer->getCustomerReference()
            );
        }

        return $productConfiguratorRequestDataTransfer;
    }
}
