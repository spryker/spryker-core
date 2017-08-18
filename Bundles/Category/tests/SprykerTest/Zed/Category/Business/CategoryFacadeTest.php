<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Spryker\Zed\Category\Business\CategoryFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Facade
 * @group CategoryFacadeTest
 * Add your own group annotations below this line
 */
class CategoryFacadeTest extends Unit
{

    /**
     * @return void
     */
    public function testReadWithRootCategoryReturnsCategoryTransfer()
    {
        $categoryFacade = new CategoryFacade();

        $this->assertInstanceOf(CategoryTransfer::class, $categoryFacade->read($this->getRootCategoryId()));
    }

    /**
     * @return void
     */
    public function testReadWithNonRootCategoryReturnsCategoryTransfer()
    {
        $categoryFacade = new CategoryFacade();

        $this->assertInstanceOf(CategoryTransfer::class, $categoryFacade->read($this->getNonRootCategoryId()));
    }

    /**
     * @return int
     */
    protected function getRootCategoryId()
    {
        return $this->getCategoryNodeQuery()->findOneByIsRoot(true)->getFkCategory();
    }

    /**
     * @return int
     */
    protected function getNonRootCategoryId()
    {
        return $this->getCategoryNodeQuery()->findOneByIsRoot(false)->getFkCategory();
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    protected function getCategoryNodeQuery()
    {
        return SpyCategoryNodeQuery::create();
    }

}
