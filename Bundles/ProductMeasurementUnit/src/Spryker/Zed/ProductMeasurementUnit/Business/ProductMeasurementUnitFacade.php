<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepositoryInterface getRepository()
 */
class ProductMeasurementUnitFacade extends AbstractFacade implements ProductMeasurementUnitFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function expandItemGroupKeyWithSalesUnit(ItemTransfer $itemTransfer): string
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitItemGroupKeyGenerator()
            ->expandItemGroupKey($itemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    public function calculateQuantityNormalizedSalesUnitValue(ItemTransfer $itemTransfer): int
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitValue()
            ->calculateQuantityNormalizedSalesUnitValue($itemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getBaseUnitByIdProduct(int $idProduct): ProductMeasurementBaseUnitTransfer
    {
        return $this->getFactory()
            ->createProductMeasurementBaseUnitReader()
            ->getProductMeasurementBaseUnitTransferByIdProduct($idProduct);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnitsByIdProduct(int $idProduct): array
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitReader()
            ->getProductMeasurementSalesUnitTransfersByIdProduct($idProduct);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[] Keys are product measurement unit IDs, values are product measurement unit codes.
     */
    public function getProductMeasurementUnitCodeMap(): array
    {
        return $this->getRepository()->getProductMeasurementUnitCodeMap();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array
    {
        return $this->getRepository()->findProductMeasurementUnitTransfers($productMeasurementUnitIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function normalizeSalesUnitValueInQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitValue()
            ->normalizeSalesUnitValueInQuote($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()
            ->createCartChangeTransferExpander()
            ->expandCartChangeTransfer($cartChangeTransfer);
    }
}
