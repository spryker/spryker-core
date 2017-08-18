<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery;

class DiscountPromotionReader implements DiscountPromotionReaderInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer;
     */
    public function expandDiscountPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $idDiscount = $discountConfiguratorTransfer->getDiscountGeneral()->getIdDiscount();

        $discountPromotionEntity = SpyDiscountPromotionQuery::create()->findOneByFkDiscount($idDiscount); //@todo move to container rename to promotion

        if (!$discountPromotionEntity) {
            return $discountConfiguratorTransfer;
        }

        $discountCalculatorTransfer = $discountConfiguratorTransfer->getDiscountCalculator();
        $discountCalculatorTransfer->setCollectorType('promotion');
        $discountCalculatorTransfer->setDiscountPromotion($this->hydrateDiscountPromotion($discountPromotionEntity));

        return $discountConfiguratorTransfer;
    }

    /**
     * @param int $idDiscount
     *
     * @return bool
     */
    public function isDiscountWithPromotion($idDiscount)
    {
        return SpyDiscountPromotionQuery::create()->filterByFkDiscount($idDiscount)->count() > 0;
    }

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    protected function hydrateDiscountPromotion(SpyDiscountPromotion $discountPromotionEntity)
    {
        $discountPromotionTransfer = new DiscountPromotionTransfer();
        $discountPromotionTransfer->fromArray($discountPromotionEntity->toArray(), true);

        return $discountPromotionTransfer;
    }

}
