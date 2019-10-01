<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAbstractDataFeed\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractDataFeedTransfer;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\ProductAbstractDataFeed\Persistence\ProductAbstractDataFeedQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductAbstractDataFeed
 * @group Persistence
 * @group ProductAbstractDataFeedQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductAbstractDataFeedQueryContainerTest extends Unit
{
    /**
     * @var \Spryker\Zed\ProductAbstractDataFeed\Persistence\ProductAbstractDataFeedQueryContainer
     */
    protected $productDataFeedQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer
     */
    protected $productDataFeedTransfer;

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

        $this->productDataFeedQueryContainer = $this->createProductDataFeedQueryContainer();
        $this->productDataFeedTransfer = $this->createProductDataFeedTransfer();
        $this->idLocale = $this->getIdLocale();
    }

    /**
     * @return void
     */
    public function testQueryAbstractProductDataFeed()
    {
        $query = $this->productDataFeedQueryContainer
            ->queryAbstractProductDataFeed($this->productDataFeedTransfer);

        $expectedJoinedTables = $this->getDefaultJoinedTables();
        $joinedTables = $this->getJoinedTablesNames($query);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testQueryAbstractProductDataFeedWithJoinedCategories()
    {
        $this->productDataFeedTransfer->setJoinCategory(true);
        $query = $this->productDataFeedQueryContainer
            ->queryAbstractProductDataFeed($this->productDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getCategoryJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testQueryAbstractProductDataFeedWithJoinedPrices()
    {
        $this->productDataFeedTransfer->setJoinPrice(true);
        $query = $this->productDataFeedQueryContainer
            ->queryAbstractProductDataFeed($this->productDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getPriceJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testQueryAbstractProductDataFeedWithJoinedOptions()
    {
        $this->productDataFeedTransfer->setJoinOption(true);
        $query = $this->productDataFeedQueryContainer
            ->queryAbstractProductDataFeed($this->productDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getOptionJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testQueryAbstractProductDataFeedWithJoinedImages()
    {
        $this->productDataFeedTransfer->setJoinImage(true);
        $query = $this->productDataFeedQueryContainer
            ->queryAbstractProductDataFeed($this->productDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getImageJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testQueryAbstractProductDataFeedWithJoinedVariants()
    {
        $this->productDataFeedTransfer->setJoinProduct(true);
        $query = $this->productDataFeedQueryContainer
            ->queryAbstractProductDataFeed($this->productDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getVariantJoinedTables(),
            $this->getImageJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testQueryAbstractProductDataFeedWithJoinedAll()
    {
        $this->productDataFeedTransfer->setJoinProduct(true);
        $this->productDataFeedTransfer->setJoinCategory(true);
        $this->productDataFeedTransfer->setJoinImage(true);
        $this->productDataFeedTransfer->setJoinPrice(true);
        $this->productDataFeedTransfer->setJoinOption(true);
        $query = $this->productDataFeedQueryContainer
            ->queryAbstractProductDataFeed($this->productDataFeedTransfer);

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

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testQueryAbstractProductDataFeedWithLocaleId()
    {
        $this->productDataFeedTransfer->setIdLocale($this->idLocale);
        $query = $this->productDataFeedQueryContainer
            ->queryAbstractProductDataFeed($this->productDataFeedTransfer);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($this->getParamsForLocaleFilter(), $query->getParams());
    }

    /**
     * @return void
     */
    public function testQueryAbstractProductDataFeedWithDatesFilter()
    {
        $this->productDataFeedTransfer->setUpdatedFrom('2017-01-01');
        $this->productDataFeedTransfer->setUpdatedTo('2017-12-01');
        $query = $this->productDataFeedQueryContainer
            ->queryAbstractProductDataFeed($this->productDataFeedTransfer);

        $this->assertInstanceOf(SpyProductAbstractQuery::class, $query);
        $this->assertEquals($this->getParamsForDateFilter(), $query->getParams());
    }

    /**
     * @return \Spryker\Zed\ProductAbstractDataFeed\Persistence\ProductAbstractDataFeedQueryContainer
     */
    protected function createProductDataFeedQueryContainer()
    {
        return new ProductAbstractDataFeedQueryContainer();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer
     */
    protected function createProductDataFeedTransfer()
    {
        $productDataFeedTransfer = new ProductAbstractDataFeedTransfer();

        return $productDataFeedTransfer;
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
            'spy_product_abstract_localized_attributes',
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
            'spy_category_node',
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
            'spy_stock',
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
        ];
    }

    /**
     * @return array
     */
    protected function getParamsForDateFilter()
    {
        return [
            [
                'table' => 'spy_product_abstract_localized_attributes',
                'column' => 'updated_at',
                'value' => '2017-01-01',
            ],
            [
                'table' => 'spy_product_abstract_localized_attributes',
                'column' => 'updated_at',
                'value' => '2017-12-01',
            ],
        ];
    }
}
