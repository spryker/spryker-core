<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOfferGui;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;

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
class ProductOfferGuiCommunicationTester extends Actor
{
    use _generated\ProductOfferGuiCommunicationTesterActions;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer
     */
    public function createProductOfferTableCriteriaTransfer(array $seedData = []): ProductOfferTableCriteriaTransfer
    {
        return (new ProductOfferTableCriteriaTransfer())
            ->setStores($seedData[ProductOfferTableCriteriaTransfer::STORES] ?? null)
            ->setStatus($seedData[ProductOfferTableCriteriaTransfer::STATUS] ?? null)
            ->setApprovalStatuses($seedData[ProductOfferTableCriteriaTransfer::APPROVAL_STATUSES] ?? null);
    }
}
