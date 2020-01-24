<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Reader;

use Generated\Shared\Transfer\DiscountPromotionTransfer;

interface DiscountPromotionReaderInterface
{
    /**
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByUuid(string $uuid): ?DiscountPromotionTransfer;
}
