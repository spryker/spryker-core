<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Stock\Business\StockBusinessFactory getFactory()
 */
class StockFacade extends AbstractFacade implements StockFacadeInterface
{

    /**
     * @api
     *
     * @param string $sku
     *
     * @return bool
     */
    public function isNeverOutOfStock($sku)
    {
        return $this->getFactory()->createReaderModel()->isNeverOutOfStock($sku);
    }

    /**
     * @api
     *
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getFactory()->createCalculatorModel()->calculateStockForProduct($sku);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function createStockType(TypeTransfer $stockTypeTransfer)
    {
        return $this->getFactory()->createWriterModel()->createStockType($stockTypeTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function updateStockType(TypeTransfer $stockTypeTransfer)
    {
        return $this->getFactory()->createWriterModel()->updateStockType($stockTypeTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockProductTransfer $transferStockProduct)
    {
        return $this->getFactory()->createWriterModel()->createStockProduct($transferStockProduct);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $stockProductTransfer)
    {
        return $this->getFactory()->createWriterModel()->updateStockProduct($stockProductTransfer);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param string $stockType
     * @param int $decrementBy
     *
     * @return void
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1)
    {
        $this->getFactory()->createWriterModel()->decrementStock($sku, $stockType, $decrementBy);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param string $stockType
     * @param int $incrementBy
     *
     * @return void
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1)
    {
        $this->getFactory()->createWriterModel()->incrementStock($sku, $stockType, $incrementBy);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param string $stockType
     *
     * @return bool
     */
    public function hasStockProduct($sku, $stockType)
    {
        return $this->getFactory()->createReaderModel()->hasStockProduct($sku, $stockType);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param string $stockType
     *
     * @return int
     */
    public function getIdStockProduct($sku, $stockType)
    {
        return $this->getFactory()->createReaderModel()->getIdStockProduct($sku, $stockType);
    }

    /**
     * Specification:
     * - Processes all provided stocks of the concrete product transfer
     * - If a stock entry from the collection doesn't exists for the product, then it will be newly created.
     * - If a stock entry from the collection exists for the product, then it will be updated with the provided data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function persistStockProductCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createWriterModel()
            ->persistStockProductCollection($productConcreteTransfer);
    }

    /**
     * Specification:
     * - Expands concrete product transfer (by the ID of the product) with it's stock information from the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithStocks(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createReaderModel()
            ->expandProductConcreteWithStocks($productConcreteTransfer);
    }

}
