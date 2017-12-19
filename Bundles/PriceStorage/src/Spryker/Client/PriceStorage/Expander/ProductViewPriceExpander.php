<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceStorage\Expander;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\PriceStorage\Storage\PriceAbstractStorageReaderInterface;
use Spryker\Client\PriceStorage\Storage\PriceConcreteStorageReaderInterface;

class ProductViewPriceExpander implements ProductViewPriceExpanderInterface
{
    /**
     * @var PriceAbstractStorageReaderInterface
     */
    protected $priceAbstractStorageReader;

    /**
     * @var PriceConcreteStorageReaderInterface
     */
    protected $priceConcreteStorageReader;

    /**
     * @param PriceAbstractStorageReaderInterface $priceAbstractStorageReader
     * @param PriceConcreteStorageReaderInterface $priceConcreteStorageReader
     */
    public function __construct(PriceAbstractStorageReaderInterface $priceAbstractStorageReader, PriceConcreteStorageReaderInterface $priceConcreteStorageReader)
    {
        $this->priceAbstractStorageReader = $priceAbstractStorageReader;
        $this->priceConcreteStorageReader = $priceConcreteStorageReader;
    }

    /**
     * @param ProductViewTransfer $productViewTransfer
     *
     * @return ProductViewTransfer
     */
    public function expandProductViewPriceData(ProductViewTransfer $productViewTransfer)
    {
        $productViewPriceData = $this->getProductViewPrices($productViewTransfer);
        $productViewTransfer->fromArray($productViewPriceData, true);

        return $productViewTransfer;
    }

    /**
     * @param ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    protected function getProductViewPrices(ProductViewTransfer $productViewTransfer): array
    {
        $priceAbstractData = $this->getPriceAbstractData($productViewTransfer);
        $priceConcreteData = $this->getPriceConcreteData($productViewTransfer);

        $productViewPriceData = array_replace_recursive($priceAbstractData, $priceConcreteData);

        return $productViewPriceData;
    }

    /**
     * @param ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    protected function getPriceAbstractData(ProductViewTransfer $productViewTransfer)
    {
        $priceAbstractStorageTransfer = $this->priceAbstractStorageReader->findPriceAbstractStorageTransfer($productViewTransfer->getIdProductAbstract());

        return $this->convertToPriceViewData($priceAbstractStorageTransfer);
    }

    /**
     * @param ProductViewTransfer $productViewTransfer
     *
     * @return array
     */
    protected function getPriceConcreteData(ProductViewTransfer $productViewTransfer)
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return [];
        }

        $priceConcreteStorageTransfer = $this->priceConcreteStorageReader->findPriceConcreteStorageTransfer($productViewTransfer->getIdProductConcrete());

        if (!$priceConcreteStorageTransfer) {
            return [];
        }

        return $this->convertToPriceViewData($priceConcreteStorageTransfer);
    }

    /**
     * @param PriceProductStorageTransfer $priceProductStorageTransfer
     *
     * @return array
     */
    protected function convertToPriceViewData(PriceProductStorageTransfer $priceProductStorageTransfer)
    {
        if ($priceProductStorageTransfer->getDefaultPrice()) {
            $productViewPriceData[ProductViewTransfer::DEFAULT_PRICE] = $priceProductStorageTransfer->getDefaultPrice();
        }

        $prices = [];
        foreach ($priceProductStorageTransfer->getPrices() as $priceStorageTransfer) {
            $prices[$priceStorageTransfer->getType()] = $priceStorageTransfer->getPrice();
        }

        $productViewPriceData[ProductViewTransfer::PRICES] = $prices;

        return $productViewPriceData;
    }
}
