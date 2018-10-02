<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesOrderThreshold\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer;
use Orm\Zed\SalesOrderThreshold\Persistence\Map\SpySalesOrderThresholdTableMap;
use Orm\Zed\SalesOrderThreshold\Persistence\Map\SpySalesOrderThresholdTypeTableMap;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdQuery;
use Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdTypeQuery;
use Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class SalesOrderThresholdHelper extends Module
{
    use LocatorHelperTrait;
    use DataCleanupHelperTrait;

    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';

    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least %d entries in the database table `%s` and found %d entries.';

    /**
     * @return void
     */
    public function truncateSalesOrderThresholds(): void
    {
        $this->getSalesOrderThresholdQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertSalesOrderThresholdTableIsEmtpy(): void
    {
        $this->assertFalse($this->getSalesOrderThresholdQuery()->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpySalesOrderThresholdTableMap::TABLE_NAME));
    }

    /**
     * @param int $recordsNum
     *
     * @return void
     */
    public function assertSalesOrderThresholdTypeTableHasRecords(int $recordsNum): void
    {
        $entriesFound = $this->getSalesOrderThresholdTypeQuery()->count();
        $this->assertEquals($entriesFound, $recordsNum, sprintf(static::ERROR_MESSAGE_EXPECTED, $recordsNum, SpySalesOrderThresholdTypeTableMap::TABLE_NAME, $entriesFound));
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderThresholdTypeTransfer
     */
    public function haveSalesOrderThresholdType(SalesOrderThresholdTypeTransfer $salesOrderThresholdTypeTransfer): SalesOrderThresholdTypeTransfer
    {
        $salesOrderThresholdTypeTransfer = $this->getFacade()->saveSalesOrderThresholdType($salesOrderThresholdTypeTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($salesOrderThresholdTypeTransfer) {
            $this->getSalesOrderThresholdTypeQuery()
                ->filterByKey($salesOrderThresholdTypeTransfer->getKey())
                ->filterByThresholdGroup($salesOrderThresholdTypeTransfer->getThresholdGroup())
                ->delete();
        });

        return $salesOrderThresholdTypeTransfer;
    }

    /**
     * @return \Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdQuery
     */
    protected function getSalesOrderThresholdQuery(): SpySalesOrderThresholdQuery
    {
        return SpySalesOrderThresholdQuery::create();
    }

    /**
     * @return \Orm\Zed\SalesOrderThreshold\Persistence\SpySalesOrderThresholdTypeQuery
     */
    protected function getSalesOrderThresholdTypeQuery(): SpySalesOrderThresholdTypeQuery
    {
        return SpySalesOrderThresholdTypeQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesOrderThreshold\Business\SalesOrderThresholdFacadeInterface
     */
    protected function getFacade(): SalesOrderThresholdFacadeInterface
    {
        return $this->getLocator()->salesOrderThreshold()->facade();
    }
}
