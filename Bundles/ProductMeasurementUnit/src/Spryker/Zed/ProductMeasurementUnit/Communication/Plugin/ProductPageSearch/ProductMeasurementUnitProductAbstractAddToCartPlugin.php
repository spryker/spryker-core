<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Communication\Plugin\ProductPageSearch;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractAddToCartPluginInterface;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig getConfig()
 * @method \Spryker\Zed\ProductMeasurementUnit\Business\ProductMeasurementUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductMeasurementUnit\Communication\ProductMeasurementUnitCommunicationFactory getFactory()
 */
class ProductMeasurementUnitProductAbstractAddToCartPlugin extends AbstractPlugin implements ProductAbstractAddToCartPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters out products which have measurement unit available and returns back modified array.
     * - Requires ProductConcreteTransfer::idProductConcrete to be set.
     * - Requires ProductConcreteTransfer::fkProductAbstract to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getEligibleConcreteProducts(array $productConcreteTransfers): array
    {
        return $this->getFacade()->filterProductsWithoutMeasurementUnit($productConcreteTransfers);
    }
}
