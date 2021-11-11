<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Discount;

use Generated\Shared\Transfer\DiscountCalculationRequestTransfer;
use Generated\Shared\Transfer\DiscountCalculationResponseTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Discount\DiscountServiceFactory getFactory()
 */
class DiscountService extends AbstractService implements DiscountServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Please do not use this method. Exists only for internal purposes.
     *
     * @param \Generated\Shared\Transfer\DiscountCalculationRequestTransfer $discountCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountCalculationResponseTransfer
     */
    public function calculate(DiscountCalculationRequestTransfer $discountCalculationRequestTransfer): DiscountCalculationResponseTransfer
    {
        return $this->getFactory()
           ->createCalculator()
           ->calculate($discountCalculationRequestTransfer);
    }
}
