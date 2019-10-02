<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StockDataImport;

use Codeception\Actor;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;

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
class StockDataImportCommunicationTester extends Actor
{
    use _generated\StockDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureStockTableIsEmpty(): void
    {
        $this->createStockProductQuery()->deleteAll();
        $this->createStockQuery()->deleteAll();
    }

    /**
     * @return void
     */
    public function assertStockTableContainsData(): void
    {
        $stockCount = $this->createStockQuery()->count();

        $this->assertTrue(
            $stockCount > 0,
            'Expected at least one entry in the database table but database table is empty.'
        );
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    protected function createStockQuery(): SpyStockQuery
    {
        return SpyStockQuery::create();
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    protected function createStockProductQuery(): SpyStockProductQuery
    {
        return SpyStockProductQuery::create();
    }
}
