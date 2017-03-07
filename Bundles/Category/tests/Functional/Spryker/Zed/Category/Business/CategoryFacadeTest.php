<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Category\Business;

use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Category\Business\CategoryFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Category
 * @group Business
 * @group CategoryFacadeTest
 */
class CategoryFacadeTest extends PHPUnit_Framework_TestCase
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
