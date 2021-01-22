<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface;

class ProductConfiguratorRequestDataLocaleExpander implements ProductConfiguratorRequestDataExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToLocaleInterface $localeClient
     */
    public function __construct(ProductConfigurationToLocaleInterface $localeClient)
    {
        $this->localeClient = $localeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer
     */
    public function expand(ProductConfiguratorRequestDataTransfer $productConfiguratorRequestDataTransfer): ProductConfiguratorRequestDataTransfer
    {
        return $productConfiguratorRequestDataTransfer->setLocaleName(
            $this->localeClient->getCurrentLocale()
        );
    }
}
