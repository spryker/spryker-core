<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Expander;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface;
use Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface;

class ProductViewPriceExpander implements ProductViewPriceExpanderInterface
{
    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface
     */
    protected $priceAbstractStorageReader;

    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface
     */
    protected $priceConcreteStorageReader;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface
     */
    protected $priceProductClient;

    /**
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface $priceAbstractStorageReader
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface $priceConcreteStorageReader
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductInterface $priceProductClient
     */
    public function __construct(
        PriceAbstractStorageReaderInterface $priceAbstractStorageReader,
        PriceConcreteStorageReaderInterface $priceConcreteStorageReader,
        PriceProductStorageToPriceProductInterface $priceProductClient
    ) {
        $this->priceAbstractStorageReader = $priceAbstractStorageReader;
        $this->priceConcreteStorageReader = $priceConcreteStorageReader;
        $this->priceProductClient = $priceProductClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewPriceData(ProductViewTransfer $productViewTransfer)
    {
        $priceProductAbstractTransfers = $this->getPriceAbstractData($productViewTransfer);
        $currentProductAbstractPriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter(
            $priceProductAbstractTransfers,
            $this->getPriceProductFilterFromProductView($productViewTransfer)
        );

        $priceProductConcreteTransfers = $this->getPriceConcreteData($productViewTransfer);
        if (!$priceProductConcreteTransfers) {
            return $productViewTransfer
                ->setPrice($currentProductAbstractPriceTransfer->getPrice())
                ->setPrices($currentProductAbstractPriceTransfer->getPrices());
        }

        $currentProductConcretePriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter(
            $priceProductConcreteTransfers,
            $this->getPriceProductFilterFromProductView($productViewTransfer)
        );

        if (!$currentProductConcretePriceTransfer->getPrice()) {
            return $productViewTransfer
                ->setPrice($currentProductAbstractPriceTransfer->getPrice())
                ->setPrices($currentProductAbstractPriceTransfer->getPrices());
        }

        $currentProductPriceTransfer = (new CurrentProductPriceTransfer())->fromArray(
            array_replace_recursive(
                $currentProductAbstractPriceTransfer->toArray(),
                $currentProductConcretePriceTransfer->toArray()
            )
        );

        return $productViewTransfer
            ->setPrice($currentProductPriceTransfer->getPrice())
            ->setPrices($currentProductPriceTransfer->getPrices());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceAbstractData(ProductViewTransfer $productViewTransfer): array
    {
        if (!$productViewTransfer->getIdProductAbstract()) {
            return [];
        }

        return $this->priceAbstractStorageReader->findPriceProductAbstractTransfers($productViewTransfer->getIdProductAbstract());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceConcreteData(ProductViewTransfer $productViewTransfer): array
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return [];
        }

        return $this->priceConcreteStorageReader->findPriceProductConcreteTransfers($productViewTransfer->getIdProductConcrete());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function getPriceProductFilterFromProductView(ProductViewTransfer $productViewTransfer): PriceProductFilterTransfer
    {
        return (new PriceProductFilterTransfer())
            ->setQuantity($productViewTransfer->getQuantity());
    }
}
