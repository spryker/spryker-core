<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Expander;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductClientInterface;
use Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceInterface;
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
     * @var \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface $priceAbstractStorageReader
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface $priceConcreteStorageReader
     * @param \Spryker\Client\PriceProductStorage\Dependency\Client\PriceProductStorageToPriceProductClientInterface $priceProductClient
     * @param \Spryker\Client\PriceProductStorage\Dependency\Service\PriceProductStorageToPriceProductServiceInterface $priceProductService
     */
    public function __construct(
        PriceAbstractStorageReaderInterface $priceAbstractStorageReader,
        PriceConcreteStorageReaderInterface $priceConcreteStorageReader,
        PriceProductStorageToPriceProductClientInterface $priceProductClient,
        PriceProductStorageToPriceProductServiceInterface $priceProductService
    ) {
        $this->priceAbstractStorageReader = $priceAbstractStorageReader;
        $this->priceConcreteStorageReader = $priceConcreteStorageReader;
        $this->priceProductClient = $priceProductClient;
        $this->priceProductService = $priceProductService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewPriceData(ProductViewTransfer $productViewTransfer)
    {
        $priceProductAbstractTransfers = $this->findPriceAbstractData($productViewTransfer);
        $priceProductConcreteTransfers = $this->findPriceConcreteData($productViewTransfer);

        if (empty($priceProductConcreteTransfers)) {
            $productViewTransfer = $this->setPrices($productViewTransfer, $priceProductAbstractTransfers);

            return $productViewTransfer;
        }

        $priceProductConcreteTransfers = $this->priceProductService->mergeConcreteAndAbstractPrices(
            $priceProductAbstractTransfers,
            $priceProductConcreteTransfers
        );

        $productViewTransfer = $this->setPrices($productViewTransfer, $priceProductConcreteTransfers);

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function setPrices(ProductViewTransfer $productViewTransfer, array $priceProductTransfers): ProductViewTransfer
    {
        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter(
            $priceProductTransfers,
            $this->getPriceProductFilterFromProductView($productViewTransfer)
        );

        return $productViewTransfer
            ->setCurrentProductPrice($currentProductPriceTransfer)
            ->setPrice($currentProductPriceTransfer->getPrice())
            ->setPrices($currentProductPriceTransfer->getPrices());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function findPriceAbstractData(ProductViewTransfer $productViewTransfer): array
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
    protected function findPriceConcreteData(ProductViewTransfer $productViewTransfer): array
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
