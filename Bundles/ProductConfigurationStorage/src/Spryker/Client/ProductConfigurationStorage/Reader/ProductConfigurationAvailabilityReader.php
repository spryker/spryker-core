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
    /**
     * @var int
     */
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
    public function isProductHasProductConfigurationInstance(ProductViewTransfer $productViewTransfer): bool
    {
        $productConfigurationInstance = $this->findProductConfigurationInstance($productViewTransfer);

        return $productConfigurationInstance !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductConcreteAvailable(ProductViewTransfer $productViewTransfer): bool
    {
        $productConfigurationInstance = $this->findProductConfigurationInstance($productViewTransfer);

        if (!$productConfigurationInstance || $productConfigurationInstance->getAvailableQuantity() === null) {
            return (bool)$productViewTransfer->getAvailable();
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
        if (!$this->isProductConcrete($productViewTransfer)) {
            return null;
        }

        $productConfigurationInstance = $productViewTransfer->getProductConfigurationInstance();
        if ($productConfigurationInstance) {
            return $productConfigurationInstance;
        }

        return $this->productConfigurationInstanceReader
            ->findProductConfigurationInstanceBySku($productViewTransfer->getSkuOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    protected function isProductConcrete(ProductViewTransfer $productViewTransfer): bool
    {
        return $productViewTransfer->getIdProductConcrete() !== null;
    }
}
