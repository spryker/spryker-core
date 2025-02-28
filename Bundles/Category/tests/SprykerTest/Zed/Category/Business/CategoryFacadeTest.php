<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryBuilder;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\UpdateCategoryStoreRelationRequestTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Category\Business\CategoryFacadeInterface;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Communication\Plugin\Category\MainChildrenPropagationCategoryStoreAssignerPlugin;
use Spryker\Zed\Category\Communication\Plugin\CategoryUrlPathPrefixUpdaterPlugin;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface;

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
    /**
     * @var int
     */
    public const CATEGORY_ID_ROOT = 1;

    /**
     * @var string
     */
    protected const TEST_LOCALE_EN = 'en_US';

    /**
     * @var string
     */
    protected const TEST_LOCALE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const TEST_STORE_DE = 'DE';

    /**
     * @var string
     */
    protected const TEST_STORE_AT = 'AT';

    /**
     * @var int
     */
    protected const FAKE_CATEGORY_NODE_ID = 8888;

    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            CategoryDependencyProvider::PLUGIN_CATEGORY_STORE_ASSIGNER,
            new MainChildrenPropagationCategoryStoreAssignerPlugin(),
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
    public function testDeleteWillDeleteCategoryAndAssignNewParentStoreRelationToChildCategory(): void
    {
        // Arrange
        $expectedStoreRelationCount = 2;
        $categoryTransferRoot = $this->tester->haveCategory();
        $storeTransferDE = $this->tester->haveStore([
            StoreTransfer::NAME => static::TEST_STORE_DE,
        ], false);

        $storeTransferAT = $this->tester->haveStore([
            StoreTransfer::NAME => static::TEST_STORE_AT,
        ], false);

        $this->tester->haveCategoryStoreRelation($categoryTransferRoot->getIdCategory(), $storeTransferDE->getIdStore());
        $this->tester->haveCategoryStoreRelation($categoryTransferRoot->getIdCategory(), $storeTransferAT->getIdStore());

        $categoryTransferParent = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransferRoot->getCategoryNode(),
        ]);

        $this->tester->haveCategoryStoreRelation($categoryTransferParent->getIdCategory(), $storeTransferDE->getIdStore());

        $categoryTransferChild = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransferParent->getCategoryNode(),
        ]);

        $this->tester->haveCategoryStoreRelation($categoryTransferChild->getIdCategory(), $storeTransferDE->getIdStore());

        // Act
        $this->getFacade()->delete($categoryTransferParent->getIdCategory());

        // Assert
        $categoryStoreRelationsCount = $this->tester->getStoresCountByIdCategory($categoryTransferChild->getIdCategory());
        $this->assertSame($expectedStoreRelationCount, $categoryStoreRelationsCount, 'Child category should have store relation based on the new parent category.');
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
        $categoryLocalizedAttributesTransfer1 = $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransfer1);
        $categoryLocalizedAttributesTransfer2 = $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransfer2);
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
            $resultCategoryTransfer->getLocalizedAttributes()->offsetGet(0)->getLocale()->getLocaleName(),
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
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_EN]);

        $parentCategoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryStoreRelation($parentCategoryTransfer->getIdCategory(), $storeTransfer->getIdStore());

        $storeRelationTransfer = (new StoreRelationTransfer())->addIdStores($storeTransfer->getIdStore());
        $categoryTemplateTransfer = $this->tester->haveCategoryTemplate();

        $categoryTransfer = (new CategoryBuilder([
            CategoryTransfer::ID_CATEGORY => null,
            CategoryTransfer::LOCALIZED_ATTRIBUTES => [
                $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransfer)->toArray(),
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

        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_EN]);
        $categoryTemplateTransfer = $this->tester->haveCategoryTemplate();
        $categoryLocalizedAttributesTransfer = $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransfer);

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
    public function testGetCategoryNodesWithRelativeNodesWillReturnAllRequestedNodeTransfers(): void
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
        $nodeTransfers = $this->getFacade()
            ->getCategoryNodesWithRelativeNodes($categoryNodeCriteriaTransfer)
            ->getNodes()
            ->getArrayCopy();

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
    public function testGetCategoryNodesWithRelativeNodesWillReturnNodeTransfersWithRelativeNodes(): void
    {
        // Arrange
        $parentCategoryTransfer = $this->tester->haveCategory();

        $categoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNode()->toArray(),
        ]);

        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer->getCategoryNode()->toArray(),
        ]);
        $childCategoryTransfer2 = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer->getCategoryNode()->toArray(),
        ]);

        $expectedCategoryNodeIds = [
            $parentCategoryTransfer->getCategoryNode()->getIdCategoryNode(),
            $categoryTransfer->getCategoryNode()->getIdCategoryNode(),
            $childCategoryTransfer->getCategoryNode()->getIdCategoryNode(),
            $childCategoryTransfer2->getCategoryNode()->getIdCategoryNode(),
        ];

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->addIdCategoryNode($categoryTransfer->getCategoryNode()->getIdCategoryNode());

        // Act
        /** @var array<\Generated\Shared\Transfer\NodeTransfer> $categoryNodeTransfers */
        $categoryNodeTransfers = $this->getFacade()
            ->getCategoryNodesWithRelativeNodes($categoryNodeCriteriaTransfer)
            ->getNodes()
            ->getArrayCopy();

        // Assert
        $this->assertCount(4, $categoryNodeTransfers, 'The number of category nodes is not equal to the expected value.');

        $resultCategoryNodeIds = array_map(function (NodeTransfer $nodeTransfer): int {
            return $nodeTransfer->getIdCategoryNode();
        }, $categoryNodeTransfers);
        $this->assertEmpty(array_diff($expectedCategoryNodeIds, $resultCategoryNodeIds), 'Returned category nodes ids are not equal to expected values.');
    }

    /**
     * @return void
     */
    public function testGetCategoryNodesWithRelativeNodesWillReturnNodeTransfersWithRelativeNodesAndRelations(): void
    {
        // Arrange
        $expectedStoreIds = [
            $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE], false)->getIdStore(),
            $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT], false)->getIdStore(),
        ];

        $localeTransfers = [
            $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_DE]),
            $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_EN]),
        ];

        $expectedLocaleIds = $this->extractLocaleIdsFromLocales($localeTransfers);

        $categoryTransfers = $this->tester->createCategoryWithChildrenAndRelations($localeTransfers, $expectedStoreIds, 3);
        $categoryNodeIds = $this->extractCategoryNodeIdsFromCategoryCollection($categoryTransfers);

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())->addIdCategoryNode($categoryNodeIds[0]);

        // Act
        /** @var array<\Generated\Shared\Transfer\NodeTransfer> $categoryNodeTransfers */
        $categoryNodeTransfers = $this->getFacade()
            ->getCategoryNodesWithRelativeNodes($categoryNodeCriteriaTransfer)
            ->getNodes()
            ->getArrayCopy();

        // Assert
        $this->assertCount(4, $categoryNodeTransfers, 'The number of category nodes is not equal to the expected value.');
        foreach ($categoryNodeTransfers as $categoryNodeTransfer) {
            $this->assertTrue(in_array($categoryNodeTransfer->getIdCategoryNode(), $categoryNodeIds), 'The category node is missing in expected collection.');
            $this->assertCategoryNodeWithRelations(
                $categoryNodeTransfer,
                $expectedLocaleIds,
                $expectedStoreIds,
            );
        }
    }

    /**
     * @return void
     */
    public function testGetCategoryNodesWithRelativeNodesWillReturnEmptyNodeCollectionForNotExistingCategoryNode(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer->getCategoryNode()->toArray(),
        ]);
        $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer->getCategoryNode()->toArray(),
        ]);

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->addIdCategoryNode(static::FAKE_CATEGORY_NODE_ID);

        // Act
        $categoryNodeCollectionTransfer = $this->getFacade()
            ->getCategoryNodesWithRelativeNodes($categoryNodeCriteriaTransfer);

        // Assert
        $this->assertCount(0, $categoryNodeCollectionTransfer->getNodes(), 'The number of category nodes is not equal to the expected value.');
    }

    /**
     * @return void
     */
    public function testGetCategoryNodesWillReturnCorrectCategoryNodes(): void
    {
        // Arrange
        $categoryTransfer1 = $this->tester->haveCategory();
        $categoryTransfer2 = $this->tester->haveCategory();

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->addIdCategory($categoryTransfer1->getIdCategory())
            ->addIdCategory($categoryTransfer2->getIdCategory());

        // Act
        $nodeCollectionTransfer = $this->getFacade()->getCategoryNodes($categoryNodeCriteriaTransfer);

        // Assert
        $this->assertCount(2, $nodeCollectionTransfer->getNodes(), 'Expected 2 category nodes in results.');
        $this->assertSame(
            $categoryTransfer1->getCategoryNode()->getIdCategoryNode(),
            $nodeCollectionTransfer->getNodes()->offsetGet(0)->getIdCategoryNode(),
            'Returned category nodes id do not equal expected value.',
        );
        $this->assertSame(
            $categoryTransfer2->getCategoryNode()->getIdCategoryNode(),
            $nodeCollectionTransfer->getNodes()->offsetGet(1)->getIdCategoryNode(),
            'Returned category nodes id do not equal expected value.',
        );
    }

    /**
     * @return void
     */
    public function testGetCategoryNodesWillReturnCorrectNodeTransfers(): void
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
        $nodeCollectionTransfer = $this->getFacade()->getCategoryNodes(
            (new CategoryNodeCriteriaTransfer())
                ->setCategoryNodeIds($nodeTransferIds)
                ->setWithRelations(true),
        );

        // Assert
        $this->assertCount(2, $nodeCollectionTransfer->getNodes(), 'The number of category nodes does not equal the expected value.');

        $resultNodeTransfer1 = $nodeCollectionTransfer->getNodes()->offsetGet(0);
        $this->assertInstanceOf(
            NodeTransfer::class,
            $resultNodeTransfer1,
            'The class of returned category node does not equal to an expected value.',
        );
        $this->assertTrue(
            in_array($resultNodeTransfer1->getIdCategoryNode(), $nodeTransferIds, true),
            'The returned category node id does not present in the list of expected category node ids.',
        );

        $resultNodeTransfer2 = $nodeCollectionTransfer->getNodes()->offsetGet(1);
        $this->assertInstanceOf(
            NodeTransfer::class,
            $resultNodeTransfer2,
            'The class of returned category node does not equal to an expected value.',
        );
        $this->assertTrue(
            in_array($resultNodeTransfer2->getIdCategoryNode(), $nodeTransferIds, true),
            'The returned category node id does not present in the list of expected category node ids.',
        );
    }

    /**
     * @return void
     */
    public function testUpdateCategoryStoreRelationWithMainChildrenPropagationWillAddOnlyNewStoreRelation(): void
    {
        // Arrange
        $deStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE], false);
        $atStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT], false);

        $parentCategoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryStoreRelation($parentCategoryTransfer->getIdCategory(), $deStoreTransfer->getIdStore());

        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNode(),
        ]);

        $updateCategoryStoreRelationRequestTransfer = (new UpdateCategoryStoreRelationRequestTransfer())
            ->setIdCategory($parentCategoryTransfer->getIdCategory())
            ->setNewStoreAssignment((new StoreRelationTransfer())
                ->addIdStores($deStoreTransfer->getIdStore())
                ->addIdStores($atStoreTransfer->getIdStore()));

        // Act
        $this->getFacade()->updateCategoryStoreRelationWithMainChildrenPropagation($updateCategoryStoreRelationRequestTransfer);

        // Assert
        $parentCategoryStoreRelationStoreIds = $this->tester->getCategoryRelationStoreIds($parentCategoryTransfer->getIdCategory());
        $childCategoryStoreRelationStoreIds = $this->tester->getCategoryRelationStoreIds($childCategoryTransfer->getIdCategory());

        $this->assertCount(2, $parentCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');
        $this->assertCount(1, $childCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');

        $this->assertEmpty(
            array_diff([$deStoreTransfer->getIdStore(), $atStoreTransfer->getIdStore()], $parentCategoryStoreRelationStoreIds),
            'Category store relations are different from expecting value.',
        );
        $this->assertEmpty(
            array_diff([$atStoreTransfer->getIdStore()], $childCategoryStoreRelationStoreIds),
            'Category store relations are different from expecting value.',
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

        $updateCategoryStoreRelationRequestTransfer = (new UpdateCategoryStoreRelationRequestTransfer())
            ->setIdCategory($parentCategoryTransfer->getIdCategory())
            ->setNewStoreAssignment((new StoreRelationTransfer())
                ->addIdStores($deStoreTransfer->getIdStore()));

        // Act
        $this->getFacade()->updateCategoryStoreRelationWithMainChildrenPropagation($updateCategoryStoreRelationRequestTransfer);

        // Assert
        $parentCategoryStoreRelationStoreIds = $this->tester->getCategoryRelationStoreIds($parentCategoryTransfer->getIdCategory());
        $childCategoryStoreRelationStoreIds = $this->tester->getCategoryRelationStoreIds($childCategoryTransfer->getIdCategory());

        $this->assertCount(1, $parentCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');
        $this->assertCount(0, $childCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');

        $this->assertEmpty(
            array_diff([$deStoreTransfer->getIdStore()], $parentCategoryStoreRelationStoreIds),
            'Category store relations are different from expecting value.',
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

        $updateCategoryStoreRelationRequestTransfer = (new UpdateCategoryStoreRelationRequestTransfer())
            ->setIdCategory($childCategoryTransfer->getIdCategory())
            ->setNewStoreAssignment((new StoreRelationTransfer())
                ->addIdStores($deStoreTransfer->getIdStore())
                ->addIdStores($atStoreTransfer->getIdStore()));

        // Act
        $this->getFacade()->updateCategoryStoreRelationWithMainChildrenPropagation($updateCategoryStoreRelationRequestTransfer);

        // Assert
        $parentCategoryStoreRelationStoreIds = $this->tester->getCategoryRelationStoreIds($parentCategoryTransfer->getIdCategory());
        $childCategoryStoreRelationStoreIds = $this->tester->getCategoryRelationStoreIds($childCategoryTransfer->getIdCategory());

        $this->assertCount(1, $parentCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');
        $this->assertCount(1, $childCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');

        $this->assertEmpty(
            array_diff([$deStoreTransfer->getIdStore()], $parentCategoryStoreRelationStoreIds),
            'Category store relations are different from expecting value.',
        );
        $this->assertEmpty(
            array_diff([$deStoreTransfer->getIdStore()], $parentCategoryStoreRelationStoreIds),
            'Category store relations are different from expecting value.',
        );
    }

    /**
     * @return void
     */
    public function testUpdateCategoryStoreRelationExecutesPluginStack(): void
    {
        // Arrange
        $this->tester->setDependency(
            CategoryDependencyProvider::PLUGIN_CATEGORY_STORE_ASSIGNER,
            $this->getCategoryStoreAssignerPluginMock(),
        );

        // Act
        $this->getFacade()->updateCategoryStoreRelation(new UpdateCategoryStoreRelationRequestTransfer());
    }

    /**
     * @return void
     */
    public function testGetCategoryNodesWithFilterWillReturnCategoryNodesData(): void
    {
        // Arrange
        $expectedCount = 1;

        $storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE]);

        $parentCategoryTransfer = $this->tester->haveCategory();
        $this->tester->haveCategoryStoreRelation($parentCategoryTransfer->getIdCategory(), $storeTransfer->getIdStore());

        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNode(),
        ]);
        $this->tester->haveCategoryStoreRelation($childCategoryTransfer->getIdCategory(), $storeTransfer->getIdStore());

        $filterTransfer = (new FilterTransfer())
            ->setLimit($expectedCount)
            ->setOffset(0);

        $categoryNodeCriteriaTransfer = (new CategoryNodeCriteriaTransfer())
            ->setFilter($filterTransfer);

        // Act
        $nodeCollectionTransfer = $this->getFacade()->getCategoryNodes($categoryNodeCriteriaTransfer);

        // Assert
        $this->assertCount($expectedCount, $nodeCollectionTransfer->getNodes(), sprintf('Exactly %d category nodes should be found.', $expectedCount));
    }

    /**
     * @return void
     */
    public function testFindCategoryWillFilterCategoryAttributesByProvidedLocale(): void
    {
        // Arrange
        $localeTransferEn = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_EN]);
        $localeTransferDe = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_DE]);

        $parentCategoryLocalizedAttributesTransferEn = $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransferEn);
        $parentCategoryLocalizedAttributesTransferDE = $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransferDe);

        $parentCategoryTransfer = $this->tester->haveCategory(
            [
                CategoryTransfer::LOCALIZED_ATTRIBUTES => [
                    $parentCategoryLocalizedAttributesTransferEn->toArray(),
                    $parentCategoryLocalizedAttributesTransferDE->toArray(),
                ],
            ],
        );

        $childCategoryLocalizedAttributesTransferEn = $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransferEn);
        $childCategoryLocalizedAttributesTransferDe = $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransferDe);

        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNode(),
            CategoryTransfer::LOCALIZED_ATTRIBUTES => [
                $childCategoryLocalizedAttributesTransferEn->toArray(),
                $childCategoryLocalizedAttributesTransferDe->toArray(),
            ],
        ]);

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($parentCategoryTransfer->getIdCategory())
            ->setLocaleName($localeTransferEn->getLocaleName())
            ->setWithChildren(true);

        // Act
        $categoryTransfer = $this->getFacade()->findCategory($categoryCriteriaTransfer);

        // Assert
        $this->assertNotNull($categoryTransfer);
        $this->assertCount(1, $categoryTransfer->getNodeCollection()->getNodes());

        /** @var \Generated\Shared\Transfer\NodeTransfer $parentNodeTransfer */
        $parentNodeTransfer = $categoryTransfer->getNodeCollection()->getNodes()->offsetGet(0);
        $this->assertCount(1, $parentNodeTransfer->getCategory()->getLocalizedAttributes());

        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $parentCategoryLocalizedAttributesTransfer */
        $parentCategoryLocalizedAttributesTransfer = $parentNodeTransfer->getCategory()->getLocalizedAttributes()->offsetGet(0);
        $this->assertSame($localeTransferEn->getIdLocale(), $parentCategoryLocalizedAttributesTransfer->getLocale()->getIdLocale());
        $this->assertSame($parentCategoryLocalizedAttributesTransferEn->getName(), $parentCategoryLocalizedAttributesTransfer->getName());

        $this->assertCount(1, $parentNodeTransfer->getChildrenNodes()->getNodes());

        /** @var \Generated\Shared\Transfer\NodeTransfer $childNodeTransfer */
        $childNodeTransfer = $parentNodeTransfer->getChildrenNodes()->getNodes()->offsetGet(0);
        $this->assertCount(1, $childNodeTransfer->getCategory()->getLocalizedAttributes());

        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $childCategoryLocalizedAttributesTransfer */
        $childCategoryLocalizedAttributesTransfer = $childNodeTransfer->getCategory()->getLocalizedAttributes()->offsetGet(0);
        $this->assertSame($localeTransferEn->getIdLocale(), $childCategoryLocalizedAttributesTransfer->getLocale()->getIdLocale());
        $this->assertSame($childCategoryLocalizedAttributesTransferEn->getName(), $childCategoryLocalizedAttributesTransfer->getName());
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param array<int> $expectedLocaleIds
     * @param array<int> $expectedStoreIds
     *
     * @return void
     */
    protected function assertCategoryNodeWithRelations(
        NodeTransfer $categoryNodeTransfer,
        array $expectedLocaleIds,
        array $expectedStoreIds
    ): void {
        $storeRelationTransfer = $categoryNodeTransfer->getCategoryOrFail()->getStoreRelation();
        $this->assertNotNull($storeRelationTransfer, 'The category does not have store relations.');
        $this->assertEquals(count($expectedStoreIds), $storeRelationTransfer->getStores()->count(), 'The category node does have all store relations.');

        $resultStoreIds = $this->extractStoreIdsFromStoreRelation($storeRelationTransfer);
        $this->assertEmpty(array_diff($expectedStoreIds, $resultStoreIds), 'Returned stores ids are not equal to expected values.');

        $categoryLocalizedAttributes = $categoryNodeTransfer->getCategoryOrFail()->getLocalizedAttributes();
        $this->assertEquals(count($expectedLocaleIds), $categoryLocalizedAttributes->count(), 'The category node does have all localized attributes.');

        foreach ($categoryLocalizedAttributes as $localizedAttributeTransfer) {
            $this->assertTrue(
                in_array($localizedAttributeTransfer->getLocaleOrFail()->getIdLocaleOrFail(), $expectedLocaleIds),
                'The locale is missed in a list of expected values.',
            );
            $this->assertNotNull($localizedAttributeTransfer->getUrl(), 'The category localized attribute URL is empty.');
        }
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryStoreAssignerPluginInterface
     */
    protected function getCategoryStoreAssignerPluginMock(): CategoryStoreAssignerPluginInterface
    {
        $categoryStoreAssignerPluginMock = $this
            ->getMockBuilder(CategoryStoreAssignerPluginInterface::class)
            ->getMock();

        $categoryStoreAssignerPluginMock
            ->expects($this->once())
            ->method('handleStoreRelationUpdate');

        return $categoryStoreAssignerPluginMock;
    }

    /**
     * @param array<\Generated\Shared\Transfer\CategoryTransfer> $categoryTransfers
     *
     * @return array<int>
     */
    protected function extractCategoryNodeIdsFromCategoryCollection(array $categoryTransfers): array
    {
        $categoryNodeIds = [];
        foreach ($categoryTransfers as $categoryTransfer) {
            $categoryNodeIds[] = $categoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail();
        }

        return $categoryNodeIds;
    }

    /**
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     *
     * @return array<int>
     */
    protected function extractLocaleIdsFromLocales(array $localeTransfers): array
    {
        $localeIds = [];
        foreach ($localeTransfers as $localeTransfer) {
            $localeIds[] = $localeTransfer->getIdLocaleOrFail();
        }

        return $localeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return array<int>
     */
    protected function extractStoreIdsFromStoreRelation(StoreRelationTransfer $storeRelationTransfer): array
    {
        $storeIds = [];
        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeIds[] = $storeTransfer->getIdStore();
        }

        return $storeIds;
    }
}
