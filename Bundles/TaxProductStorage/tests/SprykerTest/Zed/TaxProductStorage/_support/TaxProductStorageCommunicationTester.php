<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxProductStorage;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorageQuery;

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
 * @method \Spryker\Zed\TaxProductStorage\Business\TaxProductStorageFacade getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class TaxProductStorageCommunicationTester extends Actor
{
    use _generated\TaxProductStorageCommunicationTesterActions;

    /**
     * @var int
     */
    public const TEST_INVALID_ID = 999999999;

    /**
     * @return void
     */
    public function assertStorageDatabaseTableIsEmpty(): void
    {
        SpyTaxProductStorageQuery::create()->deleteAll();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function haveProductAbstractTaxStorage(): ProductAbstractTransfer
    {
        $productAbstract = $this->haveProductAbstract();
        $this->getFacade()->publish([$productAbstract->getIdProductAbstract()]);

        return $productAbstract;
    }
}
