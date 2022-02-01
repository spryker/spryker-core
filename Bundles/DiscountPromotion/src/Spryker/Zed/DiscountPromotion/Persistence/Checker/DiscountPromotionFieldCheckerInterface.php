<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence\Checker;

interface DiscountPromotionFieldCheckerInterface
{
    /**
     * @deprecated Will be removed in next major release.
     *
     * @return bool
     */
    public function isAbstractSkusFieldExists(): bool;

    /**
     * @deprecated Will be removed in next major release.
     *
     * @return bool
     */
    public function isUuidFieldExists(): bool;
}
