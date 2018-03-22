<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Facade
 * @group CategoryFacadeTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\Category\CategoryBusinessTester $tester
 */
class CategoryFacadeTest extends Unit
{
    const CATEGORY_NODE_ID_ROOT = 1;

    /**
     * @return void
     */
    public function testReadWithRootCategoryReturnsCategoryTransfer()
    {
        $categoryFacade = $this->createCategoryFacade();

        $this->assertInstanceOf(CategoryTransfer::class, $categoryFacade->read($this->getRootCategoryId()));
    }

    /**
     * @return void
     */
    public function testReadWithNonRootCategoryReturnsCategoryTransfer()
    {
        $categoryFacade = $this->createCategoryFacade();

        $this->assertInstanceOf(CategoryTransfer::class, $categoryFacade->read($this->getNonRootCategoryId()));
    }

    /**
     * @return void
     */
    public function testDeleteByIdCategory()
    {
        $categoryFacade = $this->createCategoryFacade();
        $rootCategoryNodeTransfer = $categoryFacade->getNodeById(static::CATEGORY_NODE_ID_ROOT);

        //create initial category (inside root)
        $categoryTransfer1 = $this->tester->haveCategory([
            'parentCategoryNode' => $rootCategoryNodeTransfer,
        ]);

        //create a child to the initial category
        $categoryTransfer2 = $this->tester->haveCategory([
            'parentCategoryNode' => $categoryTransfer1->getCategoryNode(),
        ]);

        //create a control child to the child of initial
        $categoryTransfer3 = $this->tester->haveCategory([
            'parentCategoryNode' => $categoryTransfer2->getCategoryNode(),
        ]);

        //add extra parent to initial node (make c1 enveloped into c1 through c2)
        $categoryTransfer1->setExtraParents(new ArrayObject([
            $categoryTransfer2->getCategoryNode(),
        ]));
        $categoryFacade->update($categoryTransfer1);

        //test on delete
        $categoryFacade->delete($categoryTransfer2->getIdCategory());

        $resultNodes = $this->getCategoryNodeQuery()
            ->filterByFkParentCategoryNode($categoryTransfer1->getCategoryNode()->getIdCategoryNode())
            ->find();

        $this->assertEquals(1, $resultNodes->count(), 'If parent already contains a moving child category OR it is the same category, then they should be skipped');
        $this->assertEquals($categoryTransfer3->getCategoryNode()->getIdCategoryNode(), $resultNodes->getFirst()->getIdCategoryNode());
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

    /**
     * @return \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected function createCategoryFacade()
    {
        return $this->tester->getLocator()->category()->facade();
    }
}
