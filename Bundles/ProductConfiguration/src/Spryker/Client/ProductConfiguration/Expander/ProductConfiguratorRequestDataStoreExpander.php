<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface;

class ProductConfiguratorRequestDataStoreExpander implements ProductConfiguratorRequestDataExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToStoreClientInterface $storeClient
     */
    public function __construct(ProductConfigurationToStoreClientInterface $storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function expand(ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer): ProductConfiguratorRequestDataTransfer
    {
        return $productConfiguratorRequestDataTransfer->setStoreName(
            $this->storeClient->getCurrentStore()->getName()
        );
    }
}
