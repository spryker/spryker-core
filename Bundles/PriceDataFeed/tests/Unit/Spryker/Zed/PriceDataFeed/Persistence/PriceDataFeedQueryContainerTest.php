<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\PriceDataFeed\Persistence;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\PriceDataFeedTransfer;
use Orm\Zed\Price\Persistence\Base\SpyPriceProductQuery;
use Spryker\Zed\Price\Persistence\PriceQueryContainer;
use Spryker\Zed\PriceDataFeed\Persistence\PriceDataFeedQueryContainer;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group PriceDataFeed
 * @group Persistence
 * @group PriceDataFeedQueryContainer
 */
class PriceDataFeedQueryContainerTest extends Test
{

    /**
     * @var PriceDataFeedQueryContainer
     */
    protected $priceDataFeedQueryContainer;

    /**
     * @var PriceDataFeedTransfer
     */
    protected $priceDataFeedTransfer;

    public function setUp()
    {
        parent::setUp();

        $this->priceDataFeedQueryContainer = $this->createPriceDataFeedQueryContainer();
        $this->priceDataFeedTransfer = $this->createPriceDataFeedTransfer();
    }

    public function testGetPriceDataFeedQuery()
    {
        $query = $this->priceDataFeedQueryContainer
            ->getPriceDataFeedQuery($this->priceDataFeedTransfer);

        $expectedJoinedTables = $this->getDefaultJoinedTables();
        $joinedTables = $this->getJoinedTablesNames($query);

        $this->assertTrue($query instanceof SpyPriceProductQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    public function testGetPriceDataFeedQueryWithJoinedTypes()
    {
        $this->priceDataFeedTransfer->setIsJoinType(true);
        $query = $this->priceDataFeedQueryContainer
            ->getPriceDataFeedQuery($this->priceDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getTypeJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertTrue($query instanceof SpyPriceProductQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return PriceDataFeedQueryContainer
     */
    protected function createPriceDataFeedQueryContainer()
    {
        $priceQueryContainer = new PriceQueryContainer();
        $priceDataFeedQueryContainer = new PriceDataFeedQueryContainer($priceQueryContainer);

        return $priceDataFeedQueryContainer;
    }

    /**
     * @return PriceDataFeedTransfer
     */
    protected function createPriceDataFeedTransfer()
    {
        $priceDataFeedTransfer = new PriceDataFeedTransfer();

        return $priceDataFeedTransfer;
    }

    /**
     * @param SpyPriceProductQuery $query
     *
     * @return array
     */
    protected function getJoinedTablesNames(SpyPriceProductQuery $query)
    {
        $tablesNames = [];
        $joins = $query->getJoins();

        foreach ($joins as $join) {
            $tablesNames[] = $join->getRightTableName();
        }
        asort($tablesNames);
        $tablesNames = array_values($tablesNames);

        return $tablesNames;
    }

    /**
     * @param $tablesArray
     *
     * @return array
     */
    protected function getSortedExpectedJoinedTables($tablesArray)
    {
        asort($tablesArray);
        $tablesArray = array_values($tablesArray);

        return $tablesArray;
    }

    /**
     * @return array
     */
    protected function getDefaultJoinedTables()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getTypeJoinedTables()
    {
        return [
            'spy_price_type',
        ];
    }

}