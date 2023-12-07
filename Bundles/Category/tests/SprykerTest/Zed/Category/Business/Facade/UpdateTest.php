<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Category\Business\Creator\CategoryUrlCreatorInterface;
use Spryker\Zed\Category\CategoryConfig;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Communication\Plugin\Category\MainChildrenPropagationCategoryStoreAssignerPlugin;
use Spryker\Zed\Category\Communication\Plugin\CategoryUrlPathPrefixUpdaterPlugin;
use Spryker\Zed\Category\Persistence\CategoryEntityManager;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Category\Persistence\CategoryPersistenceFactory;
use SprykerTest\Zed\Category\CategoryBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Facade
 * @group UpdateTest
 * Add your own group annotations below this line
 */
class UpdateTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_LOCALE_EN = 'en_US';

    /**
     * @var string
     */
    protected const TEST_STORE_DE = 'DE';

    /**
     * @var string
     */
    protected const TEST_STORE_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected CategoryBusinessTester $tester;

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
    public function testUpdateCategoryWhenParentCategoryIsChangedWillRemoveStoreRelationsMissingForParentCategory(): void
    {
        // Arrange
        $deStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_DE], false);
        $atStoreTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::TEST_STORE_AT], false);

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
        $childCategoryTransfer = $this->tester->getFacade()->findCategory($categoryCriteriaTransfer);

        $childCategoryTransfer->setParentCategoryNode($secondParentCategoryTransfer->getCategoryNode());

        // Act
        $this->tester->getFacade()->update($childCategoryTransfer);

        // Assert
        $childCategoryStoreRelationStoreIds = $this->tester->getCategoryRelationStoreIds($childCategoryTransfer->getIdCategory());
        $this->assertCount(0, $childCategoryStoreRelationStoreIds, 'Number of category store relations does not equals to expected value.');
    }

    /**
     * @return void
     */
    public function testUpdateShouldUpdateCategoryWhenParentCategoryAndAdditionalParentSwitched(): void
    {
        // Arrange
        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_EN]);

        $parentCategoryNodeTransfer = $this->tester->haveCategory([
            CategoryTransfer::LOCALIZED_ATTRIBUTES => [
                $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransfer)->toArray(),
            ],
        ])->getCategoryNodeOrFail();

        $extraParentCategoryNodeTransfer = $this->tester->haveCategory([
            CategoryTransfer::LOCALIZED_ATTRIBUTES => [
                $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransfer)->toArray(),
            ],
        ])->getCategoryNodeOrFail();

        $categoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $parentCategoryNodeTransfer,
            CategoryTransfer::EXTRA_PARENTS => [
                $extraParentCategoryNodeTransfer->toArray(),
            ],
            CategoryTransfer::LOCALIZED_ATTRIBUTES => [
                $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransfer)->toArray(),
            ],
        ]);

        $categoryTransfer->setParentCategoryNode($extraParentCategoryNodeTransfer);
        $categoryTransfer->setExtraParents(new ArrayObject([$parentCategoryNodeTransfer]));
        $this->tester->getFacade()->update($categoryTransfer);

        $categoryTransfer->setParentCategoryNode($parentCategoryNodeTransfer);
        $categoryTransfer->setExtraParents(new ArrayObject([$extraParentCategoryNodeTransfer]));

        // Act
        $this->tester->getFacade()->update($categoryTransfer);

        // Assert
        $this->assertSame($parentCategoryNodeTransfer->getIdCategoryNodeOrFail(), $categoryTransfer->getCategoryNodeOrFail()->getFkParentCategoryNodeOrFail());
        $this->assertCount(1, $categoryTransfer->getExtraParents());

        /** @var \Generated\Shared\Transfer\NodeTransfer $updatedExtraParentCategoryNodeTransfer */
        $updatedExtraParentCategoryNodeTransfer = $categoryTransfer->getExtraParents()->offsetGet(0);
        $this->assertSame($extraParentCategoryNodeTransfer->getIdCategoryNodeOrFail(), $updatedExtraParentCategoryNodeTransfer->getIdCategoryNodeOrFail());
    }

    /**
     * @return void
     */
    public function testUpdateCategoryWithNewLocalizedAttributeWillGenerateUrlForNewProvidedLocale(): void
    {
        // Arrange
        $this->tester->setDependency(CategoryDependencyProvider::PLUGINS_CATEGORY_URL_PATH, [
            new CategoryUrlPathPrefixUpdaterPlugin(),
        ]);

        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_EN]);
        $categoryLocalizedAttributes = $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransfer);

        $categoryTransfer = $this->tester->haveCategory();
        $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributes);

        // Act
        $this->tester->getFacade()->update($categoryTransfer);

        // Assert
        $this->assertNotNull(
            $this->tester->findUrlCategoryNodeAndLocale($categoryTransfer->getCategoryNodeOrFail(), $localeTransfer),
            'Category URL should be successfully created.',
        );
    }

    /**
     * @return void
     */
    public function testUpdateCategoryWithChildNodesWithNewLocalizedAttributeWillGenerateUrlForNewProvidedLocale(): void
    {
        // Arrange
        $this->tester->setDependency(CategoryDependencyProvider::PLUGINS_CATEGORY_URL_PATH, [
            new CategoryUrlPathPrefixUpdaterPlugin(),
        ]);

        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::TEST_LOCALE_EN]);
        $categoryLocalizedAttributes = $this->tester->createCategoryLocalizedAttributesTransferForLocale($localeTransfer);

        $categoryTransfer = $this->tester->haveCategory();
        $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributes);
        $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer->getCategoryNode(),
        ]);

        // Act
        $this->tester->getFacade()->update($categoryTransfer);

        // Assert
        $this->assertNotNull(
            $this->tester->findUrlCategoryNodeAndLocale($categoryTransfer->getCategoryNodeOrFail(), $localeTransfer),
            'Category URL should be successfully created.',
        );
    }

    /**
     * @dataProvider getUpdateCategoryWithExistingLocalizedAttributesDataProvider
     *
     * @param bool $withChildNodes
     *
     * @return void
     */
    public function testUpdateCategoryWithExistingLocalizedAttributes(bool $withChildNodes): void
    {
        // Arrange
        $this->tester->setDependency(CategoryDependencyProvider::PLUGINS_CATEGORY_URL_PATH, [
            new CategoryUrlPathPrefixUpdaterPlugin(),
        ]);

        $categoryTransfer = $this->tester->haveLocalizedCategory();

        if ($withChildNodes) {
            $this->tester->haveLocalizedCategory([
                CategoryTransfer::PARENT_CATEGORY_NODE => $categoryTransfer->getCategoryNode(),
            ]);
        }

        $categoryUrlCreatorMock = $this->createCategoryUrlCreatorMock();
        $this->tester->mockFactoryMethod('createCategoryUrlCreator', $categoryUrlCreatorMock);

        // Assert
        $categoryUrlCreatorMock->expects($this->never())->method('createLocalizedCategoryUrlsForNode');

        // Act
        $this->tester->getFacade()->update($categoryTransfer);
    }

    /**
     * @dataProvider getUpdateCategoryWithParentCategoryClosureTableDataProvider
     *
     * @param bool $isCategoryClosureTableEventsEnabled
     *
     * @return void
     */
    public function testUpdateCategoryWhenParentCategoryIsChanged(bool $isCategoryClosureTableEventsEnabled): void
    {
        // Arrange
        $firstParentCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $this->tester->haveCategory()->getCategoryNode(),
        ]);
        $secondParentCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $this->tester->haveCategory()->getCategoryNode(),
        ]);
        $firstChildCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $firstParentCategoryTransfer->getCategoryNode(),
        ]);
        $secondChildCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $firstChildCategoryTransfer->getCategoryNode(),
        ]);

        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($firstChildCategoryTransfer->getIdCategory())
            ->setWithChildrenRecursively(true);
        $firstChildCategoryTransfer = $this->tester->getFacade()->findCategory($categoryCriteriaTransfer);

        $firstChildCategoryTransfer->setParentCategoryNode($secondParentCategoryTransfer->getCategoryNode());

        /** @var \Spryker\Zed\Category\CategoryConfig $categoryConfigMock */
        $categoryConfigMock = $this->tester->mockConfigMethod('isCategoryClosureTableEventsEnabled', $isCategoryClosureTableEventsEnabled);
        $this->tester->mockFactoryMethod('getEntityManager', $this->createCategoryEntityManager($categoryConfigMock));

        // Act
        $this->tester->getFacade()->update($firstChildCategoryTransfer);

        // Assert
        $secondParentIdCategoryNode = $secondParentCategoryTransfer->getCategoryNode()->getIdCategoryNode();
        $firstChildIdCategoryNode = $firstChildCategoryTransfer->getCategoryNode()->getIdCategoryNode();
        $secondChildIdCategoryNode = $secondChildCategoryTransfer->getCategoryNode()->getIdCategoryNode();

        $this->assertSame(0, $this->tester->findCategoryClosureTableDepth($firstChildIdCategoryNode, $firstChildIdCategoryNode));
        $this->assertSame(0, $this->tester->findCategoryClosureTableDepth($secondChildIdCategoryNode, $secondChildIdCategoryNode));
        $this->assertSame(1, $this->tester->findCategoryClosureTableDepth($secondParentIdCategoryNode, $firstChildIdCategoryNode));
        $this->assertSame(1, $this->tester->findCategoryClosureTableDepth($firstChildIdCategoryNode, $secondChildIdCategoryNode));
        $this->assertSame(2, $this->tester->findCategoryClosureTableDepth($secondParentIdCategoryNode, $secondChildIdCategoryNode));
    }

    /**
     * @param \Spryker\Zed\Category\CategoryConfig $categoryConfig
     *
     * @return \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected function createCategoryEntityManager(CategoryConfig $categoryConfig): CategoryEntityManagerInterface
    {
        $categoryPersistenceFactory = new CategoryPersistenceFactory();
        $categoryPersistenceFactory->setConfig($categoryConfig);

        $categoryEntityManager = new CategoryEntityManager();
        $categoryEntityManager->setFactory($categoryPersistenceFactory);

        return $categoryEntityManager;
    }

    /**
     * @return \Spryker\Zed\Category\Business\Creator\CategoryUrlCreatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createCategoryUrlCreatorMock(): CategoryUrlCreatorInterface
    {
        return $this->getMockBuilder(CategoryUrlCreatorInterface::class)->getMock();
    }

    /**
     * @return array<string, list<bool>>
     */
    protected function getUpdateCategoryWithParentCategoryClosureTableDataProvider(): array
    {
        return [
            'Should properly update category closure table when `isCategoryClosureTableEventsEnabled` configuration is enabled.' => [true],
            'Should properly update category closure table when `isCategoryClosureTableEventsEnabled` configuration is disabled.' => [false],
        ];
    }

    /**
     * @return array<string, list<bool>>
     */
    protected function getUpdateCategoryWithExistingLocalizedAttributesDataProvider(): array
    {
        return [
            'Should not generate URL when category has no child nodes.' => [false],
            'Should not generate URL when category has child nodes.' => [true],
        ];
    }
}
