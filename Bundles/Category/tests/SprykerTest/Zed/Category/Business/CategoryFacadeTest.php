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
    public const CATEGORY_NODE_ID_ROOT = 1;

    /**
     * @return void
     */
    public function testReadWithRootCategoryReturnsCategoryTransfer()
    {
        $this->assertInstanceOf(CategoryTransfer::class, $this->getFacade()->read($this->getRootCategoryId()));
    }

    /**
     * @return void
     */
    public function testReadWithNonRootCategoryReturnsCategoryTransfer()
    {
        $this->assertInstanceOf(CategoryTransfer::class, $this->getFacade()->read($this->getNonRootCategoryId()));
    }

    /**
     * @return void
     */
    public function testDeleteByIdCategory()
    {
        $rootCategoryNodeTransfer = $this->getFacade()->getNodeById(static::CATEGORY_NODE_ID_ROOT);

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
        $this->getFacade()->update($categoryTransfer1);

        //test on delete
        $this->getFacade()->delete($categoryTransfer2->getIdCategory());

        $resultNodes = $this->getCategoryNodeQuery()
            ->filterByFkParentCategoryNode($categoryTransfer1->getCategoryNode()->getIdCategoryNode())
            ->find();

        $this->assertEquals(1, $resultNodes->count(), 'If parent already contains a moving child category OR it is the same category, then they should be skipped');
        $this->assertEquals($categoryTransfer3->getCategoryNode()->getIdCategoryNode(), $resultNodes->getFirst()->getIdCategoryNode());
    }

    /**
     * @return void
     */
    public function testGetAllCategoryCollectionRetrievesCategoriesWillReturnCategoryRelationTransfer()
    {
        $localeTransfer = $this->tester->haveLocale(['localeName' => 'de_DE']);
        /** @var \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer */
        $categoryCollectionTransfer = $this->tester->getFacade()->getAllCategoryCollection($localeTransfer);
        $this->assertGreaterThan(0, count($categoryCollectionTransfer->getCategories()));
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
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
