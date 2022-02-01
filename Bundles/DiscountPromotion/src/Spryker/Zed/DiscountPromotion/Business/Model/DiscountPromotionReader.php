<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountPromotionConditionsTransfer;
use Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface;

class DiscountPromotionReader implements DiscountPromotionReaderInterface
{
    /**
     * @var \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface
     */
    protected $discountPromotionRepository;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface $discountPromotionRepository
     */
    public function __construct(DiscountPromotionRepositoryInterface $discountPromotionRepository)
    {
        $this->discountPromotionRepository = $discountPromotionRepository;
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

        $discountPromotionCriteriaTransfer = $this->createDiscountPromotionCriteriaTransferWithIdDiscountCondition($idDiscount);

        $discountPromotionTransfer = $this->discountPromotionRepository
            ->findDiscountPromotionByCriteria($discountPromotionCriteriaTransfer);

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
        $discountPromotionCriteriaTransfer = $this->createDiscountPromotionCriteriaTransferWithIdDiscountCondition($idDiscount);

        return $this->discountPromotionRepository->hasDiscountPromotion($discountPromotionCriteriaTransfer);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepository::getDiscountPromotionCollection()} instead.
     *
     * @param int $idDiscountPromotion
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscountPromotion($idDiscountPromotion)
    {
        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addIdDiscountPromotion($idDiscountPromotion);
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        return $this->discountPromotionRepository->findDiscountPromotionByCriteria($discountPromotionCriteriaTransfer);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepository::getDiscountPromotionCollection()} instead.
     *
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByIdDiscount($idDiscount)
    {
        $discountPromotionCriteriaTransfer = $this->createDiscountPromotionCriteriaTransferWithIdDiscountCondition($idDiscount);

        return $this->discountPromotionRepository->findDiscountPromotionByCriteria($discountPromotionCriteriaTransfer);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepository::getDiscountPromotionCollection()} instead.
     *
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer|null
     */
    public function findDiscountPromotionByUuid(string $uuid): ?DiscountPromotionTransfer
    {
        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addUuid($uuid);
        $discountPromotionCriteriaTransfer = (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);

        return $this->discountPromotionRepository->findDiscountPromotionByCriteria($discountPromotionCriteriaTransfer);
    }

    /**
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionCriteriaTransfer
     */
    protected function createDiscountPromotionCriteriaTransferWithIdDiscountCondition(int $idDiscount): DiscountPromotionCriteriaTransfer
    {
        $discountPromotionConditionsTransfer = (new DiscountPromotionConditionsTransfer())
            ->addIdDiscount($idDiscount);

        return (new DiscountPromotionCriteriaTransfer())
            ->setDiscountPromotionConditions($discountPromotionConditionsTransfer);
    }
}
