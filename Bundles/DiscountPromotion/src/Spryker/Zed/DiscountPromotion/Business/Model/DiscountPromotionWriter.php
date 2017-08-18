<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotionQuery;

class DiscountPromotionWriter implements DiscountPromotionWriterInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function save(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        return $this->saveDiscountPromotion($discountConfiguratorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function update(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        return $this->saveDiscountPromotion($discountConfiguratorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function saveDiscountPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        if ($discountConfiguratorTransfer->getDiscountCalculator()->getCollectorType() != 'promotion') {
            return $discountConfiguratorTransfer;
        }

        $discountPromotionTransfer = $discountConfiguratorTransfer->getDiscountCalculator()->getDiscountPromotion();
        $idDiscount = $discountConfiguratorTransfer->getDiscountGeneral()->getIdDiscount();

        $idDiscountPromotion = $discountPromotionTransfer->getIdDiscountPromotion();

        $discountPromotionEntity = $this->getDiscountPromotionEntity($idDiscountPromotion);

        $discountPromotionEntity->setFkDiscount($idDiscount);
        $discountPromotionEntity->fromArray($discountPromotionTransfer->modifiedToArray());
        $discountPromotionEntity->save();

        return $discountConfiguratorTransfer;
    }

    /**
     * @param int $idDiscountPromotion
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion
     */
    protected function getDiscountPromotionEntity($idDiscountPromotion)
    {
        if (!$idDiscountPromotion) {
            return new SpyDiscountPromotion();
        }

        return SpyDiscountPromotionQuery::create()
            ->filterByIdDiscountPromotion($idDiscountPromotion)
            ->findOne();
    }

}
