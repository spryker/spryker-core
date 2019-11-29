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
    public function setUp(): void
    {
        parent::setUp();

        $this->productDataFeedQueryContainer = $this->createProductDataFeedQueryContainer();
        $this->productDataFeedTransfer = $this->createProductDataFeedTransfer();
        $this->idLocale = $this->getIdLocale();
    }

    /**
     * @return void
     */
    public function testQueryAbstractProductDataFeed(): void
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
    public function testQueryAbstractProductDataFeedWithJoinedCategories(): void
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
    public function testQueryAbstractProductDataFeedWithJoinedPrices(): void
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
    public function testQueryAbstractProductDataFeedWithJoinedOptions(): void
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
    public function testQueryAbstractProductDataFeedWithJoinedImages(): void
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
    public function testQueryAbstractProductDataFeedWithJoinedVariants(): void
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
    public function testQueryAbstractProductDataFeedWithJoinedAll(): void
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
    public function testQueryAbstractProductDataFeedWithLocaleId(): void
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
    public function testQueryAbstractProductDataFeedWithDatesFilter(): void
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
    protected function createProductDataFeedQueryContainer(): ProductAbstractDataFeedQueryContainer
    {
        $productDataFeedQueryContainer = new ProductAbstractDataFeedQueryContainer();

        return $productDataFeedQueryContainer;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractDataFeedTransfer
     */
    protected function createProductDataFeedTransfer(): ProductAbstractDataFeedTransfer
    {
        $productDataFeedTransfer = new ProductAbstractDataFeedTransfer();

        return $productDataFeedTransfer;
    }

    /**
     * @return int
     */
    protected function getIdLocale(): int
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
    protected function getJoinedTablesNames(SpyProductAbstractQuery $query): array
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
    protected function getSortedExpectedJoinedTables(array $tablesArray): array
    {
        asort($tablesArray);
        $tablesArray = array_values($tablesArray);

        return $tablesArray;
    }

    /**
     * @return array
     */
    protected function getDefaultJoinedTables(): array
    {
        return [
            'spy_product_abstract_localized_attributes',
        ];
    }

    /**
     * @return array
     */
    protected function getCategoryJoinedTables(): array
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
    protected function getPriceJoinedTables(): array
    {
        return [
            'spy_price_product',
            'spy_price_type',
        ];
    }

    /**
     * @return array
     */
    protected function getOptionJoinedTables(): array
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
    protected function getImageJoinedTables(): array
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
    protected function getVariantJoinedTables(): array
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
    protected function getParamsForLocaleFilter(): array
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
    protected function getParamsForDateFilter(): array
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
