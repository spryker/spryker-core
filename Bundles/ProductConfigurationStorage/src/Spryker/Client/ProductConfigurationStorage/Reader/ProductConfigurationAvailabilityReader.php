<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Reader;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class ProductConfigurationAvailabilityReader implements ProductConfigurationAvailabilityReaderInterface
{
    protected const MINIMUM_AVAILABLE_QUANTITY = 0;

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
     *
     * @return bool
     */
    public function isProductHasProductConfigurationInstance(
        ProductViewTransfer $productViewTransfer
    ): bool {
        $productConfigurationInstance = $this->findProductConfigurationInstance($productViewTransfer);

        return $productConfigurationInstance !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductConcreteAvailable(
        ProductViewTransfer $productViewTransfer
    ): bool {
        $productConfigurationInstance = $this->findProductConfigurationInstance($productViewTransfer);

        if (!$productConfigurationInstance) {
            return false;
        }

        return $productConfigurationInstance->getAvailableQuantity() > static::MINIMUM_AVAILABLE_QUANTITY;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer|null
     */
    protected function findProductConfigurationInstance(
        ProductViewTransfer $productViewTransfer
    ): ?ProductConfigurationInstanceTransfer {
        $productConfigurationInstance = $productViewTransfer->getProductConfigurationInstance();

        if ($productConfigurationInstance) {
            return $productConfigurationInstance;
        }

        if (!$productViewTransfer->getIdProductConcrete()) {
            return null;
        }

        return $this->productConfigurationInstanceReader
            ->findProductConfigurationInstanceBySku($productViewTransfer->getSku());
    }
}
