<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceDataFeed\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceDataFeedTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Spryker\Zed\PriceDataFeed\Persistence\PriceDataFeedQueryContainer;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceDataFeed
 * @group Persistence
 * @group PriceDataFeedQueryContainerTest
 * Add your own group annotations below this line
 */
class PriceDataFeedQueryContainerTest extends Unit
{
    /**
     * @var \Spryker\Zed\PriceDataFeed\Persistence\PriceDataFeedQueryContainer
     */
    protected $priceDataFeedQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\PriceDataFeedTransfer
     */
    protected $priceDataFeedTransfer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->priceDataFeedQueryContainer = $this->createPriceDataFeedQueryContainer();
        $this->priceDataFeedTransfer = $this->createPriceDataFeedTransfer();
    }

    /**
     * @return void
     */
    public function testGetPriceDataFeedQuery()
    {
        $query = $this->priceDataFeedQueryContainer
            ->queryPriceDataFeed($this->priceDataFeedTransfer);

        $expectedJoinedTables = $this->getDefaultJoinedTables();
        $joinedTables = $this->getJoinedTablesNames($query);

        $this->assertInstanceOf(SpyPriceProductQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testGetPriceDataFeedQueryWithJoinedTypes()
    {
        $this->priceDataFeedTransfer->setJoinPriceType(true);
        $query = $this->priceDataFeedQueryContainer
            ->queryPriceDataFeed($this->priceDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getTypeJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertInstanceOf(SpyPriceProductQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return \Spryker\Zed\PriceDataFeed\Persistence\PriceDataFeedQueryContainer
     */
    protected function createPriceDataFeedQueryContainer()
    {
        $priceQueryContainer = new PriceProductQueryContainer();
        $priceDataFeedQueryContainer = new PriceDataFeedQueryContainer($priceQueryContainer);

        return $priceDataFeedQueryContainer;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceDataFeedTransfer
     */
    protected function createPriceDataFeedTransfer()
    {
        $priceDataFeedTransfer = new PriceDataFeedTransfer();

        return $priceDataFeedTransfer;
    }

    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery $query
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
     * @param array $tablesArray
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
