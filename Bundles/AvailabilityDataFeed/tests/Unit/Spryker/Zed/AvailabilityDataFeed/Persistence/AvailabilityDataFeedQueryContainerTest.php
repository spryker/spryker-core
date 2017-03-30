<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\AvailabilityDataFeed\Persistence;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AvailabilityDataFeedTransfer;
use Orm\Zed\Stock\Persistence\Base\SpyStockProductQuery;
use Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedQueryContainer;
use Spryker\Zed\Stock\Persistence\StockQueryContainer;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group AvailabilityDataFeed
 * @group Persistence
 * @group AvailabilityDataFeedQueryContainerTest
 */
class AvailabilityDataFeedQueryContainerTest extends Test
{

    /**
     * @var \Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedQueryContainer
     */
    protected $availabilityDataFeedQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\AvailabilityDataFeedTransfer
     */
    protected $availabilityDataFeedTransfer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->availabilityDataFeedQueryContainer = $this->createAvailabilityDataFeedQueryContainer();
        $this->availabilityDataFeedTransfer = $this->createAvailabilityDataFeedTransfer();
    }

    /**
     * @return void
     */
    public function testGetAvailabilityDataFeedQuery()
    {
        $query = $this->availabilityDataFeedQueryContainer
            ->getAvailabilityDataFeedQuery($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = $this->getDefaultJoinedTables();
        $joinedTables = $this->getJoinedTablesNames($query);

        $this->assertTrue($query instanceof SpyStockProductQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testGetAvailabilityDataFeedQueryWithJoinedProducts()
    {
        $this->availabilityDataFeedTransfer->setIsJoinProduct(true);
        $query = $this->availabilityDataFeedQueryContainer
            ->getAvailabilityDataFeedQuery($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getProductJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertTrue($query instanceof SpyStockProductQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testGetAvailabilityDataFeedQueryWithJoinedProductsAndLocaleFilter()
    {
        $this->availabilityDataFeedTransfer->setIsJoinProduct(true);
        $this->availabilityDataFeedTransfer->setLocaleId(46);
        $query = $this->availabilityDataFeedQueryContainer
            ->getAvailabilityDataFeedQuery($this->availabilityDataFeedTransfer);

        $this->assertTrue($query instanceof SpyStockProductQuery);
        $this->assertEquals($this->getParamsForLocaleFilter(), $query->getParams());
    }

    /**
     * @return void
     */
    public function testGetAvailabilityDataFeedQueryWithFilterByDate()
    {
        $this->availabilityDataFeedTransfer->setUpdatedFrom('2017-01-01');
        $this->availabilityDataFeedTransfer->setUpdatedTo('2017-12-01');
        $query = $this->availabilityDataFeedQueryContainer
            ->getAvailabilityDataFeedQuery($this->availabilityDataFeedTransfer);

        $this->assertTrue($query instanceof SpyStockProductQuery);
        $this->assertEquals($this->getParamsForDateFilter(), $query->getParams());
    }

    /**
     * @return \Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedQueryContainer
     */
    protected function createAvailabilityDataFeedQueryContainer()
    {
        $stockQueryContainer = new StockQueryContainer();
        $availabilityDataFeedQueryContainer = new AvailabilityDataFeedQueryContainer($stockQueryContainer);

        return $availabilityDataFeedQueryContainer;
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilityDataFeedTransfer
     */
    protected function createAvailabilityDataFeedTransfer()
    {
        $availabilityDataFeedTransfer = new AvailabilityDataFeedTransfer();

        return $availabilityDataFeedTransfer;
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\Base\SpyStockProductQuery $query
     *
     * @return array
     */
    protected function getJoinedTablesNames(SpyStockProductQuery $query)
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
        return [
            'spy_stock',
            'spy_stock_product',
            'spy_touch',
        ];
    }

    /**
     * @return array
     */
    protected function getProductJoinedTables()
    {
        return [
            'spy_product',
            'spy_product_localized_attributes',
        ];
    }

    /**
     * @return array
     */
    protected function getParamsForLocaleFilter()
    {
        return [
            [
                'table' => 'spy_product_localized_attributes',
                'column' => 'fk_locale',
                'value' => 46,
            ],
            [
                'table' => null,
                'type' => 2,
                'value' => 'stock-product',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getParamsForDateFilter()
    {
        return [
            [
                'table' => null,
                'type' => 2,
                'value' => 'stock-product',
            ],
            [
                'table' => null,
                'type' => 2,
                'value' => '2017-01-01',
            ],
            [
                'table' => null,
                'type' => 2,
                'value' => '2017-12-01',
            ],
        ];
    }

}
