<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferAvailabilityStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage;
use Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorageQuery;
use Spryker\DecimalObject\Decimal;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferAvailabilityStorageCommunicationTester extends Actor
{
    use _generated\ProductOfferAvailabilityStorageCommunicationTesterActions;

    /**
     * @param string $storeName
     * @param string $productOfferReference
     *
     * @return \Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorage|null
     */
    protected function findProductOfferAvailabilityStorage(string $storeName, string $productOfferReference): ?SpyProductOfferAvailabilityStorage
    {
        return $this->getProductOfferAvailabilityStoragePropelQuery()
            ->filterByStore($storeName)
            ->findOneByProductOfferReference($productOfferReference);
    }

    /**
     * @param string $storeName
     * @param string $productOfferReference
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getProductOfferAvailability(string $storeName, string $productOfferReference): Decimal
    {
        $productOfferAvailabilityStorageEntity = $this->findProductOfferAvailabilityStorage($storeName, $productOfferReference);

        if (!$productOfferAvailabilityStorageEntity) {
            return new Decimal(0);
        }

        return new Decimal($productOfferAvailabilityStorageEntity->getData()[ProductOfferAvailabilityStorageTransfer::AVAILABILITY]);
    }

    /**
     * @return \Orm\Zed\ProductOfferAvailabilityStorage\Persistence\SpyProductOfferAvailabilityStorageQuery
     */
    protected function getProductOfferAvailabilityStoragePropelQuery(): SpyProductOfferAvailabilityStorageQuery
    {
        return SpyProductOfferAvailabilityStorageQuery::create();
    }
}
