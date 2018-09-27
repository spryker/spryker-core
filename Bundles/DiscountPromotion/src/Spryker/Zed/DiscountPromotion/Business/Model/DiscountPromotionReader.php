<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\DiscountPromotion\Business\Model\Mapper\DiscountPromotionMapperInterface;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface;

class DiscountPromotionReader implements DiscountPromotionReaderInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface
     */
    protected $discountPromotionQueryContainer;

    /**
     * @var \Spryker\Zed\DiscountPromotion\Business\Model\Mapper\DiscountPromotionMapperInterface
     */
    protected $discountPromotionMapper;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface $discountPromotionQueryContainer
     * @param \Spryker\Zed\DiscountPromotion\Business\Model\Mapper\DiscountPromotionMapperInterface $discountPromotionMapper
     */
    public function __construct(
        DiscountPromotionQueryContainerInterface $discountPromotionQueryContainer,
        DiscountPromotionMapperInterface $discountPromotionMapper
    ) {

        $this->discountPromotionQueryContainer = $discountPromotionQueryContainer;
        $this->discountPromotionMapper = $discountPromotionMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    public function expandDiscountPromotion(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $discountConfiguratorTransfer->requireDiscountGeneral()
            ->requireDiscountCalculator();

        $idDiscount = $discountConfiguratorTransfer->getDiscountGeneral()->getIdDiscount();

        $discountPromotionTransfer = $this->findDiscountPromotionByIdDiscount($idDiscount);
        if (!$discountPromotionTransfer) {
            return $discountConfiguratorTransfer;
        }

        $discountCalculatorTransfer = $discountConfiguratorTransfer->getDiscountCalculator();
        $discountCalculatorTransfer->setCollectorStrategyType(DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY);
        $discountCalculatorTransfer->setDiscountPromotion($discountPromotionTransfer);

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
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscountPromotion($idDiscountPromotion)
    {
        $discountPromotionEntity = $this->discountPromotionQueryContainer
            ->queryDiscountPromotionByIdDiscountPromotion($idDiscountPromotion)
            ->findOne();

        if (!$discountPromotionEntity) {
            return null;
        }

        return $this->discountPromotionMapper->mapTransfer($discountPromotionEntity);
    }

    /**
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscount($idDiscount)
    {
        $discountPromotionEntity = $this->discountPromotionQueryContainer
            ->queryDiscountPromotionByIdDiscount($idDiscount)
            ->findOne();

        if (!$discountPromotionEntity) {
            return null;
        }

        return $this->discountPromotionMapper->mapTransfer($discountPromotionEntity);
    }
}
