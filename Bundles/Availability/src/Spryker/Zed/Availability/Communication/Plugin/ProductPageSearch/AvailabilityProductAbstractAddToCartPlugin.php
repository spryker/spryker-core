<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication\Plugin\ProductPageSearch;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractAddToCartPluginInterface;

/**
 * @method \Spryker\Zed\Availability\AvailabilityConfig getConfig()
 * @method \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface getFacade()
 * @method \Spryker\Zed\Availability\Communication\AvailabilityCommunicationFactory getFactory()
 * @method \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface getQueryContainer()
 */
class AvailabilityProductAbstractAddToCartPlugin extends AbstractPlugin implements ProductAbstractAddToCartPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters out products which are not available and returns back modified array.
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
        return $this->getFacade()->filterAvailableProducts($productConcreteTransfers);
    }
}
