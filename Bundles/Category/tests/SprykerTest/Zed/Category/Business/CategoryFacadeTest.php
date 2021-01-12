<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryLocalizedAttributesBuilder;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;
use Spryker\Zed\Category\Business\CategoryFacadeInterface;

/**
 * Auto-generated group annotations
 *
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
    public const CATEGORY_ID_ROOT = 1;

    protected const TEST_STORE_NAME = 'DE';

    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindCategoryByIdWithRootCategoryReturnsCategoryTransfer(): void
    {
        $this->assertInstanceOf(CategoryTransfer::class, $this->getFacade()->findCategoryById($this->getRootCategoryId()));
    }

    /**
     * @return void
     */
    public function testFindCategoryByIdWithNonRootCategoryReturnsCategoryTransfer(): void
    {
        $this->assertInstanceOf(CategoryTransfer::class, $this->getFacade()->findCategoryById($this->getNonRootCategoryId()));
    }

    /**
     * @return void
     */
    public function testDeleteByIdCategory(): void
    {
        $categoryTransfer = $this->getFacade()
            ->findCategory((new CategoryCriteriaTransfer())->setIdCategory(static::CATEGORY_ID_ROOT));

        $rootCategoryNodeTransfer = $categoryTransfer->getCategoryNode();

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

        $this->assertSame(1, $resultNodes->count(), 'If parent already contains a moving child category OR it is the same category, then they should be skipped');
        $this->assertEquals($categoryTransfer3->getCategoryNode()->getIdCategoryNode(), $resultNodes->getFirst()->getIdCategoryNode());
    }

    /**
     * @return void
     */
    public function testDeleteWillDeleteCategoryStoreRelation(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_NAME]);
        $this->tester->haveCategoryStoreRelation($categoryTransfer->getIdCategory(), $storeTransfer->getIdStore());

        // Act
        $this->getFacade()->delete($categoryTransfer->getIdCategory());

        // Assert
        $categoryStoreRelationsCount = SpyCategoryStoreQuery::create()
            ->filterByFkCategory($categoryTransfer->getIdCategory())
            ->count();
        $this->assertSame(0, $categoryStoreRelationsCount, 'Relations between Category and Store should deleted.');
    }

    /**
     * @return void
     */
    public function testGetAllCategoryCollectionRetrievesCategoriesWillReturnCategoryRelationTransfer(): void
    {
        $localeTransfer = $this->tester->haveLocale(['localeName' => 'de_DE']);
        /** @var \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer */
        $categoryCollectionTransfer = $this->tester->getFacade()->getAllCategoryCollection($localeTransfer);
        $this->assertGreaterThan(0, count($categoryCollectionTransfer->getCategories()));
    }

    /**
     * @return void
     */
    public function testFindCategoryWillFindExistingCategory(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())->setIdCategory($categoryTransfer->getIdCategory());

        // Act
        $resultCategoryTransfer = $this->getFacade()->findCategory($categoryCriteriaTransfer);

        // Assert
        $this->assertEquals($resultCategoryTransfer->getIdCategory(), $categoryTransfer->getIdCategory());
        $this->assertEquals($resultCategoryTransfer->getCategoryKey(), $categoryTransfer->getCategoryKey());
    }

    /**
     * @return void
     */
    public function testFindCategoryWillFindExistingCategoryWithRequiredLocale(): void
    {
        // Arrange
        $localeTransfer1 = $this->tester->haveLocale();
        $localeTransfer2 = $this->tester->haveLocale();
        $categoryLocalizedAttributesTransfer1 = $this->createCategoryLocalizedAttributesTransferForLocale($localeTransfer1);
        $categoryLocalizedAttributesTransfer2 = $this->createCategoryLocalizedAttributesTransferForLocale($localeTransfer2);
        $categoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::LOCALIZED_ATTRIBUTES => [
                $categoryLocalizedAttributesTransfer1->toArray(),
                $categoryLocalizedAttributesTransfer2->toArray(),
            ],
        ]);

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($categoryTransfer->getIdCategory())
            ->setLocaleName($localeTransfer1->getLocaleName());

        // Act
        $resultCategoryTransfer = $this->getFacade()->findCategory($categoryCriteriaTransfer);

        // Assert
        $this->assertEquals($resultCategoryTransfer->getIdCategory(), $categoryTransfer->getIdCategory());
        $this->assertEquals($resultCategoryTransfer->getCategoryKey(), $categoryTransfer->getCategoryKey());
        $this->assertCount(1, $resultCategoryTransfer->getLocalizedAttributes());
        $this->assertEquals(
            $localeTransfer1->getLocaleName(),
            $resultCategoryTransfer->getLocalizedAttributes()->offsetGet(0)->getLocale()->getLocaleName()
        );
    }

    /**
     * @return void
     */
    public function testFindCategoryWillFindExistingCategoryWithFirstLevelChildrenOnly(): void
    {
        // Arrange
        $categoryTransfer1 = $this->tester->haveCategory();
        $categoryTransfer2 = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer1->getCategoryNode()->toArray(),
        ]);
        $categoryTransfer3 = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer2->getCategoryNode()->toArray(),
        ]);

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($categoryTransfer1->getIdCategory())
            ->setWithChildren(true);

        // Act
        $resultCategoryTransfer = $this->getFacade()->findCategory($categoryCriteriaTransfer);

        // Assert
        $this->assertEquals($resultCategoryTransfer->getIdCategory(), $categoryTransfer1->getIdCategory());
        $this->assertEquals($resultCategoryTransfer->getCategoryKey(), $categoryTransfer1->getCategoryKey());
        $nodeCollectionTransfer = $resultCategoryTransfer->getNodeCollection();
        $this->assertCount(1, $nodeCollectionTransfer->getNodes());
        /** @var \Generated\Shared\Transfer\NodeTransfer $childNode */
        $childNode = $nodeCollectionTransfer->getNodes()->offsetGet(0);
        $this->assertEquals($childNode->getIdCategoryNode(), $categoryTransfer1->getCategoryNode()->getIdCategoryNode());
        $childNodeCollectionTransfer = $childNode->getChildrenNodes();
        $this->assertCount(1, $childNodeCollectionTransfer->getNodes());
        /** @var \Generated\Shared\Transfer\NodeTransfer $childChildNode */
        $childChildNode = $childNodeCollectionTransfer->getNodes()->offsetGet(0);
        $this->assertEquals($childChildNode->getIdCategoryNode(), $categoryTransfer2->getCategoryNode()->getIdCategoryNode());
        $this->assertCount(0, $childChildNode->getChildrenNodes()->getNodes());
    }

    /**
     * @return void
     */
    public function testFindCategoryWillFindExistingCategoryWithAllChildren(): void
    {
        // Arrange
        $categoryTransfer1 = $this->tester->haveCategory();
        $categoryTransfer2 = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer1->getCategoryNode()->toArray(),
        ]);
        $categoryTransfer3 = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer2->getCategoryNode()->toArray(),
        ]);

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($categoryTransfer1->getIdCategory())
            ->setWithChildrenRecursively(true);

        // Act
        $resultCategoryTransfer = $this->getFacade()->findCategory($categoryCriteriaTransfer);

        // Assert
        $this->assertEquals($resultCategoryTransfer->getIdCategory(), $categoryTransfer1->getIdCategory());
        $this->assertEquals($resultCategoryTransfer->getCategoryKey(), $categoryTransfer1->getCategoryKey());

        $nodeCollectionTransfer = $resultCategoryTransfer->getNodeCollection();
        $this->assertCount(1, $nodeCollectionTransfer->getNodes());
        /** @var \Generated\Shared\Transfer\NodeTransfer $selfNode */
        $selfNode = $nodeCollectionTransfer->getNodes()->offsetGet(0);
        $this->assertEquals($selfNode->getIdCategoryNode(), $categoryTransfer1->getCategoryNode()->getIdCategoryNode());

        $firstChildNodeCollectionTransfer = $selfNode->getChildrenNodes();
        $this->assertCount(1, $firstChildNodeCollectionTransfer->getNodes());
        /** @var \Generated\Shared\Transfer\NodeTransfer $firstChildNode */
        $firstChildNode = $firstChildNodeCollectionTransfer->getNodes()->offsetGet(0);
        $this->assertEquals($firstChildNode->getIdCategoryNode(), $categoryTransfer2->getCategoryNode()->getIdCategoryNode());

        $secondChildNodeCollectionTransfer = $firstChildNode->getChildrenNodes();
        $this->assertCount(1, $secondChildNodeCollectionTransfer->getNodes());
        /** @var \Generated\Shared\Transfer\NodeTransfer $firstChildNode */
        $secondChildNode = $secondChildNodeCollectionTransfer->getNodes()->offsetGet(0);
        $this->assertEquals($secondChildNode->getIdCategoryNode(), $categoryTransfer3->getCategoryNode()->getIdCategoryNode());
    }

    /**
     * @return int
     */
    protected function getRootCategoryId(): int
    {
        return $this->getCategoryNodeQuery()->findOneByIsRoot(true)->getFkCategory();
    }

    /**
     * @return int
     */
    protected function getNonRootCategoryId(): int
    {
        return $this->getCategoryNodeQuery()->findOneByIsRoot(false)->getFkCategory();
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNodeQuery
     */
    protected function getCategoryNodeQuery(): SpyCategoryNodeQuery
    {
        return SpyCategoryNodeQuery::create();
    }

    /**
     * @return \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected function getFacade(): CategoryFacadeInterface
    {
        return $this->tester->getFacade();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    protected function createCategoryLocalizedAttributesTransferForLocale(LocaleTransfer $localeTransfer): CategoryLocalizedAttributesTransfer
    {
        return (new CategoryLocalizedAttributesBuilder([
            CategoryLocalizedAttributesTransfer::LOCALE => $localeTransfer->toArray(),
        ]))->build();
    }
}
