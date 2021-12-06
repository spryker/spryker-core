<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

interface DiscountStoreRelationReaderInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface::getDiscountStoreRelations()} instead.
     *
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function getStoreRelation($idDiscount);
}
