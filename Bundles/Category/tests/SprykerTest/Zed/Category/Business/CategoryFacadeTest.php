<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryBuilder;
use Generated\Shared\DataBuilder\CategoryLocalizedAttributesBuilder;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Category\Business\CategoryFacadeInterface;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Communication\Plugin\Category\MainChildrenPropagationCategoryStoreAssignerPlugin;
use Spryker\Zed\Category\Communication\Plugin\CategoryUrlPathPrefixUpdaterPlugin;

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

    protected const TEST_LOCALE = 'en_US';
    protected const TEST_STORE_DE = 'DE';
    protected const TEST_STORE_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected $tester;

    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            CategoryDependencyProvider::PLUGIN_CATEGORY_STORE_ASSIGNER,
            new MainChildrenPropagationCategoryStoreAssignerPlugin()
        );
    }

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
     * @group her
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
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
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
     * @return void
     */
    public function testCreateCategoryWillCreateCategoryWithStoreRelation(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE]);

        $parentCategoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryStoreRelation($parentCategoryTransfer->getIdCategory(), $storeTransfer->getIdStore());

        $storeRelationTransfer = (new StoreRelationTransfer())->addIdStores($storeTransfer->getIdStore());
        $categoryTemplateTransfer = $this->tester->haveCategoryTemplate();

        $categoryTransfer = (new CategoryBuilder([
            CategoryTransfer::ID_CATEGORY => null,
            CategoryTransfer::LOCALIZED_ATTRIBUTES => [
                $this->createCategoryLocalizedAttributesTransferForLocale($localeTransfer)->toArray(),
            ],
            CategoryTransfer::STORE_RELATION => $storeRelationTransfer->toArray(),
            CategoryTransfer::CATEGORY_TEMPLATE => $categoryTemplateTransfer->toArray(),
            CategoryTransfer::FK_CATEGORY_TEMPLATE => $categoryTemplateTransfer->getIdCategoryTemplate(),
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNode(),
        ]))->withCategoryNode([
            NodeTransfer::ID_CATEGORY_NODE => null,
        ])->build();

        // Act
        $this->getFacade()->create($categoryTransfer);

        // Assert
        $categoryEntity = SpyCategoryQuery::create()
            ->filterByCategoryKey($categoryTransfer->getCategoryKey())
            ->findOne();
        $this->assertNotNull($categoryEntity, 'Category should be successfully created.');

        $categoryStoreEntity = SpyCategoryStoreQuery::create()
            ->filterByFkCategory($categoryEntity->getIdCategory())
            ->filterByFkStore($storeTransfer->getIdStore())
            ->findOne();
        $this->assertNotNull($categoryStoreEntity, 'Relation between Category and Store should be successfully created.');
    }

    /**
     * @return void
     */
    public function testCreateCategoryWillCreateCategoryNodeWithCorrectUrl(): void
    {
        // Arrange
        $this->tester->setDependency(CategoryDependencyProvider::PLUGINS_CATEGORY_URL_PATH, [
            new CategoryUrlPathPrefixUpdaterPlugin(),
        ]);

        $parentCategoryTransfer = $this->tester->haveCategory();

        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE]);
        $categoryTemplateTransfer = $this->tester->haveCategoryTemplate();
        $categoryLocalizedAttributesTransfer = $this->createCategoryLocalizedAttributesTransferForLocale($localeTransfer);

        $categoryTransfer = (new CategoryBuilder([
            CategoryTransfer::ID_CATEGORY => null,
            CategoryTransfer::LOCALIZED_ATTRIBUTES => [
                $categoryLocalizedAttributesTransfer->toArray(),
            ],
            CategoryTransfer::CATEGORY_TEMPLATE => $categoryTemplateTransfer->toArray(),
            CategoryTransfer::FK_CATEGORY_TEMPLATE => $categoryTemplateTransfer->getIdCategoryTemplate(),
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNode(),
        ]))->withCategoryNode([
            NodeTransfer::ID_CATEGORY_NODE => null,
        ])->build();
        $expectedUrl = '/en/' . mb_strtolower(str_replace(' ', '-', $categoryLocalizedAttributesTransfer->getName()));

        // Act
        $this->getFacade()->create($categoryTransfer);

        // Assert
        $categoryEntity = SpyCategoryQuery::create()
            ->filterByCategoryKey($categoryTransfer->getCategoryKey())
            ->findOne();
        $this->assertNotNull($categoryEntity, 'Category should be successfully created.');

        $categoryNodeEntity = SpyCategoryNodeQuery::create()
            ->filterByFkCategory($categoryEntity->getIdCategory())
            ->findOne();
        $this->assertNotNull($categoryNodeEntity, 'Category Node should be successfully created.');

        $urlEntity = SpyUrlQuery::create()
            ->filterByFkResourceCategorynode($categoryNodeEntity->getIdCategoryNode())
            ->findOne();
        $this->assertNotNull($urlEntity, 'Category Url should be successfully created.');
        $this->assertEquals($expectedUrl, $urlEntity->getUrl(), 'Urls should be the same.');
    }

    /**
     * @return void
     */
    public function testGetCategoryNodesWithRelativeNodesByCriteriaWillReturnAllRequestedNodeTransfers(): void
    {
        // Arrange
        $categoryTransfer1 = $this->tester->haveCategory();
        $categoryTransfer2 = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer1->getCategoryNode()->toArray(),
        ]);
        $categoryTransfer3 = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer2->getCategoryNode()->toArray(),
        ]);
        $expectedCategoryNodeIds = [
            $categoryTransfer1->getCategoryNode()->getIdCategoryNode(),
            $categoryTransfer2->getCategoryNode()->getIdCategoryNode(),
            $categoryTransfer3->getCategoryNode()->getIdCategoryNode(),
        ];

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->addIdCategoryNode($categoryTransfer2->getCategoryNode()->getIdCategoryNode());

        // Act
        $nodeTransfers = $this->getFacade()->getCategoryNodesWithRelativeNodesByCriteria(
            $categoryNodeCriteriaTransfer
        );

        // Assert
        $this->assertCount(3, $nodeTransfers, 'The number of category nodes does not equal the expected value.');

        $resultCategoryNodeIds = array_map(function (NodeTransfer $nodeTransfer): int {
            return $nodeTransfer->getIdCategoryNode();
        }, $nodeTransfers);
        $this->assertEmpty(array_diff($expectedCategoryNodeIds, $resultCategoryNodeIds), 'Returned category nodes ids do not equal expected values.');
    }

    /**
     * @return void
     */
    public function testGetCategoryNodesByCriteriaWillReturnCorrectCategoryNodes(): void
    {
        // Arrange
        $categoryTransfer1 = $this->tester->haveCategory();
        $categoryTransfer2 = $this->tester->haveCategory();

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->addIdCategory($categoryTransfer1->getIdCategory())
            ->addIdCategory($categoryTransfer2->getIdCategory());

        // Act
        $nodeCollectionTransfer = $this->getFacade()->getCategoryNodesByCriteria($categoryNodeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $nodeCollectionTransfer->getNodes(), 'Expected 2 category nodes in results.');
        $this->assertSame(
            $categoryTransfer1->getCategoryNode()->getIdCategoryNode(),
            $nodeCollectionTransfer->getNodes()->offsetGet(0)->getIdCategoryNode(),
            'Returned category nodes id do not equal expected value.'
        );
        $this->assertSame(
            $categoryTransfer2->getCategoryNode()->getIdCategoryNode(),
            $nodeCollectionTransfer->getNodes()->offsetGet(1)->getIdCategoryNode(),
            'Returned category nodes id do not equal expected value.'
        );
    }

    /**
     * @return void
     */
    public function testGetCategoryNodesByCriteriaWillReturnCorrectNodeTransfers(): void
    {
        // Arrange
        $categoryTransfer1 = $this->tester->haveCategory();
        $categoryTransfer2 = $this->tester->haveCategory();

        $nodeTransfer1 = $categoryTransfer1->getCategoryNode();
        $nodeTransfer2 = $categoryTransfer2->getCategoryNode();
        $nodeTransferIds = [
            $nodeTransfer1->getIdCategoryNode(),
            $nodeTransfer2->getIdCategoryNode(),
        ];

        // Act
        $nodeCollectionTransfer = $this->getFacade()->getCategoryNodesByCriteria(
            (new CategoryNodeCriteriaTransfer())
                ->setCategoryNodeIds($nodeTransferIds)
                ->setWithRelations(true)
        );

        // Assert
        $this->assertCount(2, $nodeCollectionTransfer->getNodes(), 'The number of category nodes does not equal the expected value.');

        $resultNodeTransfer1 = $nodeCollectionTransfer->getNodes()->offsetGet(0);
        $this->assertInstanceOf(
            NodeTransfer::class,
            $resultNodeTransfer1,
            'The class of returned category node does not equal to an expected value.'
        );
        $this->assertTrue(
            in_array($resultNodeTransfer1->getIdCategoryNode(), $nodeTransferIds, true),
            'The returned category node id does not present in the list of expected category node ids.'
        );

        $resultNodeTransfer2 = $nodeCollectionTransfer->getNodes()->offsetGet(1);
        $this->assertInstanceOf(
            NodeTransfer::class,
            $resultNodeTransfer2,
            'The class of returned category node does not equal to an expected value.'
        );
        $this->assertTrue(
            in_array($resultNodeTransfer2->getIdCategoryNode(), $nodeTransferIds, true),
            'The returned category node id does not present in the list of expected category node ids.'
        );
    }

    /**
     * @return void
     */
    public function testUpdateCategoryStoreRelationWithMainChildrenPropagationWillAddOnlyNewStoreRelation(): void
    {
        // Arrange
        $deStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
        $atStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT]);

        $parentCategoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryStoreRelation($parentCategoryTransfer->getIdCategory(), $deStoreTransfer->getIdStore());

        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNode(),
        ]);

        $newStoreRelationTransfer = (new StoreRelationTransfer())
            ->addIdStores($deStoreTransfer->getIdStore())
            ->addIdStores($atStoreTransfer->getIdStore());

        // Act
        $this->getFacade()->updateCategoryStoreRelationWithMainChildrenPropagation(
            $parentCategoryTransfer->getIdCategory(),
            $newStoreRelationTransfer
        );

        // Assert
        $parentCategoryStoreRelationStoreIds = $this->getCategoryRelationStoreIds($parentCategoryTransfer->getIdCategory());
        $childCategoryStoreRelationStoreIds = $this->getCategoryRelationStoreIds($childCategoryTransfer->getIdCategory());

        $this->assertCount(2, $parentCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');
        $this->assertCount(1, $childCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');

        $this->assertEmpty(
            array_diff([$deStoreTransfer->getIdStore(), $atStoreTransfer->getIdStore()], $parentCategoryStoreRelationStoreIds),
            'Category store relations are different from expecting value.'
        );
        $this->assertEmpty(
            array_diff([$atStoreTransfer->getIdStore()], $childCategoryStoreRelationStoreIds),
            'Category store relations are different from expecting value.'
        );
    }

    /**
     * @return void
     */
    public function testUpdateCategoryStoreRelationWithMainChildrenPropagationWillDeleteObsoleteStoreRelation(): void
    {
        // Arrange
        $deStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
        $atStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT]);

        $parentCategoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryStoreRelation($parentCategoryTransfer->getIdCategory(), $deStoreTransfer->getIdStore());
        $this->tester->haveCategoryStoreRelation($parentCategoryTransfer->getIdCategory(), $atStoreTransfer->getIdStore());

        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNode(),
        ]);
        $this->tester->haveCategoryStoreRelation($parentCategoryTransfer->getIdCategory(), $atStoreTransfer->getIdStore());

        $newStoreRelationTransfer = (new StoreRelationTransfer())
            ->addIdStores($deStoreTransfer->getIdStore());

        // Act
        $this->getFacade()->updateCategoryStoreRelationWithMainChildrenPropagation(
            $parentCategoryTransfer->getIdCategory(),
            $newStoreRelationTransfer
        );

        // Assert
        $parentCategoryStoreRelationStoreIds = $this->getCategoryRelationStoreIds($parentCategoryTransfer->getIdCategory());
        $childCategoryStoreRelationStoreIds = $this->getCategoryRelationStoreIds($childCategoryTransfer->getIdCategory());

        $this->assertCount(1, $parentCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');
        $this->assertCount(0, $childCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');

        $this->assertEmpty(
            array_diff([$deStoreTransfer->getIdStore()], $parentCategoryStoreRelationStoreIds),
            'Category store relations are different from expecting value.'
        );
    }

    /**
     * @return void
     */
    public function testUpdateCategoryStoreRelationWithMainChildrenPropagationWillNotCreateCategoryStoreRelationIfParentCategoryDontHaveIt(): void
    {
        // Arrange
        $deStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
        $atStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT]);

        $parentCategoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryStoreRelation($parentCategoryTransfer->getIdCategory(), $deStoreTransfer->getIdStore());

        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNode(),
        ]);
        $this->tester->haveCategoryStoreRelation($childCategoryTransfer->getIdCategory(), $deStoreTransfer->getIdStore());

        $newStoreRelationTransfer = (new StoreRelationTransfer())
            ->addIdStores($deStoreTransfer->getIdStore())
            ->addIdStores($atStoreTransfer->getIdStore());

        // Act
        $this->getFacade()->updateCategoryStoreRelationWithMainChildrenPropagation(
            $childCategoryTransfer->getIdCategory(),
            $newStoreRelationTransfer
        );

        // Assert
        $parentCategoryStoreRelationStoreIds = $this->getCategoryRelationStoreIds($parentCategoryTransfer->getIdCategory());
        $childCategoryStoreRelationStoreIds = $this->getCategoryRelationStoreIds($childCategoryTransfer->getIdCategory());

        $this->assertCount(1, $parentCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');
        $this->assertCount(1, $childCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');

        $this->assertEmpty(
            array_diff([$deStoreTransfer->getIdStore()], $parentCategoryStoreRelationStoreIds),
            'Category store relations are different from expecting value.'
        );$this->assertEmpty(
            array_diff([$deStoreTransfer->getIdStore()], $parentCategoryStoreRelationStoreIds),
            'Category store relations are different from expecting value.'
        );
    }

    /**
     * @return void
     */
    public function testUpdateCategoryWhenParentCategoryIsChangedWillRemoveStoreRelationsMissingForParentCategory(): void
    {
        // Arrange
        $deStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);
        $atStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT]);

        $firstParentCategoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryStoreRelation($firstParentCategoryTransfer->getIdCategory(), $deStoreTransfer->getIdStore());

        $secondParentCategoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryStoreRelation($secondParentCategoryTransfer->getIdCategory(), $atStoreTransfer->getIdStore());

        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $firstParentCategoryTransfer->getCategoryNode(),
        ]);
        $this->tester->haveCategoryStoreRelation($childCategoryTransfer->getIdCategory(), $deStoreTransfer->getIdStore());

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($childCategoryTransfer->getIdCategory())
            ->setWithChildrenRecursively(true);
        $childCategoryTransfer = $this->getFacade()->findCategory($categoryCriteriaTransfer);

        $childCategoryTransfer->setParentCategoryNode($secondParentCategoryTransfer->getCategoryNode());

        // Act
        $this->getFacade()->update($childCategoryTransfer);

        // Assert
        $childCategoryStoreRelationStoreIds = $this->getCategoryRelationStoreIds($childCategoryTransfer->getIdCategory());
        $this->assertCount(0, $childCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');
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

    /**
     * @param int $idCategory
     *
     * @return int[]
     */
    protected function getCategoryRelationStoreIds(int $idCategory): array
    {
        return SpyCategoryStoreQuery::create()
            ->filterByFkCategory($idCategory)
            ->select(SpyCategoryStoreTableMap::COL_FK_STORE)
            ->find()
            ->getData();
    }
}
