<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer;
use Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer;
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
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer
     */
    public function getSalesUnitEntity(int $idProductMeasurementSalesUnit): SpyProductMeasurementSalesUnitEntityTransfer
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitReader()
            ->getProductMeasurementSalesUnitEntity($idProductMeasurementSalesUnit);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getBaseUnitByIdProduct(int $idProduct): SpyProductMeasurementBaseUnitEntityTransfer
    {
        return $this->getFactory()
            ->createProductMeasurementBaseUnitReader()
            ->getProductMeasurementBaseUnitEntityByIdProduct($idProduct);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getSalesUnitsByIdProduct(int $idProduct): array
    {
        return $this->getFactory()
            ->createProductMeasurementSalesUnitReader()
            ->getProductMeasurementSalesUnitEntitiesByIdProduct($idProduct);
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
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer[]
     */
    public function findProductMeasurementUnitEntities(array $productMeasurementUnitIds): array
    {
        return $this->getRepository()->findProductMeasurementUnitEntities($productMeasurementUnitIds);
    }
}
