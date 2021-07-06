<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Availability;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Availability\AvailabilityServiceFactory getFactory()
 */
class AvailabilityService extends AbstractService implements AvailabilityServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $productConcretesNeverOutOfStockSet
     *
     * @return bool
     */
    public function isAbstractProductNeverOutOfStock(string $productConcretesNeverOutOfStockSet): bool
    {
        return $this->getFactory()
            ->createStockChecker()
            ->isAbstractProductNeverOutOfStock($productConcretesNeverOutOfStockSet);
    }
}
