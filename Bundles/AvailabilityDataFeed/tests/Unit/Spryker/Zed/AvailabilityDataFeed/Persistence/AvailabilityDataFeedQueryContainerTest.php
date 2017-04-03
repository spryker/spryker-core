<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\AvailabilityDataFeed\Persistence;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AvailabilityDataFeedTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedQueryContainer;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;

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
        $this->availabilityDataFeedTransfer->setLocaleId(46);
        $query = $this->availabilityDataFeedQueryContainer
            ->queryAvailabilityDataFeed($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = $this->getDefaultJoinedTables();
        $joinedTables = $this->getJoinedTablesNames($query);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testGetAvailabilityDataFeedQueryWithJoinedProducts()
    {
        $this->availabilityDataFeedTransfer->setLocaleId(46);
        $query = $this->availabilityDataFeedQueryContainer
            ->queryAvailabilityDataFeed($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = $this->getDefaultJoinedTables();
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testGetAvailabilityDataFeedQueryWithJoinedProductsAndLocaleFilter()
    {
        $this->availabilityDataFeedTransfer->setLocaleId(46);
        $query = $this->availabilityDataFeedQueryContainer
            ->queryAvailabilityDataFeed($this->availabilityDataFeedTransfer);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($this->getParamsForLocaleFilter(), $query->getParams());
    }

    /**
     * @return void
     */
    public function testGetAvailabilityDataFeedQueryWithFilterByDate()
    {
        $this->availabilityDataFeedTransfer->setUpdatedFrom('2017-01-01');
        $this->availabilityDataFeedTransfer->setUpdatedTo('2017-12-01');
        $this->availabilityDataFeedTransfer->setLocaleId(46);

        $query = $this->availabilityDataFeedQueryContainer
            ->queryAvailabilityDataFeed($this->availabilityDataFeedTransfer);
        $expectedParams = array_merge(
            $this->getParamsForLocaleFilter(),
            $this->getParamsForDateFilter()
        );

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($expectedParams, $query->getParams());
    }

    /**
     * @return \Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedQueryContainer
     */
    protected function createAvailabilityDataFeedQueryContainer()
    {
        $availabilityQueryContainer = new AvailabilityQueryContainer();
        $availabilityDataFeedQueryContainer = new AvailabilityDataFeedQueryContainer($availabilityQueryContainer);

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
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $query
     *
     * @return array
     */
    protected function getJoinedTablesNames(SpyProductAbstractQuery $query)
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
            'spy_availability',
            'spy_availability_abstract',
            'spy_oms_product_reservation',
            'spy_product',
            'spy_product_abstract_localized_attributes',
            'spy_product_localized_attributes',
            'spy_stock_product',
        ];
    }

    /**
     * @return array
     */
    protected function getParamsForLocaleFilter()
    {
        return [
            [
                'table' => 'spy_product_abstract_localized_attributes',
                'column' => 'fk_locale',
                'value' => 46,
            ],
            [
                'table' => 'spy_product_localized_attributes',
                'column' => 'fk_locale',
                'value' => 46,
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
                'table' => 'spy_product_abstract',
                'column' => 'updated_at',
                'value' => '2017-01-01',
            ],
            [
                'table' => 'spy_product_abstract',
                'column' => 'updated_at',
                'value' => '2017-12-01',
            ],
        ];
    }

}
