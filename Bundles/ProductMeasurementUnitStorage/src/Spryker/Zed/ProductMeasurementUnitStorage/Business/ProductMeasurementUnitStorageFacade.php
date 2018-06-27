<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\Business\ProductMeasurementUnitStorageBusinessFactory getFactory()
 */
class ProductMeasurementUnitStorageFacade extends AbstractFacade implements ProductMeasurementUnitStorageFacadeInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findAllProductMeasurementUnitTransfers()
    {
        return $this->getFactory()->getProductMeasurementUnitFacade()->findAllProductMeasurementUnitTransfers();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function getSalesUnits()
    {
        return $this->getFactory()->getProductMeasurementUnitFacade()->getSalesUnits();
    }
}
