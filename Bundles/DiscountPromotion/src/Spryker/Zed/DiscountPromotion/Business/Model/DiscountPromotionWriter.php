<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface;

class DiscountPromotionWriter implements DiscountPromotionWriterInterface
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
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function save(DiscountPromotionTransfer $discountPromotionTransfer)
    {
        return $this->saveDiscountPromotion($discountPromotionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function update(DiscountPromotionTransfer $discountPromotionTransfer)
    {
        return $this->saveDiscountPromotion($discountPromotionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    protected function saveDiscountPromotion(DiscountPromotionTransfer $discountPromotionTransfer)
    {
        $discountPromotionTransfer->requireFkDiscount();

        $discountPromotionEntity = $this->getDiscountPromotionEntity($discountPromotionTransfer->getIdDiscountPromotion());
        $discountPromotionEntity = $this->hydrateDiscountPromotionEntity($discountPromotionEntity, $discountPromotionTransfer);

        $discountPromotionEntity->save();

        return $discountPromotionTransfer;
    }

    /**
     * @param int $idDiscountPromotion
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion
     */
    protected function getDiscountPromotionEntity($idDiscountPromotion)
    {
        if (!$idDiscountPromotion) {
            return $this->createDiscountPromotionEntity();
        }

        return $this->discountPromotionQueryContainer
            ->queryDiscountPromotionByIdDiscountPromotion($idDiscountPromotion)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion
     */
    protected function createDiscountPromotionEntity()
    {
        return new SpyDiscountPromotion();
    }

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion
     */
    protected function hydrateDiscountPromotionEntity(
        SpyDiscountPromotion $discountPromotionEntity,
        DiscountPromotionTransfer $discountPromotionTransfer
    ) {
        $discountPromotionEntity->fromArray($discountPromotionTransfer->toArray());

        return $discountPromotionEntity;
    }

}
