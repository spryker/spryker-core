<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Model;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;
use Spryker\Zed\DiscountPromotion\Business\Model\Mapper\DiscountPromotionMapperInterface;
use Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class DiscountPromotionWriter implements DiscountPromotionWriterInterface
{
    use DatabaseTransactionHandlerTrait;

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
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function create(DiscountPromotionTransfer $discountPromotionTransfer)
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

        $idDiscountPromotion = $this->handleDatabaseTransaction(function () use ($discountPromotionEntity, $discountPromotionTransfer) {
            return $this->executeSaveDiscountPromotionTransaction($discountPromotionEntity, $discountPromotionTransfer);
        });

        $discountPromotionTransfer->setIdDiscountPromotion($idDiscountPromotion);

        return $discountPromotionTransfer;
    }

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return int
     */
    protected function executeSaveDiscountPromotionTransaction(
        SpyDiscountPromotion $discountPromotionEntity,
        DiscountPromotionTransfer $discountPromotionTransfer
    ) {
        $this->removeCollectorQueryString($discountPromotionEntity);

        $discountPromotionEntity = $this->discountPromotionMapper->mapEntity(
            $discountPromotionEntity,
            $discountPromotionTransfer
        );
        $discountPromotionEntity->save();

        return $discountPromotionEntity->getIdDiscountPromotion();
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
     *
     * @return void
     */
    protected function removeCollectorQueryString(SpyDiscountPromotion $discountPromotionEntity)
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
