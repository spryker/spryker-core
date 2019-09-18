<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityDataFeed\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AvailabilityDataFeedTransfer;
use Orm\Zed\Locale\Persistence\Base\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainer;
use Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityDataFeed
 * @group Persistence
 * @group AvailabilityDataFeedQueryContainerTest
 * Add your own group annotations below this line
 */
class AvailabilityDataFeedQueryContainerTest extends Unit
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
     * @var int
     */
    protected $idLocale;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->availabilityDataFeedQueryContainer = $this->createAvailabilityDataFeedQueryContainer();
        $this->availabilityDataFeedTransfer = $this->createAvailabilityDataFeedTransfer();
        $this->idLocale = $this->getIdLocale();
    }

    /**
     * @return void
     */
    public function testGetAvailabilityDataFeedQuery()
    {
        $this->availabilityDataFeedTransfer->setIdLocale($this->idLocale);
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
        $this->availabilityDataFeedTransfer->setIdLocale($this->idLocale);
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
        $this->availabilityDataFeedTransfer->setIdLocale($this->idLocale);
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
        $this->availabilityDataFeedTransfer->setIdLocale($this->idLocale);

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
     * @return int
     */
    protected function getIdLocale()
    {
        $locale = SpyLocaleQuery::create()
            ->filterByLocaleName('de_DE')
            ->findOne();

        return $locale->getIdLocale();
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
                'value' => $this->idLocale,
            ],
            [
                'table' => 'spy_product_localized_attributes',
                'column' => 'fk_locale',
                'value' => $this->idLocale,
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
