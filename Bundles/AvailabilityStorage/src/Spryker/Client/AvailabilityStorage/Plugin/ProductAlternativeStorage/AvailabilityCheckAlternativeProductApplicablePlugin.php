<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage\Plugin\ProductAlternativeStorage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductAlternativeStorageExtension\Dependency\Plugin\AlternativeProductApplicablePluginInterface;

/**
 * @method \Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface getClient()
 * @method \Spryker\Client\AvailabilityStorage\AvailabilityStorageFactory getFactory()
 */
class AvailabilityCheckAlternativeProductApplicablePlugin extends AbstractPlugin implements AlternativeProductApplicablePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return bool
     */
    public function check(ProductViewTransfer $productViewTransfer): bool
    {
        $concreteProductAvailableItems = $this->getClient()
            ->getProductAvailabilityByIdProductAbstract($productViewTransfer->getIdProductAbstract())
            ->getConcreteProductAvailableItems();

        if (!$concreteProductAvailableItems) {
            return true;
        }

        return isset($concreteProductAvailableItems[$productViewTransfer->getSku()])
            ? !$concreteProductAvailableItems[$productViewTransfer->getSku()]
            : true;
    }
}
