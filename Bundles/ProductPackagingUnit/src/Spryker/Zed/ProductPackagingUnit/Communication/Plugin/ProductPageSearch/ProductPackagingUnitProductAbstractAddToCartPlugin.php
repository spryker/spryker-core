<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Communication\Plugin\ProductPageSearch;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractAddToCartPluginInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPackagingUnit\Communication\ProductPackagingUnitCommunicationFactory getFactory()
 */
class ProductPackagingUnitProductAbstractAddToCartPlugin extends AbstractPlugin implements ProductAbstractAddToCartPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters out products which have packaging unit available and returns back modified array.
     * - Requires ProductConcreteTransfer::idProductConcrete to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getEligibleConcreteProducts(array $productConcreteTransfers): array
    {
        return $this->getFacade()->filterProductsWithoutPackagingUnit($productConcreteTransfers);
    }
}
