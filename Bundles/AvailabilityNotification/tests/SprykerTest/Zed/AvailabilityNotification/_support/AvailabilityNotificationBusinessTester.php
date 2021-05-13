<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification;

use Codeception\Actor;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery;

/**
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
class AvailabilityNotificationBusinessTester extends Actor
{
    use _generated\AvailabilityNotificationBusinessTesterActions;

    /**
     * @param string $customerReference
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findAvailabilityNotificationByCustomerReferenceAndSku(
        string $customerReference,
        string $sku,
        int $fkStore
    ): ?AvailabilityNotificationSubscriptionTransfer {
        $availabilityNotificationSubscriptionEntity = SpyAvailabilityNotificationSubscriptionQuery::create()->filterByCustomerReference($customerReference)
            ->filterBySku($sku)
            ->filterByFkStore($fkStore)
            ->findOne();

        if ($availabilityNotificationSubscriptionEntity === null) {
            return null;
        }

        $availabilityNotificationSubscriptionTransfer = (new AvailabilityNotificationSubscriptionTransfer())->fromArray($availabilityNotificationSubscriptionEntity->toArray(), true);

        $storeTransfer = new StoreTransfer();
        $storeTransfer->fromArray($availabilityNotificationSubscriptionEntity->getSpyStore()->toArray(), true);

        $availabilityNotificationSubscriptionTransfer->setStore($storeTransfer);

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($availabilityNotificationSubscriptionEntity->getSpyLocale()->toArray(), true);

        $availabilityNotificationSubscriptionTransfer->setLocale($localeTransfer);

        return $availabilityNotificationSubscriptionTransfer;
    }
}
