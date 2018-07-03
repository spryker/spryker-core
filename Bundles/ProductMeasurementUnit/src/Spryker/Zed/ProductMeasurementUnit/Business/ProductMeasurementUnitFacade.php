<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
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
    public function expandItemGroupKeyWithQuantitySalesUnit(ItemTransfer $itemTransfer): string
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
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnitsByIdProduct(int $idProduct): array
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitReader()
            ->getProductMeasurementSalesUnitTransfersByIdProduct($idProduct);
    }

    /**
     * Specification:
     * - Retrieves all product measurement sales units by ids.
     *
     * @api
     *
     * @param int[] $salesUnitsIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnitsByIds(array $salesUnitsIds): array
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitReader()
            ->getProductMeasurementSalesUnitTransfersByIds($salesUnitsIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnits(): array
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitReader()
            ->getProductMeasurementSalesUnitTransfers();
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
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findAllProductMeasurementUnitTransfers(): array
    {
        return $this->getRepository()->findAllProductMeasurementUnitTransfers();
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
    public function calculateQuantitySalesUnitValueInQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitValue()
            ->calculateSalesUnitValueInQuote($quoteTransfer);
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
    public function expandCartChangeWithQuantitySalesUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()
            ->createCartChangeExpander()
            ->expandWithQuantitySalesUnit($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function installProductMeasurementUnit(): void
    {
        $this->getFactory()
            ->createProductMeasurementUnitInstaller()
            ->install();
    }
}
