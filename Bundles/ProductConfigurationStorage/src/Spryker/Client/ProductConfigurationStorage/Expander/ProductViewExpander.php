<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Expander;

use Generated\Shared\Transfer\ProductStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface;

class ProductViewExpander implements ProductViewExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface
     */
    protected $productConfigurationInstanceReader;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Reader\ProductConfigurationInstanceReaderInterface $productConfigurationInstanceReader
     */
    public function __construct(
        ProductConfigurationInstanceReaderInterface $productConfigurationInstanceReader
    ) {
        $this->productConfigurationInstanceReader = $productConfigurationInstanceReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     * @param \Generated\Shared\Transfer\ProductStorageCriteriaTransfer|null $productStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithProductConfigurationInstance(
        ProductViewTransfer $productViewTransfer,
        array $productData,
        string $localeName,
        ?ProductStorageCriteriaTransfer $productStorageCriteriaTransfer = null
    ): ProductViewTransfer {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return $productViewTransfer;
        }

        $productConfigurationInstanceTransfer = $this->productConfigurationInstanceReader
            ->findProductConfigurationInstanceBySku($productViewTransfer->getSku());

        if (!$productConfigurationInstanceTransfer) {
            return $productViewTransfer;
        }

        $productViewTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        return $productViewTransfer;
    }
}
