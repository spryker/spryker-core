<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence\Checker;

use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;

class DiscountPromotionFieldChecker implements DiscountPromotionFieldCheckerInterface
{
    /**
     * @var string
     */
    protected const FIELD_ABSTRACT_SKUS = 'abstract_skus';

    /**
     * @var string
     */
    protected const FIELD_UUID = 'uuid';

    /**
     * @deprecated Will be removed in next major release.
     *
     * @return bool
     */
    public function isAbstractSkusFieldExists(): bool
    {
        return property_exists(SpyDiscountPromotion::class, static::FIELD_ABSTRACT_SKUS);
    }

    /**
     * @deprecated Will be removed in next major release.
     *
     * @return bool
     */
    public function isUuidFieldExists(): bool
    {
        return property_exists(SpyDiscountPromotion::class, static::FIELD_UUID);
    }
}
