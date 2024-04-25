<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommission;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmount;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroup;
use Propel\Runtime\Collection\ObjectCollection;

class MerchantCommissionMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommission> $merchantCommissionEntities
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function mapMerchantCommissionEntitiesToMerchantCommissionCollectionTransfer(
        ObjectCollection $merchantCommissionEntities,
        MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
    ): MerchantCommissionCollectionTransfer {
        foreach ($merchantCommissionEntities as $merchantCommissionEntity) {
            $merchantCommissionCollectionTransfer->addMerchantCommission(
                $this->mapMerchantCommissionEntityToMerchantCommissionTransferWithRelations(
                    $merchantCommissionEntity,
                    new MerchantCommissionTransfer(),
                ),
            );
        }

        return $merchantCommissionCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmount> $merchantCommissionAmountEntities
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer $merchantCommissionAmountCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer
     */
    public function mapMerchantCommissionAmountEntitiesToMerchantCommissionAmountCollectionTransfer(
        ObjectCollection $merchantCommissionAmountEntities,
        MerchantCommissionAmountCollectionTransfer $merchantCommissionAmountCollectionTransfer
    ): MerchantCommissionAmountCollectionTransfer {
        foreach ($merchantCommissionAmountEntities as $merchantCommissionAmountEntity) {
            $merchantCommissionAmountCollectionTransfer->addMerchantCommissionAmount(
                $this->mapMerchantCommissionAmountEntityToMerchantCommissionAmountTransferWithRelations(
                    $merchantCommissionAmountEntity,
                    new MerchantCommissionAmountTransfer(),
                ),
            );
        }

        return $merchantCommissionAmountCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroup> $merchantCommissionGroupEntities
     * @param \Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer $merchantCommissionGroupCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer
     */
    public function mapMerchantCommissionGroupEntitiesToMerchantCommissionGroupCollectionTransfer(
        ObjectCollection $merchantCommissionGroupEntities,
        MerchantCommissionGroupCollectionTransfer $merchantCommissionGroupCollectionTransfer
    ): MerchantCommissionGroupCollectionTransfer {
        foreach ($merchantCommissionGroupEntities as $merchantCommissionGroupEntity) {
            $merchantCommissionGroupTransfer = $this->mapMerchantCommissionGroupEntityToMerchantCommissionGroupTransfer(
                $merchantCommissionGroupEntity,
                new MerchantCommissionGroupTransfer(),
            );

            $merchantCommissionGroupCollectionTransfer->addMerchantCommissionGroup($merchantCommissionGroupTransfer);
        }

        return $merchantCommissionGroupCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommission $merchantCommissionEntity
     *
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommission
     */
    public function mapMerchantCommissionTransferToMerchantCommissionEntity(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        SpyMerchantCommission $merchantCommissionEntity
    ): SpyMerchantCommission {
        $merchantCommissionEntity->fromArray($merchantCommissionTransfer->modifiedToArray());
        $merchantCommissionEntity->setFkMerchantCommissionGroup(
            $merchantCommissionTransfer->getMerchantCommissionGroupOrFail()->getIdMerchantCommissionGroupOrFail(),
        );

        return $merchantCommissionEntity;
    }

    /**
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommission $merchantCommissionEntity
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function mapMerchantCommissionEntityToMerchantCommissionTransfer(
        SpyMerchantCommission $merchantCommissionEntity,
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        $merchantCommissionTransfer->fromArray($merchantCommissionEntity->toArray(), true);
        if (!$merchantCommissionTransfer->getMerchantCommissionGroup()) {
            $merchantCommissionTransfer->setMerchantCommissionGroup(
                (new MerchantCommissionGroupTransfer())->setIdMerchantCommissionGroup($merchantCommissionEntity->getFkMerchantCommissionGroup()),
            );
        }

        return $merchantCommissionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmount $merchantCommissionAmountEntity
     *
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmount
     */
    public function mapMerchantCommissionAmountTransferToMerchantCommissionAmountEntity(
        MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer,
        SpyMerchantCommissionAmount $merchantCommissionAmountEntity
    ): SpyMerchantCommissionAmount {
        $merchantCommissionAmountEntity->fromArray($merchantCommissionAmountTransfer->modifiedToArray());
        $merchantCommissionAmountEntity->setFkCurrency($merchantCommissionAmountTransfer->getCurrencyOrFail()->getIdCurrencyOrFail());

        return $merchantCommissionAmountEntity;
    }

    /**
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmount $merchantCommissionAmountEntity
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountTransfer
     */
    public function mapMerchantCommissionAmountEntityToMerchantCommissionAmountTransfer(
        SpyMerchantCommissionAmount $merchantCommissionAmountEntity,
        MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
    ): MerchantCommissionAmountTransfer {
        $merchantCommissionAmountTransfer->fromArray($merchantCommissionAmountEntity->toArray(), true);
        if (!$merchantCommissionAmountTransfer->getCurrency()) {
            $merchantCommissionAmountTransfer->setCurrency(
                (new CurrencyTransfer())->setIdCurrency($merchantCommissionAmountEntity->getFkCurrency()),
            );
        }

        return $merchantCommissionAmountTransfer;
    }

    /**
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmount $merchantCommissionAmountEntity
     * @param \Generated\Shared\Transfer\MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountTransfer
     */
    protected function mapMerchantCommissionAmountEntityToMerchantCommissionAmountTransferWithRelations(
        SpyMerchantCommissionAmount $merchantCommissionAmountEntity,
        MerchantCommissionAmountTransfer $merchantCommissionAmountTransfer
    ): MerchantCommissionAmountTransfer {
        $merchantCommissionAmountTransfer->fromArray($merchantCommissionAmountEntity->toArray(), true);
        $merchantCommissionAmountTransfer->setCurrency(
            $this->mapCurrencyEntityToCurrencyTransfer(
                $merchantCommissionAmountEntity->getCurrency(),
                new CurrencyTransfer(),
            ),
        );

        return $merchantCommissionAmountTransfer;
    }

    /**
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommission $merchantCommissionEntity
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    protected function mapMerchantCommissionEntityToMerchantCommissionTransferWithRelations(
        SpyMerchantCommission $merchantCommissionEntity,
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        $merchantCommissionTransfer->fromArray($merchantCommissionEntity->toArray(), true);
        $merchantCommissionTransfer->setMerchantCommissionGroup(
            $this->mapMerchantCommissionGroupEntityToMerchantCommissionGroupTransfer(
                $merchantCommissionEntity->getMerchantCommissionGroup(),
                new MerchantCommissionGroupTransfer(),
            ),
        );
        $merchantCommissionTransfer->setStoreRelation(
            $this->mapMerchantCommissionStoreEntitiesToStoreRelationTransfer(
                $merchantCommissionEntity->getMerchantCommissionStores(),
                new StoreRelationTransfer(),
            ),
        );

        return $this->mapMerchantCommissionMerchantEntitiesToMerchantCommissionTransfer(
            $merchantCommissionEntity->getMerchantCommissionMerchants(),
            $merchantCommissionTransfer,
        );
    }

    /**
     * @param \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionGroup $merchantCommissionGroupEntity
     * @param \Generated\Shared\Transfer\MerchantCommissionGroupTransfer $merchantCommissionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionGroupTransfer
     */
    protected function mapMerchantCommissionGroupEntityToMerchantCommissionGroupTransfer(
        SpyMerchantCommissionGroup $merchantCommissionGroupEntity,
        MerchantCommissionGroupTransfer $merchantCommissionGroupTransfer
    ): MerchantCommissionGroupTransfer {
        return $merchantCommissionGroupTransfer->fromArray(
            $merchantCommissionGroupEntity->toArray(),
            true,
        );
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionStore> $merchantCommissionStoreEntities
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function mapMerchantCommissionStoreEntitiesToStoreRelationTransfer(
        ObjectCollection $merchantCommissionStoreEntities,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($merchantCommissionStoreEntities as $merchantCommissionStoreEntity) {
            $storeRelationTransfer->addStores((new StoreTransfer())->setIdStore($merchantCommissionStoreEntity->getFkStore()));
        }

        return $storeRelationTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<array-key, \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionMerchant> $merchantCommissionMerchantEntities
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function mapMerchantCommissionMerchantEntitiesToMerchantCommissionTransfer(
        ObjectCollection $merchantCommissionMerchantEntities,
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        foreach ($merchantCommissionMerchantEntities as $merchantCommissionMerchantEntity) {
            $merchantCommissionTransfer->addMerchant(
                (new MerchantTransfer())->setIdMerchant($merchantCommissionMerchantEntity->getFkMerchant()),
            );
        }

        return $merchantCommissionTransfer;
    }

    /**
     * @param \Orm\Zed\Currency\Persistence\SpyCurrency $currencyEntity
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function mapCurrencyEntityToCurrencyTransfer(SpyCurrency $currencyEntity, CurrencyTransfer $currencyTransfer): CurrencyTransfer
    {
        return $currencyTransfer->fromArray($currencyEntity->toArray(), true);
    }
}
