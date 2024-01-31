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
use SprykerTest\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryDynamicEntityConnector
 * @group Business
 * @group Facade
 * @group CreateCategoryClosureTableByDynamicEntityRequestTest
 * Add your own group annotations below this line
 */
class CreateCategoryClosureTableByDynamicEntityRequestTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Category\CategoryConfig::TABLE_NAME_CATEGORY_NODE
     *
     * @var string
     */
    protected const TABLE_NAME_CATEGORY_NODE = 'spy_category_node';

    /**
     * @var \SprykerTest\Zed\CategoryDynamicEntityConnector\CategoryDynamicEntityConnectorBusinessTester
     */
    protected CategoryDynamicEntityConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreatesCategoryClosureTableEntity(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();
        $categoryNodeTransfer = $this->tester->haveCategoryNodeForCategory($categoryTransfer->getIdCategory());

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => static::TABLE_NAME_CATEGORY_NODE,
        ]))->withRawDynamicEntity([
            RawDynamicEntityTransfer::FIELDS => $categoryNodeTransfer->toArray(),
        ])->build();

        // Act
        $dynamicEntityPostEditResponseTransfer = $this->tester->getFacade()
            ->createCategoryClosureTableByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);

        // Assert
        $this->assertCount(0, $dynamicEntityPostEditResponseTransfer->getErrors());
        $this->assertNotNull($this->tester->findCategoryClosureTableEntity($categoryNodeTransfer->getIdCategoryNodeOrFail()));
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNotApplicableTableNameIsProvided(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategory();
        $categoryNodeTransfer = $this->tester->haveCategoryNodeForCategory($categoryTransfer->getIdCategory());

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => 'spy_table_name',
        ]))->withRawDynamicEntity([
            RawDynamicEntityTransfer::FIELDS => $categoryNodeTransfer->toArray(),
        ])->build();

        // Act
        $dynamicEntityPostEditResponseTransfer = $this->tester->getFacade()
            ->createCategoryClosureTableByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);

        // Assert
        $this->assertCount(0, $dynamicEntityPostEditResponseTransfer->getErrors());
        $this->assertNull($this->tester->findCategoryClosureTableEntity($categoryNodeTransfer->getIdCategoryNodeOrFail()));
    }
}
