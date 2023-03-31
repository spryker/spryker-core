<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StoreStorage;

use Codeception\Actor;
use Orm\Zed\StoreStorage\Persistence\SpyStoreStorage;
use Orm\Zed\StoreStorage\Persistence\SpyStoreStorageQuery;

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
class StoreStorageCommunicationTester extends Actor
{
    use _generated\StoreStorageCommunicationTesterActions;

    /**
     * @param int $idStore
     *
     * @return \Orm\Zed\StoreStorage\Persistence\SpyStoreStorage|null
     */
    public function findStoreStorageEntityByIdStore(int $idStore): ?SpyStoreStorage
    {
        return $this->createStoreStoragePropelQuery()->findOneByFkStore($idStore);
    }

    /**
     * @return \Orm\Zed\StoreStorage\Persistence\SpyStoreStorageQuery
     */
    protected function createStoreStoragePropelQuery(): SpyStoreStorageQuery
    {
        return SpyStoreStorageQuery::create();
    }
}
