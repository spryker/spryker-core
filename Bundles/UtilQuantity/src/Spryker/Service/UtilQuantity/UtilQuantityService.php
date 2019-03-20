<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilQuantity\UtilQuantityServiceFactory getFactory()
 */
class UtilQuantityService extends AbstractService implements UtilQuantityServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param float $quantity
     *
     * @return float
     */
    public function roundQuantity(float $quantity): float
    {
        return $this->getFactory()
            ->createQuantityRounder()
            ->roundQuantity($quantity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    public function isQuantitiesEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->getFactory()
            ->createQuantityComparator()
            ->isQuantitiesEqual($firstQuantity, $secondQuantity);
    }
}
