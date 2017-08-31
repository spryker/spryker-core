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

class DiscountPromotionWriter implements DiscountPromotionWriterInterface
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
        $discountPromotionEntity = $this->discountPromotionMapper->mapEntity($discountPromotionEntity, $discountPromotionTransfer);

        $discountPromotionEntity->save();

        $discountPromotionTransfer->setIdDiscountPromotion($discountPromotionEntity->getIdDiscountPromotion());

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

}
