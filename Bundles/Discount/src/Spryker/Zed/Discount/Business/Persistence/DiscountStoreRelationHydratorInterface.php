<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use Orm\Zed\Discount\Persistence\SpyDiscount;

interface DiscountStoreRelationHydratorInterface
{
    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function hydrate(SpyDiscount $discountEntity);
}
