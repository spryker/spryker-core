<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Dependency\Service;

interface OfferToUtilQuantityServiceInterface
{
    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    public function isQuantityLessOrEqual(float $firstQuantity, float $secondQuantity): bool;
}
