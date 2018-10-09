<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface;

/**
 * @method \Spryker\Client\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageClientInterface getClient()
 * @method \Spryker\Client\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageFactory getFactory()
 */
class ProductConcreteTransferBaseMeasurementUnitExpanderPlugin extends AbstractPlugin implements ProductConcreteExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands provided ProductConcreteTransfer with base measurement unit information if available for product.
     * - Returns the unchanged provided ProductConcreteTransfer when no base measurement unit is defined for the product.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expand(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getClient()->expandProductConcreteTransferWithBaseMeasurementUnit($productConcreteTransfer);
    }
}
