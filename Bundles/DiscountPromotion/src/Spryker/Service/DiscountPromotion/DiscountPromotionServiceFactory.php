<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DiscountPromotion;

use Spryker\Service\DiscountPromotion\FloatRounder\FloatRounder;
use Spryker\Service\DiscountPromotion\FloatRounder\FloatRounderInterface;
use Spryker\Service\Kernel\AbstractServiceFactory;

/**
 * @method \Spryker\Service\DiscountPromotion\DiscountPromotionConfig getConfig()
 */
class DiscountPromotionServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\DiscountPromotion\FloatRounder\FloatRounderInterface
     */
    public function createFloatRounder(): FloatRounderInterface
    {
        return new FloatRounder($this->getConfig());
    }
}
