<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
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
    public function testDeleteNodeById()
    {
        $categoryFacade = $this->createCategoryFacade();
        $rootCategoryNodeTransfer = $categoryFacade->getNodeById(static::CATEGORY_NODE_ID_ROOT);

        //create initial category (inside root)
        $categoryTransfer1 = (new CategoryTransfer())
            ->fromArray([
                'categoryKey' => 'Category 1',
                'categoryNode' => new NodeTransfer(),
                'parentCategoryNode' => $rootCategoryNodeTransfer,
            ]);
        $categoryFacade->create($categoryTransfer1);

        //create a child to the initial category
        $categoryTransfer2 = (new CategoryTransfer())
            ->fromArray([
                'categoryKey' => 'Category 2',
                'categoryNode' => new NodeTransfer(),
                'parentCategoryNode' => $categoryTransfer1->getCategoryNode(),
            ]);
        $categoryFacade->create($categoryTransfer2);

        //create a control child to the child of initial
        $categoryTransfer3 = (new CategoryTransfer())
            ->fromArray([
                'categoryKey' => 'Category 3',
                'categoryNode' => new NodeTransfer(),
                'parentCategoryNode' => $categoryTransfer2->getCategoryNode(),
            ]);
        $categoryFacade->create($categoryTransfer3);

        //add extra parent to initial node (make c1 enveloped into c1 through c2)
        $categoryTransfer1->setExtraParents(new ArrayObject([
            $categoryTransfer2->getCategoryNode()
        ]));
        $categoryFacade->update($categoryTransfer1);

        //test on delete
        $categoryFacade->delete($categoryTransfer2->getIdCategory());

        $resultNodes = $this->getCategoryNodeQuery()
            ->filterByFkParentCategoryNode($categoryTransfer1->getCategoryNode()->getIdCategoryNode())
            ->find();

        $this->assertEquals(1, $resultNodes->count());
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
        return new CategoryFacade();
    }

}
