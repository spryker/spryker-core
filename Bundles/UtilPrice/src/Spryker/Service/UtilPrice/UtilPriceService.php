<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilPrice;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilPrice\UtilPriceServiceFactory getFactory()
 */
class UtilPriceService extends AbstractService implements UtilPriceServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param float $price
     *
     * @return int
     */
    public function roundPrice(float $price): int
    {
        return $this->getFactory()
            ->createPriceRounder()
            ->roundPrice($price);
    }
}
