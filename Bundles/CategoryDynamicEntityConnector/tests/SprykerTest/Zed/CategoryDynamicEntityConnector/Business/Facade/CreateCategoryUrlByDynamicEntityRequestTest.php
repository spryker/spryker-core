<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryDynamicEntityConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\DynamicEntityPostEditRequestBuilder;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\RawDynamicEntityTransfer;
use Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorDependencyProvider;
use Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeBridge;
use Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface;
use SprykerTest\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryDynamicEntityConnector
 * @group Business
 * @group Facade
 * @group CreateCategoryUrlByDynamicEntityRequestTest
 * Add your own group annotations below this line
 */
class CreateCategoryUrlByDynamicEntityRequestTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE_EN = 'en_EN';

    /**
     * @uses \Spryker\Zed\Category\CategoryConfig::TABLE_NAME_CATEGORY_NODE
     *
     * @var string
     */
    protected const TABLE_NAME_CATEGORY_NODE = 'spy_category_node';

    /**
     * @uses \Spryker\Zed\Category\CategoryConfig::TABLE_NAME_CATEGORY_ATTRIBUTE
     *
     * @var string
     */
    protected const TABLE_NAME_CATEGORY_ATTRIBUTE = 'spy_category_attribute';

    /**
     * @uses \Spryker\Zed\Category\Business\Updater\CategoryUrlUpdater::FIELD_FK_CATEGORY
     *
     * @var string
     */
    protected const FIELD_FK_CATEGORY = 'fk_category';

    /**
     * @var \SprykerTest\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorBusinessTester
     */
    protected CategoryDynamicEntityConnectorBusinessTester $tester;

    /**
     * @dataProvider tableNameDataProvider
     *
     * @param string $tableName
     *
     * @return void
     */
    public function testCreatesUrlWhenCategoryHaveNodeAndLocalizedAttribute(string $tableName): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryWithoutCategoryNode();
        $nodeTransfer = $this->tester->haveCategoryNodeForCategory($categoryTransfer->getIdCategoryOrFail());
        $this->tester->haveCategoryClosureTableForCategoryNode($nodeTransfer);

        $localeTransfer = $this->tester->haveLocale();
        $this->tester->createCategoryLocalizedAttributesForLocale(
            $localeTransfer,
            $categoryTransfer->getIdCategoryOrFail(),
        );

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => $tableName,
        ]))->withRawDynamicEntity([
            RawDynamicEntityTransfer::FIELDS => [
                static::FIELD_FK_CATEGORY => $categoryTransfer->getIdCategoryOrFail(),
            ],
        ])->build();

        // Act
        $dynamicEntityPostEditResponseTransfer = $this->tester->getFacade()->createCategoryUrlByDynamicEntityRequest(
            $dynamicEntityPostEditRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $dynamicEntityPostEditResponseTransfer->getErrors());
        $this->assertNotNull($this->tester->findUrlCategoryEntityByNodeAndLocale($nodeTransfer, $localeTransfer));
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenCategoryDontHaveNode(): void
    {
        // Arrange
        $this->tester->setDependency(
            CategoryDynamicEntityConnectorDependencyProvider::FACADE_CATEGORY,
            $this->createCategoryDynamicEntityConnectorToCategoryFacadeBridgeMock(),
        );

        $categoryTransfer = $this->tester->haveCategoryWithoutCategoryNode();

        $localeTransfer = $this->tester->haveLocale();
        $this->tester->createCategoryLocalizedAttributesForLocale(
            $localeTransfer,
            $categoryTransfer->getIdCategoryOrFail(),
        );

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => static::TABLE_NAME_CATEGORY_ATTRIBUTE,
        ]))->withRawDynamicEntity([
            RawDynamicEntityTransfer::FIELDS => [
                static::FIELD_FK_CATEGORY => $categoryTransfer->getIdCategoryOrFail(),
            ],
        ])->build();

        // Act
        $dynamicEntityPostEditResponseTransfer = $this->tester->getFacade()->createCategoryUrlByDynamicEntityRequest(
            $dynamicEntityPostEditRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $dynamicEntityPostEditResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenCategoryDontHaveLocalizedAttribute(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryWithoutCategoryNode();
        $categoryNodeTransfer = $this->tester->haveCategoryNodeForCategory($categoryTransfer->getIdCategoryOrFail());
        $localeTransfer = $this->tester->haveLocale();

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => static::TABLE_NAME_CATEGORY_NODE,
        ]))->withRawDynamicEntity([
            RawDynamicEntityTransfer::FIELDS => [
                static::FIELD_FK_CATEGORY => $categoryTransfer->getIdCategoryOrFail(),
            ],
        ])->build();

        // Act
        $dynamicEntityPostEditResponseTransfer = $this->tester->getFacade()->createCategoryUrlByDynamicEntityRequest(
            $dynamicEntityPostEditRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $dynamicEntityPostEditResponseTransfer->getErrors());
        $this->assertNull($this->tester->findUrlCategoryEntityByNodeAndLocale($categoryNodeTransfer, $localeTransfer));
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNotApplicableTableNameIsProvided(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryWithoutCategoryNode();
        $categoryNodeTransfer = $this->tester->haveCategoryNodeForCategory($categoryTransfer->getIdCategoryOrFail());

        $localeTransfer = $this->tester->haveLocale();
        $this->tester->createCategoryLocalizedAttributesForLocale(
            $localeTransfer,
            $categoryTransfer->getIdCategoryOrFail(),
        );

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => 'spy_table_name',
        ]))->withRawDynamicEntity([
            RawDynamicEntityTransfer::FIELDS => [
                static::FIELD_FK_CATEGORY => $categoryTransfer->getIdCategoryOrFail(),
            ],
        ])->build();

        // Act
        $dynamicEntityPostEditResponseTransfer = $this->tester->getFacade()->createCategoryUrlByDynamicEntityRequest(
            $dynamicEntityPostEditRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $dynamicEntityPostEditResponseTransfer->getErrors());
        $this->assertNull($this->tester->findUrlCategoryEntityByNodeAndLocale($categoryNodeTransfer, $localeTransfer));
    }

    /**
     * @return array<string, list<string>>
     */
    protected function tableNameDataProvider(): array
    {
        return [
            'Table `spy_category_node`' => [static::TABLE_NAME_CATEGORY_NODE],
            'Table `spy_category_attribute`' => [static::TABLE_NAME_CATEGORY_ATTRIBUTE],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToCategoryFacadeInterface
     */
    protected function createCategoryDynamicEntityConnectorToCategoryFacadeBridgeMock(): CategoryDynamicEntityConnectorToCategoryFacadeInterface
    {
        $categoryDynamicEntityConnectorToCategoryFacadeBridge = $this->getMockBuilder(CategoryDynamicEntityConnectorToCategoryFacadeBridge::class)
            ->onlyMethods(['createCategoryUrlCollection'])
            ->enableOriginalConstructor()
            ->setConstructorArgs([
                $this->tester->getLocator()->category()->facade(),
            ])
            ->getMock();

        $categoryDynamicEntityConnectorToCategoryFacadeBridge->expects($this->never())
            ->method('createCategoryUrlCollection');

        return $categoryDynamicEntityConnectorToCategoryFacadeBridge;
    }
}
