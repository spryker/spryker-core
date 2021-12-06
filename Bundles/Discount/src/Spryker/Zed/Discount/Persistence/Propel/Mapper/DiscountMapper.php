<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\DiscountMoneyAmountTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountAmount;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\Collection\ObjectCollection;

class DiscountMapper
{
    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-d-m H:i:s';

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    public function mapDiscountTransferToDiscountEntity(DiscountTransfer $discountTransfer, SpyDiscount $discountEntity): SpyDiscount
    {
        $discountEntity->fromArray($discountTransfer->toArray());

        return $discountEntity;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function mapDiscountEntityToDiscountTransfer(SpyDiscount $discountEntity, DiscountTransfer $discountTransfer): DiscountTransfer
    {
        return $discountTransfer->fromArray($discountEntity->toArray(), true)
            ->setValidFrom($discountEntity->getValidFrom(static::DATE_TIME_FORMAT))
            ->setValidTo($discountEntity->getValidTo(static::DATE_TIME_FORMAT));
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountGeneralTransfer $discountGeneralTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool $discountVoucherPoolEntity
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    public function mapDiscountGeneralTransferToDiscountVoucherPoolEntity(
        DiscountGeneralTransfer $discountGeneralTransfer,
        SpyDiscountVoucherPool $discountVoucherPoolEntity
    ): SpyDiscountVoucherPool {
        return $discountVoucherPoolEntity
            ->setName($discountGeneralTransfer->getDisplayNameOrFail())
            ->setIsActive(true);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountMoneyAmountTransfer $discountMoneyAmountTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountAmount $discountAmountEntity
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountAmount
     */
    public function mapDiscountMoneyAmountTransferToDiscountAmountEntity(
        DiscountMoneyAmountTransfer $discountMoneyAmountTransfer,
        SpyDiscountAmount $discountAmountEntity
    ): SpyDiscountAmount {
        $discountAmountEntity->fromArray($discountMoneyAmountTransfer->modifiedToArray());

        return $discountAmountEntity;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountAmount $discountAmountEntity
     * @param \Generated\Shared\Transfer\DiscountMoneyAmountTransfer $discountMoneyAmountTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountMoneyAmountTransfer
     */
    public function mapDiscountAmountEntityToDiscountMoneyAmountTransfer(
        SpyDiscountAmount $discountAmountEntity,
        DiscountMoneyAmountTransfer $discountMoneyAmountTransfer
    ): DiscountMoneyAmountTransfer {
        return $discountMoneyAmountTransfer->fromArray($discountAmountEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Discount\Persistence\SpyDiscountAmount[] $discountAmountEntities
     * @param array<\Generated\Shared\Transfer\MoneyValueTransfer> $moneyValueTransfers
     *
     * @return array<\Generated\Shared\Transfer\MoneyValueTransfer>
     */
    public function mapDiscountAmountEntitiesToMoneyValueTransfers(
        ObjectCollection $discountAmountEntities,
        array $moneyValueTransfers
    ): array {
        foreach ($discountAmountEntities as $discountAmountEntity) {
            $moneyValueTransfers[] = $this->mapDiscountAmountEntityToMoneyValueTransfer($discountAmountEntity, new MoneyValueTransfer());
        }

        return $moneyValueTransfers;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Discount\Persistence\SpyDiscountStore[] $discountStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapDiscountStoreEntitiesToStoreRelationTransfer(
        ObjectCollection $discountStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($discountStoreEntities as $discountStoreEntity) {
            $storeTransfer = $this->mapStoreEntityToStoreTransfer($discountStoreEntity->getSpyStore(), new StoreTransfer());
            $storeRelationTransfer
                ->addIdStores($storeTransfer->getIdStoreOrFail())
                ->addStores($storeTransfer);
        }

        return $storeRelationTransfer;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountAmount $discountAmountEntity
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function mapDiscountAmountEntityToMoneyValueTransfer(
        SpyDiscountAmount $discountAmountEntity,
        MoneyValueTransfer $moneyValueTransfer
    ): MoneyValueTransfer {
        return $moneyValueTransfer
            ->fromArray($discountAmountEntity->toArray(), true)
            ->setIdEntity($discountAmountEntity->getIdDiscountAmount());
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToStoreTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }
}
