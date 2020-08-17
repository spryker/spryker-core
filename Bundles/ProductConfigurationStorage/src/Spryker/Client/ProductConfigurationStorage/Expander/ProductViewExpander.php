<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface;

class ProductViewExpander implements ProductViewExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface
     */
    protected $configurationInstanceReader;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface $configurationInstanceReader
     */
    public function __construct(
        ProductConfigurationInstanceReaderInterface $configurationInstanceReader
    ) {
        $this->configurationInstanceReader = $configurationInstanceReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandWithProductConfigurationInstance(
        ProductViewTransfer $productViewTransfer
    ): productViewTransfer {
        $productConfigurationInstance = $this->configurationInstanceReader
            ->findProductConfigurationInstanceBySku($productViewTransfer->getSku());

        $productViewTransfer->setProductConfigurationInstance($productConfigurationInstance);

        return $productViewTransfer;
    }
}
