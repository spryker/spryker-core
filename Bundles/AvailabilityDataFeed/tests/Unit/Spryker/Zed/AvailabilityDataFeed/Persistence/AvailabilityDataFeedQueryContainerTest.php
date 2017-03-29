<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\AvailabilityDataFeed\Persistence;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AvailabilityDataFeedTransfer;
use Generated\Shared\Transfer\ProductDataFeedTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Stock\Persistence\Base\SpyStockProductQuery;
use Spryker\Zed\AvailabilityDataFeed\Persistence\AvailabilityDataFeedQueryContainer;
use Spryker\Zed\Stock\Persistence\StockQueryContainer;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductDataFeed
 * @group Persistence
 * @group ProductDataFeedQueryContainer
 */
class AvailabilityDataFeedQueryContainerTest extends Test
{

    /**
     * @var AvailabilityDataFeedQueryContainer
     */
    protected $availabilityDataFeedQueryContainer;

    /**
     * @var AvailabilityDataFeedTransfer
     */
    protected $availabilityDataFeedTransfer;

    public function setUp()
    {
        parent::setUp();

        $this->availabilityDataFeedQueryContainer = $this->createAvailabilityDataFeedQueryContainer();
        $this->availabilityDataFeedTransfer = $this->createProductDataFeedTransfer();
    }

    public function testGetProductDataFeedQuery()
    {
        $query = $this->availabilityDataFeedQueryContainer
            ->getAvailabilityDataFeedQuery($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = $this->getDefaultJoinedTables();
        $joinedTables = $this->getJoinedTablesNames($query);


        $this->assertTrue($query instanceof SpyProductAbstractQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    public function testGetProductDataFeedQueryWithJoinedCategories()
    {
        $this->availabilityDataFeedTransfer->setIsJoinCategory(true);
        $query = $this->availabilityDataFeedQueryContainer
            ->getProductDataFeedQuery($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getCategoryJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertTrue($query instanceof SpyProductAbstractQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    public function testGetProductDataFeedQueryWithJoinedPrices()
    {
        $this->availabilityDataFeedTransfer->setIsJoinPrice(true);
        $query = $this->availabilityDataFeedQueryContainer
            ->getProductDataFeedQuery($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getPriceJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertTrue($query instanceof SpyProductAbstractQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    public function testGetProductDataFeedQueryWithJoinedOptions()
    {
        $this->availabilityDataFeedTransfer->setIsJoinOption(true);
        $query = $this->availabilityDataFeedQueryContainer
            ->getProductDataFeedQuery($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getOptionJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertTrue($query instanceof SpyProductAbstractQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    public function testGetProductDataFeedQueryWithJoinedImages()
    {
        $this->availabilityDataFeedTransfer->setIsJoinImage(true);
        $query = $this->availabilityDataFeedQueryContainer
            ->getProductDataFeedQuery($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getImageJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertTrue($query instanceof SpyProductAbstractQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    public function testGetProductDataFeedQueryWithJoinedVariants()
    {
        $this->availabilityDataFeedTransfer->setIsJoinVariant(true);
        $query = $this->availabilityDataFeedQueryContainer
            ->getProductDataFeedQuery($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getVariantJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertTrue($query instanceof SpyProductAbstractQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    public function testGetProductDataFeedQueryWithJoinedAll()
    {
        $this->availabilityDataFeedTransfer->setIsJoinVariant(true);
        $this->availabilityDataFeedTransfer->setIsJoinCategory(true);
        $this->availabilityDataFeedTransfer->setIsJoinImage(true);
        $this->availabilityDataFeedTransfer->setIsJoinPrice(true);
        $this->availabilityDataFeedTransfer->setIsJoinOption(true);
        $query = $this->availabilityDataFeedQueryContainer
            ->getProductDataFeedQuery($this->availabilityDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getCategoryJoinedTables(),
            $this->getImageJoinedTables(),
            $this->getPriceJoinedTables(),
            $this->getOptionJoinedTables(),
            $this->getVariantJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertTrue($query instanceof SpyProductAbstractQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    public function testGetProductDataFeedQueryWithLocaleId()
    {
        $this->availabilityDataFeedTransfer->setLocaleId(46);
        $query = $this->availabilityDataFeedQueryContainer
            ->getProductDataFeedQuery($this->availabilityDataFeedTransfer);

        $this->assertTrue($query instanceof SpyProductAbstractQuery);
        $this->assertEquals($this->getParamsForLocaleFilter(), $query->getParams());
    }

    public function testGetProductDataFeedQueryWithDatesFilter()
    {
        $this->availabilityDataFeedTransfer->setUpdatedFrom('2017-01-01');
        $this->availabilityDataFeedTransfer->setUpdatedTo('2017-12-01');
        $query = $this->availabilityDataFeedQueryContainer
            ->getProductDataFeedQuery($this->availabilityDataFeedTransfer);

        $this->assertTrue($query instanceof SpyProductAbstractQuery);
        $this->assertEquals($this->getParamsForDateFilter(), $query->getParams());
    }

    /**
     * @return AvailabilityDataFeedQueryContainer
     */
    protected function createAvailabilityDataFeedQueryContainer()
    {
        $stockQueryContainer = new StockQueryContainer();
        $availabilityDataFeedQueryContainer = new AvailabilityDataFeedQueryContainer($stockQueryContainer);

        return $availabilityDataFeedQueryContainer;
    }

    /**
     * @return ProductDataFeedTransfer
     */
    protected function createProductDataFeedTransfer()
    {
        $productDataFeedTransfer = new ProductDataFeedTransfer();

        return $productDataFeedTransfer;
    }

    /**
     * @param SpyStockProductQuery $query
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
        return [
            'spy_product_abstract_localized_attributes',
            'spy_touch',
        ];
    }

    /**
     * @return array
     */
    protected function getCategoryJoinedTables()
    {
        return [
            'spy_product_category',
            'spy_category',
            'spy_category_attribute',
        ];
    }

    /**
     * @return array
     */
    protected function getPriceJoinedTables()
    {
        return [
            'spy_price_product',
            'spy_price_type',
        ];
    }

    /**
     * @return array
     */
    protected function getOptionJoinedTables()
    {
        return [
            'spy_product_abstract_product_option_group',
            'spy_product_option_group',
            'spy_product_option_value',
        ];
    }

    /**
     * @return array
     */
    protected function getImageJoinedTables()
    {
        return [
            'spy_product_image',
            'spy_product_image_set',
            'spy_product_image_set_to_product_image',
        ];
    }

    /**
     * @return array
     */
    protected function getVariantJoinedTables()
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
                'table' => 'spy_product_abstract_localized_attributes',
                'column' => 'fk_locale',
                'value' => 46,
            ],
            [
                'table' => 'spy_product_abstract_localized_attributes',
                'column' => 'fk_locale',
                'value' => 46,
            ],
            [
                'table' => null,
                'type' => 2,
                'value' => 'product_abstract',
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
                'value' => 'product_abstract',
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