<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\CategoryDataFeed\Persistence;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CategoryDataFeedTransfer;
use Orm\Zed\Category\Persistence\Base\SpyCategoryQuery;
use Pyz\Zed\Category\Persistence\CategoryQueryContainer;
use Spryker\Zed\CategoryDataFeed\Persistence\CategoryDataFeedQueryContainer;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group CategoryDataFeed
 * @group Persistence
 * @group CategoryDataFeedQueryContainer
 */
class CategoryDataFeedQueryContainerTest extends Test
{

    /**
     * @var CategoryDataFeedQueryContainer
     */
    protected $categoryDataFeedQueryContainer;

    /**
     * @var CategoryDataFeedTransfer
     */
    protected $categoryDataFeedTransfer;

    public function setUp()
    {
        parent::setUp();

        $this->categoryDataFeedQueryContainer = $this->createCategoryDataFeedQueryContainer();
        $this->categoryDataFeedTransfer = $this->createCategoryDataFeedTransfer();
    }

    public function testGetCategoryDataFeedQuery()
    {
        $query = $this->categoryDataFeedQueryContainer
            ->getCategoryDataFeedQuery($this->categoryDataFeedTransfer);

        $expectedJoinedTables = $this->getDefaultJoinedTables();
        $joinedTables = $this->getJoinedTablesNames($query);

        $this->assertTrue($query instanceof SpyCategoryQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    public function testGetCategoryDataFeedQueryWithJoinedProducts()
    {
        $this->categoryDataFeedTransfer->setIsJoinProduct(true);
        $query = $this->categoryDataFeedQueryContainer
            ->getCategoryDataFeedQuery($this->categoryDataFeedTransfer);

        $expectedJoinedTables = array_merge(
            $this->getDefaultJoinedTables(),
            $this->getProductJoinedTables()
        );
        $joinedTables = $this->getJoinedTablesNames($query);
        $expectedJoinedTables = $this->getSortedExpectedJoinedTables($expectedJoinedTables);

        $this->assertTrue($query instanceof SpyCategoryQuery);
        $this->assertEquals($expectedJoinedTables, $joinedTables);
    }

    public function testGetCategoryDataFeedQueryWithJoinedProductsAndLocaleFilter()
    {
        $this->categoryDataFeedTransfer->setLocaleId(46);
        $query = $this->categoryDataFeedQueryContainer
            ->getCategoryDataFeedQuery($this->categoryDataFeedTransfer);

        $this->assertTrue($query instanceof SpyCategoryQuery);
        $this->assertEquals($this->getParamsForLocaleFilter(), $query->getParams());
    }

    /**
     * @return CategoryDataFeedQueryContainer
     */
    protected function createCategoryDataFeedQueryContainer()
    {
        $categoryQueryContainer = new CategoryQueryContainer();
        $categoryDataFeedQueryContainer = new CategoryDataFeedQueryContainer($categoryQueryContainer);

        return $categoryDataFeedQueryContainer;
    }

    /**
     * @return CategoryDataFeedTransfer
     */
    protected function createCategoryDataFeedTransfer()
    {
        $categoryDataFeedTransfer = new CategoryDataFeedTransfer();

        return $categoryDataFeedTransfer;
    }

    /**
     * @param SpyCategoryQuery $query
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
                'value' => 46,
            ],
        ];
    }

}