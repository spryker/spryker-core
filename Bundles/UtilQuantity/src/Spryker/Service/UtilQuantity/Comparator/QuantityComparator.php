<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilQuantity\Comparator;

use Spryker\Service\UtilQuantity\Rounder\QuantityRounderInterface;

class QuantityComparator implements QuantityComparatorInterface
{
    /**
     * @var \Spryker\Service\UtilQuantity\Rounder\QuantityRounderInterface
     */
    protected $quantityRounder;

    /**
     * @param \Spryker\Service\UtilQuantity\Rounder\QuantityRounderInterface $quantityRounder
     */
    public function __construct(QuantityRounderInterface $quantityRounder)
    {
        $this->quantityRounder = $quantityRounder;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    public function isQuantitiesEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->quantityRounder->roundQuantity($firstQuantity) === $this->quantityRounder->roundQuantity($secondQuantity);
    }
}
