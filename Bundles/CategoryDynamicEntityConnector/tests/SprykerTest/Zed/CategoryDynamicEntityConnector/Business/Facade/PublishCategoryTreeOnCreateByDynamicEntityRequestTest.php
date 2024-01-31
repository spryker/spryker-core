<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryDynamicEntityConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\DynamicEntityPostEditRequestBuilder;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Spryker\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorDependencyProvider;
use Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToEventFacadeInterface;
use SprykerTest\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryDynamicEntityConnector
 * @group Business
 * @group Facade
 * @group PublishCategoryTreeOnCreateByDynamicEntityRequestTest
 * Add your own group annotations below this line
 */
class PublishCategoryTreeOnCreateByDynamicEntityRequestTest extends Unit
{
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
    public function testTriggersPublishEvent(string $tableName): void
    {
        // Arrange
        $categoryDynamicEntityConnectorToEventFacadeBridgeMock = $this->creteCategoryDynamicEntityConnectorToEventFacadeBridgeMock();
        $categoryDynamicEntityConnectorToEventFacadeBridgeMock->expects($this->once())->method('trigger');
        $this->tester->setDependency(
            CategoryDynamicEntityConnectorDependencyProvider::FACADE_EVENT,
            $categoryDynamicEntityConnectorToEventFacadeBridgeMock,
        );

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => $tableName,
        ]))->build();

        // Act
        $this->tester->getFacade()
            ->publishCategoryTreeOnCreateByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNotApplicableTableNameIsProvided(): void
    {
        // Arrange
        $categoryDynamicEntityConnectorToEventFacadeBridgeMock = $this->creteCategoryDynamicEntityConnectorToEventFacadeBridgeMock();
        $categoryDynamicEntityConnectorToEventFacadeBridgeMock->expects($this->never())->method('trigger');
        $this->tester->setDependency(
            CategoryDynamicEntityConnectorDependencyProvider::FACADE_EVENT,
            $categoryDynamicEntityConnectorToEventFacadeBridgeMock,
        );

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => 'spy_table_name',
        ]))->build();

        // Act
        $this->tester->getFacade()
            ->publishCategoryTreeOnCreateByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CategoryDynamicEntityConnector\Dependency\Facade\CategoryDynamicEntityConnectorToEventFacadeInterface
     */
    protected function creteCategoryDynamicEntityConnectorToEventFacadeBridgeMock(): CategoryDynamicEntityConnectorToEventFacadeInterface
    {
        return $this->getMockBuilder(CategoryDynamicEntityConnectorToEventFacadeInterface::class)
            ->getMock();
    }
}
