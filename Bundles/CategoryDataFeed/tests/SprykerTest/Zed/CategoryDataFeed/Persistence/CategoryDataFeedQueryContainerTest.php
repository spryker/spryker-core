<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryDataFeed\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryDataFeedTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\CategoryDataFeed\Persistence\CategoryDataFeedQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryDataFeed
 * @group Persistence
 * @group CategoryDataFeedQueryContainerTest
 * Add your own group annotations below this line
 */
class CategoryDataFeedQueryContainerTest extends Unit
{
    /**
     * @var \Spryker\Zed\CategoryDataFeed\Persistence\CategoryDataFeedQueryContainer
     */
    protected $categoryDataFeedQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\CategoryDataFeedTransfer
     */
    protected $categoryDataFeedTransfer;

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

        $this->categoryDataFeedQueryContainer = $this->createCategoryDataFeedQueryContainer();
        $this->categoryDataFeedTransfer = $this->createCategoryDataFeedTransfer();
        $this->idLocale = $this->getIdLocale();
    }

    /**
     * @return void
     */
    public function testGetCategoryDataFeedQuery()
    {
        $query = $this->categoryDataFeedQueryContainer
            ->queryCategoryDataFeed($this->categoryDataFeedTransfer);

        $expectedJoinedTables = $this->getDefaultJoinedTables();
        $joinedTables = $this->getJoinedTablesNames($query);

        $this->assertInstanceOf(SpyCategoryQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testGetCategoryDataFeedQueryWithJoinedProducts()
    {
        $this->categoryDataFeedTransfer->setJoinAbstractProduct(true);
        $query = $this->categoryDataFeedQueryContainer
            ->queryCategoryDataFeed($this->categoryDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getProductJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertInstanceOf(SpyCategoryQuery::class, $query);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    /**
     * @return void
     */
    public function testGetCategoryDataFeedQueryWithJoinedProductsAndLocaleFilter()
    {
        $this->categoryDataFeedTransfer->setJoinAbstractProduct(true);
        $this->categoryDataFeedTransfer->setIdLocale($this->getIdLocale());
        $query = $this->categoryDataFeedQueryContainer
            ->queryCategoryDataFeed($this->categoryDataFeedTransfer);

        $this->assertInstanceOf(SpyCategoryQuery::class, $query);
        $this->assertEquals($this->getParamsForLocaleFilter(), $query->getParams());
    }

    /**
     * @return \Spryker\Zed\CategoryDataFeed\Persistence\CategoryDataFeedQueryContainer
     */
    protected function createCategoryDataFeedQueryContainer()
    {
        $categoryDataFeedQueryContainer = new CategoryDataFeedQueryContainer();

        return $categoryDataFeedQueryContainer;
    }

    /**
     * @return \Generated\Shared\Transfer\CategoryDataFeedTransfer
     */
    protected function createCategoryDataFeedTransfer()
    {
        $categoryDataFeedTransfer = new CategoryDataFeedTransfer();

        return $categoryDataFeedTransfer;
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
     * @param \Orm\Zed\Category\Persistence\SpyCategoryQuery $query
     *
     * @return array
     */
    protected function getJoinedTablesNames(SpyCategoryQuery $query)
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
            'spy_category_attribute',
            'spy_category_node',
        ];
    }

    /**
     * @return array
     */
    protected function getProductJoinedTables()
    {
        return [
            'spy_product_abstract',
            'spy_product_abstract_localized_attributes',
            'spy_product_category',
        ];
    }

    /**
     * @return array
     */
    protected function getParamsForLocaleFilter()
    {
        return [
            [
                'table' => 'spy_category_attribute',
                'column' => 'fk_locale',
                'value' => $this->idLocale,
            ],
        ];
    }
}
