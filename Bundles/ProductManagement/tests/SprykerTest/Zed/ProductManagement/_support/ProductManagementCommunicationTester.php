<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductManagement;

use Codeception\Actor;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Service\UtilNumber\UtilNumberServiceInterface;

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
class ProductManagementCommunicationTester extends Actor
{
    use _generated\ProductManagementCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureProductAbstractTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getProductAbstractQuery());
    }

    /**
     * @return \Spryker\Service\UtilNumber\UtilNumberServiceInterface
     */
    public function getUtilService(): UtilNumberServiceInterface
    {
        return $this->getLocator()->utilNumber()->service();
    }

    /**
     * @return string
     */
    public function getCurrentLocaleName(): string
    {
        return $this->getLocator()
            ->locale()
            ->facade()
            ->getCurrentLocale()
            ->getLocaleName();
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function getProductAbstractQuery(): SpyProductAbstractQuery
    {
        return SpyProductAbstractQuery::create();
    }
}
