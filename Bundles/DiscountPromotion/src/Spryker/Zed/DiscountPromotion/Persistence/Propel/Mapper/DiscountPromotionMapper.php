<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\DiscountPromotionCollectionTransfer;
use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\DiscountPromotion\Persistence\Checker\DiscountPromotionFieldCheckerInterface;

class DiscountPromotionMapper
{
    /**
     * @uses \Spryker\Zed\DiscountPromotion\Persistence\Checker\DiscountPromotionFieldChecker::FIELD_ABSTRACT_SKUS
     *
     * @var string
     */
    protected const FIELD_ABSTRACT_SKUS = 'abstract_skus';

    /**
     * @var \Spryker\Zed\DiscountPromotion\Persistence\Checker\DiscountPromotionFieldCheckerInterface
     */
    protected $discountPromotionFieldChecker;

    /**
     * @param \Spryker\Zed\DiscountPromotion\Persistence\Checker\DiscountPromotionFieldCheckerInterface $discountPromotionFieldChecker
     */
    public function __construct(DiscountPromotionFieldCheckerInterface $discountPromotionFieldChecker)
    {
        $this->discountPromotionFieldChecker = $discountPromotionFieldChecker;
    }

    /**
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionTransfer
     */
    public function mapDiscountPromotionEntityToTransfer(
        SpyDiscountPromotion $discountPromotionEntity,
        DiscountPromotionTransfer $discountPromotionTransfer
    ): DiscountPromotionTransfer {
        $discountPromotionTransfer->fromArray($discountPromotionEntity->toArray(), true);

        if (!$this->discountPromotionFieldChecker->isAbstractSkusFieldExists()) {
            return $discountPromotionTransfer;
        }

        if (!$discountPromotionEntity->getAbstractSkus() && $discountPromotionEntity->getAbstractSku()) {
            return $discountPromotionTransfer->setAbstractSkus([$discountPromotionEntity->getAbstractSku()]);
        }

        return $discountPromotionTransfer->setAbstractSkus(
            $this->transformToArray($discountPromotionEntity->getAbstractSkus() ?? ''),
        );
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion> $discountPromotionEntities
     * @param \Generated\Shared\Transfer\DiscountPromotionCollectionTransfer $discountPromotionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountPromotionCollectionTransfer
     */
    public function mapDiscountPromotionEntitiesToDiscountPromotionCollectionTransfer(
        Collection $discountPromotionEntities,
        DiscountPromotionCollectionTransfer $discountPromotionCollectionTransfer
    ): DiscountPromotionCollectionTransfer {
        foreach ($discountPromotionEntities as $discountPromotionEntity) {
            $discountPromotionTransfer = $this->mapDiscountPromotionEntityToTransfer(
                $discountPromotionEntity,
                new DiscountPromotionTransfer(),
            );

            $discountPromotionCollectionTransfer->addDiscountPromotion($discountPromotionTransfer);
        }

        return $discountPromotionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountPromotionTransfer $discountPromotionTransfer
     * @param \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion $discountPromotionEntity
     *
     * @return \Orm\Zed\DiscountPromotion\Persistence\SpyDiscountPromotion
     */
    public function mapDiscountPromotionTransferToEntity(
        DiscountPromotionTransfer $discountPromotionTransfer,
        SpyDiscountPromotion $discountPromotionEntity
    ): SpyDiscountPromotion {
        $discountPromotionData = $discountPromotionTransfer->toArray();
        $isAbstractSkusFieldExists = $this->discountPromotionFieldChecker->isAbstractSkusFieldExists();
        if ($isAbstractSkusFieldExists) {
            unset($discountPromotionData[static::FIELD_ABSTRACT_SKUS]);
        }
        $discountPromotionEntity->fromArray($discountPromotionData);

        if (!$isAbstractSkusFieldExists) {
            return $discountPromotionEntity;
        }

        if (!$discountPromotionTransfer->getAbstractSkus() && $discountPromotionEntity->getAbstractSku()) {
            $discountPromotionTransfer->addAbstractSku($discountPromotionEntity->getAbstractSku());
        }

        $discountPromotionEntity->setAbstractSku('');

        return $discountPromotionEntity->setAbstractSkus(implode(', ', $discountPromotionTransfer->getAbstractSkus()));
    }

    /**
     * @param string $value
     *
     * @return array<string>
     */
    protected function transformToArray(string $value): array
    {
        $result = [];
        foreach (explode(',', $value) as $item) {
            $result[] = trim($item);
        }

        return $result;
    }
}
