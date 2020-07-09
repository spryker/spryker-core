<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStorage;

use Codeception\Actor;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage;
use Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorageQuery;
use Propel\Runtime\Collection\ObjectCollection;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantStorageCommunicationTester extends Actor
{
    use _generated\MerchantStorageCommunicationTesterActions;

    /**
     * @param int $idMerchant
     *
     * @return \Orm\Zed\MerchantStorage\Persistence\Base\SpyMerchantStorage|null
     */
    public function findMerchantStorageEntityByIdMerchant(int $idMerchant): ?SpyMerchantStorage
    {
        return $this->getMerchantStorageQuery()->findOneByIdMerchant($idMerchant);
    }

    /**
     * @param int[] $merchantIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\MerchantStorage\Persistence\Base\SpyMerchantStorage[]
     */
    public function findMerchantStorageEntitiesByIdMerchants(array $merchantIds): ObjectCollection
    {
        return $this->getMerchantStorageQuery()
            ->filterByIdMerchant_In($merchantIds)
            ->find();
    }

    /**
     * @return \Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorageQuery
     */
    protected function getMerchantStorageQuery(): SpyMerchantStorageQuery
    {
        return SpyMerchantStorageQuery::create();
    }

    /**
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    public function createStoreRelationTransfer(): StoreRelationTransfer
    {
        $storeTransfer = $this->haveStore([StoreTransfer::NAME => 'DE']);

        return (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
        ])->build();
    }
}
