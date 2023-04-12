<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\ServicePoint;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface getFacade(?string $moduleName = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ServicePointBusinessTester extends Actor
{
    use _generated\ServicePointBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureServicePointTablesAreEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(
            $this->getServicePointQuery(),
        );
    }

    /**
     * @param string $storeName
     * @param array $servicePointSeedData
     * @param bool $isStoreCreationNeeded
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePointTransferWithStoreRelation(
        string $storeName,
        array $servicePointSeedData = [],
        bool $isStoreCreationNeeded = true
    ): ServicePointTransfer {
        $storeTransfer = (new StoreTransfer())->setName($storeName);

        if ($isStoreCreationNeeded) {
            $storeTransfer = $this->haveStore($storeTransfer->toArray());
        }

        $servicePointTransfer = (new ServicePointBuilder($servicePointSeedData))
            ->withStoreRelation([
                StoreRelationTransfer::STORES => [
                    [
                        StoreTransfer::ID_STORE => $storeTransfer->getIdStore(),
                        StoreTransfer::NAME => $storeTransfer->getNameOrFail(),
                    ],
                ],
            ])->build();

        return $servicePointTransfer;
    }

    /**
     * @return \Orm\Zed\ServicePoint\Persistence\SpyServicePointQuery
     */
    public function getServicePointQuery(): SpyServicePointQuery
    {
        return SpyServicePointQuery::create();
    }
}
