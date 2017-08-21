<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConstants;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface;

class DiscountPromotionReader implements DiscountPromotionReaderInterface
{

    /**
     * @var \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface
     */
    protected $discountPromotionQueryContainer;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface $discountPromotionQueryContainer
     */
    public function __construct(DiscountPromotionQueryContainerInterface $discountPromotionQueryContainer)
    {
        $this->discountPromotionQueryContainer = $discountPromotionQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer;
     */
    public function expandDiscountPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $discountConfiguratorTransfer->requireDiscountGeneral()
            ->requireDiscountCalculator();

        $idDiscount = $discountConfiguratorTransfer->getDiscountGeneral()->getIdDiscount();

        $discountPromotionEntity = $this->findDiscountPromotionByIdDiscount($idDiscount);
        if (!$discountPromotionEntity) {
            return $discountConfiguratorTransfer;
        }

        $discountCalculatorTransfer = $discountConfiguratorTransfer->getDiscountCalculator();
        $discountCalculatorTransfer->setCollectorType(DiscountPromotionConstants::DISCOUNT_COLLECTOR_STRATEGY);
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
        return $this->discountPromotionQueryContainer->queryDiscountPromotionByIdDiscount($idDiscount)->count() > 0;
    }

    /**
     * @param int $idDiscountPromotion
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function findDiscountPromotionByIdDiscountPromotion($idDiscountPromotion)
    {
        $discountPromotionEntity = $this->discountPromotionQueryContainer
            ->queryDiscountPromotionByIdDiscountPromotion($idDiscountPromotion)
            ->findOne();

        if (!$discountPromotionEntity) {
            return null;
        }

        return $this->hydrateDiscountPromotion($discountPromotionEntity);
    }

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    protected function hydrateDiscountPromotion(SpyDiscountPromotion $discountPromotionEntity)
    {
        $discountPromotionTransfer = new DiscountPromotionTransfer();
        $discountPromotionTransfer->fromArray($discountPromotionEntity->toArray(), true);

        return $discountPromotionTransfer;
    }

    /**
     * @param int $idDiscount
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion|null
     */
    protected function findDiscountPromotionByIdDiscount($idDiscount)
    {
        return $discountPromotionEntity = $this->discountPromotionQueryContainer
            ->queryDiscountPromotionByIdDiscount($idDiscount)
            ->findOne();
    }

}
