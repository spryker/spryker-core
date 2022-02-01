<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;

interface DiscountPromotionReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function expandDiscountPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer);

    /**
     * @param int $idDiscount
     *
     * @return bool
     */
    public function isDiscountWithPromotion($idDiscount);

    /**
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface::getDiscountPromotionCollection()} instead.
     *
     * @param int $idDiscountPromotion
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscountPromotion($idDiscountPromotion);

    /**
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface::getDiscountPromotionCollection()} instead.
     *
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscount($idDiscount);

    /**
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface::getDiscountPromotionCollection()} instead.
     *
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByUuid(string $uuid): ?DiscountPromotionTransfer;
}
