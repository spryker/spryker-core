<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedChecker;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

class ProductDiscontinuedChecker implements ProductDiscontinuedCheckerInterface
{
    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface
     */
    protected $productDiscontinuedStorageReader;

    /**
     * @param \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader
     */
    public function __construct(
        ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader
    ) {
        $this->productDiscontinuedStorageReader = $productDiscontinuedStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return bool
     */
    public function isProductDiscontinued(ProductViewTransfer $productViewTransfer, string $localeName): bool
    {
        if ($productViewTransfer->getIdProductConcrete()) {
            return $this->isProductConcreteDiscontinued($productViewTransfer->getSku(), $localeName);
        }

        return $this->isProductAbstractDiscontinued($productViewTransfer, $localeName);
    }

    /**
     * @param string $concreteSku
     * @param string $localeName
     *
     * @return bool
     */
    protected function isProductConcreteDiscontinued(string $concreteSku, string $localeName): bool
    {
        return (bool)$this->productDiscontinuedStorageReader->findProductDiscontinuedStorage($concreteSku, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return bool
     */
    protected function isProductAbstractDiscontinued(ProductViewTransfer $productViewTransfer, string $localeName): bool
    {
        $attributeMap = $productViewTransfer->getAttributeMap();
        if (!$attributeMap) {
            return false;
        }
        foreach (array_keys($attributeMap->getProductConcreteIds()) as $concreteSku) {
            if (!$this->isProductConcreteDiscontinued($concreteSku, $localeName)) {
                return false;
            }
        }

        return true;
    }
}
