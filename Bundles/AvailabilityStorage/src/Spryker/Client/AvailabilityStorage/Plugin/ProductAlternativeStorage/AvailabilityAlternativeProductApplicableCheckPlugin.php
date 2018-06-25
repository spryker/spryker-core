<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage\Plugin\ProductAlternativeStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductAlternativeStorageExtension\Dependency\Plugin\AlternativeProductApplicableCheckPluginInterface;

/**
 * @method \Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface getClient()
 * @method \Spryker\Client\AvailabilityStorage\AvailabilityStorageFactory getFactory()
 */
class AvailabilityAlternativeProductApplicableCheckPlugin extends AbstractPlugin implements AlternativeProductApplicableCheckPluginInterface
{
    /**
     * Specification:
     *  - Checks if product out of stock.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function check(ProductViewTransfer $productViewTransfer): bool
    {
        $productsAvailability = $this->getClient()
            ->getProductAvailabilityByIdProductAbstract($productViewTransfer->getIdProductAbstract())
            ->getConcreteProductAvailableItems();

        if (!$productsAvailability) {
            return true;
        }

        return isset($productsAvailability[$productViewTransfer->getSku()])
            ? !$productsAvailability[$productViewTransfer->getSku()]
            : true;
    }
}
