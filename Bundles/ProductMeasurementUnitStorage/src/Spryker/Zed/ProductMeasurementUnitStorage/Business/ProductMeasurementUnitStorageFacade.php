<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Business\ProductMeasurementUnitStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\ProductMeasurementUnitStorageRepositoryInterface getRepository()
 */
class ProductMeasurementUnitStorageFacade extends AbstractFacade implements ProductMeasurementUnitStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     *
     * @return void
     */
    public function publishProductMeasurementUnit(array $productMeasurementUnitIds): void
    {
        $this->getFactory()->createProductMeasurementUnitStorageWriter()->publish($productMeasurementUnitIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishProductConcreteMeasurementUnit(array $productIds): void
    {
        $this->getFactory()->createProductConcreteMeasurementUnitStorageWriter()->publish($productIds);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function findAllProductMeasurementUnitTransfers(): array
    {
        return $this->getFactory()->getProductMeasurementUnitFacade()->findAllProductMeasurementUnitTransfers();
    }

    /**
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array
    {
        return $this->getFactory()->getProductMeasurementUnitFacade()->findProductMeasurementUnitTransfers($productMeasurementUnitIds);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function getSalesUnits(): array
    {
        return $this->getFactory()->getProductMeasurementUnitFacade()->getSalesUnits();
    }

    /**
     * @api
     *
     * @param int[] $salesUnitsIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function getSalesUnitsByIds(array $salesUnitsIds): array
    {
        return $this->getFactory()->getProductMeasurementUnitFacade()->getSalesUnitsByIds($salesUnitsIds);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function findFilteredProductMeasurementUnitTransfers(FilterTransfer $filterTransfer): array
    {
        return $this->getFactory()->getProductMeasurementUnitFacade()->findFilteredProductMeasurementUnitTransfers($filterTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function findFilteredProductMeasurementSalesUnitTransfers(FilterTransfer $filterTransfer): array
    {
        return $this->getFactory()->getProductMeasurementUnitFacade()->findFilteredProductMeasurementSalesUnitTransfers($filterTransfer);
    }
}
