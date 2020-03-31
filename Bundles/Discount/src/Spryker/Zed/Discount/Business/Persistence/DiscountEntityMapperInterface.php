<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use Orm\Zed\Discount\Persistence\SpyDiscount;

interface DiscountEntityMapperInterface
{
    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function mapFromEntity(SpyDiscount $discountEntity);

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getMoneyValueCollectionForEntity(SpyDiscount $discountEntity);

    /**
     * @param int $idCurrency
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyByIdCurrency($idCurrency);
}
