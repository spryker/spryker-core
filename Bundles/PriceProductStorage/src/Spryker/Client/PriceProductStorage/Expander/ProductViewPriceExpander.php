<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Expander;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;

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
     * @var \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    protected $priceProductService;

    /**
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceAbstractStorageReaderInterface $priceAbstractStorageReader
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceConcreteStorageReaderInterface $priceConcreteStorageReader
     * @param \Spryker\Service\PriceProduct\PriceProductServiceInterface $priceProductService
     */
    public function __construct(
        PriceAbstractStorageReaderInterface $priceAbstractStorageReader,
        PriceConcreteStorageReaderInterface $priceConcreteStorageReader,
        PriceProductServiceInterface $priceProductService
    ) {
        $this->priceAbstractStorageReader = $priceAbstractStorageReader;
        $this->priceConcreteStorageReader = $priceConcreteStorageReader;
        $this->priceProductService = $priceProductService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewPriceData(ProductViewTransfer $productViewTransfer)
    {
        $priceProductTransferCollection = $this->getProductViewPrices($productViewTransfer);

        $priceProductCriteriaTransfer = (new PriceProductCriteriaTransfer())
            ->setIdStore(1)
            ->setIdCurrency(1)
            ->setPriceMode()
            ->setPriceType('DEFAULT');
        $moneyValueTransfer = $this->priceProductService->resolveProductPrice($priceProductTransferCollection, $priceProductCriteriaTransfer);

        //$productViewTransfer->setPrices($currentProductPriceTransfer->getPrices());
        //todo resolve gross/net, currency
        $productViewTransfer->setPrice($moneyValueTransfer->getGrossAmount());

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return int|null
     */
    protected function findPriceByPriceMode(
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer,
        MoneyValueTransfer $moneyValueTransfer
    ) {
        if ($priceProductCriteriaTransfer->getPriceMode() === $this->priceProductMapper->getNetPriceModeIdentifier()) {
            return $moneyValueTransfer->getNetAmount();
        }

        return $moneyValueTransfer->getGrossAmount();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    protected function getProductViewPrices(ProductViewTransfer $productViewTransfer)
    {
        $priceProductAbstractStorageTransfer = $this->getPriceAbstractData($productViewTransfer);
        if (!$priceProductAbstractStorageTransfer) {
            return [];
        }

        $priceProductConcreteStorageTransfer = $this->getPriceConcreteData($productViewTransfer);
        if (!$priceProductConcreteStorageTransfer) {
            return $priceProductAbstractStorageTransfer->getPrices();
        }

        $productViewPriceData = array_replace_recursive(
            $priceProductAbstractStorageTransfer->getPrices(),
            $priceProductConcreteStorageTransfer->getPrices()
        );

        return $productViewPriceData;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    protected function getPriceAbstractData(ProductViewTransfer $productViewTransfer)
    {
        return $this->priceAbstractStorageReader->findPriceAbstractStorageTransfer($productViewTransfer->getIdProductAbstract());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    protected function getPriceConcreteData(ProductViewTransfer $productViewTransfer)
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return null;
        }

        return $this->priceConcreteStorageReader->findPriceConcreteStorageTransfer($productViewTransfer->getIdProductConcrete());
    }
}
