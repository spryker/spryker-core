<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionPersistenceFactory getFactory()
 */
class DiscountPromotionEntityManager extends AbstractEntityManager implements DiscountPromotionEntityManagerInterface
{
    /**
     * @param int $idDiscount
     *
     * @return void
     */
    public function removePromotionByIdDiscount(int $idDiscount): void
    {
        $this->getFactory()
            ->createDiscountPromotionQuery()
            ->filterByFkDiscount($idDiscount)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function createDiscountPromotion(DiscountPromotionTransfer $discountPromotionTransfer): DiscountPromotionTransfer
    {
        $mapper = $this->getFactory()
            ->createDiscountPromotionMapper();
        $discountPromotionEntity = $mapper->mapDiscountPromotionTransferToEntity(
            $discountPromotionTransfer,
            new SpyDiscountPromotion()
        );

        $discountPromotionEntity->save();

        $this->removeCollectorQueryString($discountPromotionEntity);

        return $mapper->mapDiscountPromotionEntityToTransfer($discountPromotionEntity, new DiscountPromotionTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function updateDiscountPromotion(DiscountPromotionTransfer $discountPromotionTransfer): DiscountPromotionTransfer
    {
        $discountPromotionEntity = $this->getFactory()
            ->createDiscountPromotionQuery()
            ->findOneByIdDiscountPromotion($discountPromotionTransfer->getIdDiscountPromotion());

        if ($discountPromotionEntity === null) {
            return new DiscountPromotionTransfer();
        }

        $mapper = $this->getFactory()
            ->createDiscountPromotionMapper();
        $discountPromotionEntity = $mapper->mapDiscountPromotionTransferToEntity(
            $discountPromotionTransfer,
            $discountPromotionEntity
        );

        $discountPromotionEntity->save();

        $this->removeCollectorQueryString($discountPromotionEntity);

        return $mapper->mapDiscountPromotionEntityToTransfer($discountPromotionEntity, new DiscountPromotionTransfer());
    }

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     *
     * @return void
     */
    protected function removeCollectorQueryString(SpyDiscountPromotion $discountPromotionEntity): void
    {
        /** @var \Orm\Zed\Discount\Persistence\SpyDiscount|null $discountEntity */
        $discountEntity = $discountPromotionEntity->getDiscount();
        if (!$discountEntity) {
            return;
        }

        $discountEntity->setCollectorQueryString('');
        $discountEntity->save();
    }
}
