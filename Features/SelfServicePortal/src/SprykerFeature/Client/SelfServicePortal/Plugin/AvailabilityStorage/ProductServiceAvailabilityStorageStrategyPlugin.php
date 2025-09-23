<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Client\SelfServicePortal\Plugin\AvailabilityStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\AvailabilityStorageStrategyPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class ProductServiceAvailabilityStorageStrategyPlugin extends AbstractPlugin implements AvailabilityStorageStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Determines if the product is a concrete service product with at least one product offer that has a service shipment type.
     * - Returns `false` if the product is not a concrete product.
     * - Returns `false` if the product does not have a SKU.
     * - Returns `false` if the product does not have the service product class name.
     * - Returns `false` if the product does not have at least one shipment type that matches the configured service availability shipment type keys.
     * - Returns `false` if the product does not have at least one product offer with service shipment types.
     * - Returns `true` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isApplicable(ProductViewTransfer $productViewTransfer): bool
    {
        return $this->getFactory()
            ->createProductServiceAvailabilityChecker()
            ->isApplicable($productViewTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns true if the product is available based on the current store and product offer availability.
     * - Checks availability only for offers with service shipment types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function isProductAvailable(ProductViewTransfer $productViewTransfer): bool
    {
        return $this->getFactory()
            ->createProductServiceAvailabilityChecker()
            ->isProductAvailable($productViewTransfer);
    }
}
