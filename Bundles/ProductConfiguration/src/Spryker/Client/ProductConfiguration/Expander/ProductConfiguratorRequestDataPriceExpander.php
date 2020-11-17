<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface;

class ProductConfiguratorRequestDataPriceExpander implements ProductConfiguratorRequestDataExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToPriceClientInterface $priceClient
     */
    public function __construct(ProductConfigurationToPriceClientInterface $priceClient)
    {
        $this->priceClient = $priceClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function expand(ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer): ProductConfiguratorRequestDataTransfer
    {
        return $productConfiguratorRequestDataTransfer->setPriceMode(
            $this->priceClient->getCurrentPriceMode()
        );
    }
}
