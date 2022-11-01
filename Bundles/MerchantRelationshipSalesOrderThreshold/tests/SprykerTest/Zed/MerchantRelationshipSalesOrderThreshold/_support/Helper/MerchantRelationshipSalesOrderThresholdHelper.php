<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipSalesOrderThreshold\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantRelationshipSalesOrderThresholdBuilder;
use Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\Map\SpyMerchantRelationshipSalesOrderThresholdTableMap;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipSalesOrderThresholdFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantRelationshipSalesOrderThresholdHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FOUND = 'Found at least one entry in the database table but database table `%s` was expected to be empty.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_EXPECTED = 'Expected at least one entry in the database table `%s` but table is empty.';

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipSalesOrderThresholdTransfer
     */
    public function haveMerchantRelationshipSalesOrderThreshold(array $seedData = []): MerchantRelationshipSalesOrderThresholdTransfer
    {
        $merchantRelationshipSalesOrderThresholdTransfer = (new MerchantRelationshipSalesOrderThresholdBuilder($seedData))->build();

        return $this->getMerchantRelationshipSalesOrderThresholdFacade()->saveMerchantRelationshipSalesOrderThreshold(
            $merchantRelationshipSalesOrderThresholdTransfer,
        );
    }

    /**
     * @return void
     */
    public function cleanupMerchantRelationshipSalesOrderThresholds(): void
    {
        $this->debug(sprintf(
            'Deleting All rows of table `%s`.',
            SpyMerchantRelationshipSalesOrderThresholdTableMap::TABLE_NAME,
        ));

        $this->getMerchantRelationshipSalesOrderThresholdQuery()
            ->deleteAll();
    }

    /**
     * @return void
     */
    public function assertMerchantRelationshipSalesOrderThresholdTableIsEmtpy(): void
    {
        $this->assertFalse($this->getMerchantRelationshipSalesOrderThresholdQuery()->exists(), sprintf(static::ERROR_MESSAGE_FOUND, SpyMerchantRelationshipSalesOrderThresholdTableMap::TABLE_NAME));
    }

    /**
     * @return void
     */
    public function assertMerchantRelationshipSalesOrderThresholdTableHasRecords(): void
    {
        $this->assertTrue($this->getMerchantRelationshipSalesOrderThresholdQuery()->exists(), sprintf(static::ERROR_MESSAGE_EXPECTED, SpyMerchantRelationshipSalesOrderThresholdTableMap::TABLE_NAME));
    }

    /**
     * @return \Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery
     */
    protected function getMerchantRelationshipSalesOrderThresholdQuery(): SpyMerchantRelationshipSalesOrderThresholdQuery
    {
        return SpyMerchantRelationshipSalesOrderThresholdQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Business\MerchantRelationshipSalesOrderThresholdFacadeInterface
     */
    protected function getMerchantRelationshipSalesOrderThresholdFacade(): MerchantRelationshipSalesOrderThresholdFacadeInterface
    {
        return $this->getLocatorHelper()->getLocator()->merchantRelationshipSalesOrderThreshold()->facade();
    }
}
