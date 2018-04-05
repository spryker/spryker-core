<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage;

use Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageFactory getFactory()
 */
class ProductMeasurementUnitStorageClient extends AbstractClient implements ProductMeasurementUnitStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer|null
     */
    public function findProductMeasurementUnitStorage(int $idProductMeasurementUnit): ?ProductMeasurementUnitStorageTransfer
    {
        return $this->getFactory()
            ->createProductMeasurementUnitStorageReader()
            ->findProductMeasurementUnitStorage($idProductMeasurementUnit);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer|null
     */
    public function findProductConcreteMeasurementUnitStorage(int $idProduct): ?ProductConcreteMeasurementUnitStorageTransfer
    {
        return $this->getFactory()
            ->createProductConcreteMeasurementUnitStorageReader()
            ->findProductConcreteMeasurementUnitStorage($idProduct);
    }
}
