<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryDynamicEntityConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\DynamicEntityPostEditRequestBuilder;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\NodeTransfer;
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
 * @group UpdateCategoryClosureTableByDynamicEntityRequestTest
 * Add your own group annotations below this line
 */
class UpdateCategoryClosureTableByDynamicEntityRequestTest extends Unit
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
    public function testUpdatesCategoryClosureTable(): void
    {
        $initialParentCategoryTransfer = $this->tester->haveCategory();
        $newParentCategoryTransfer = $this->tester->haveCategory();
        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $initialParentCategoryTransfer->getCategoryNodeOrFail()->toArray(),
        ]);

        $childNodeTransfer = $childCategoryTransfer->getCategoryNodeOrFail();
        $initialCategoryClosureTableEntity = $this->tester->findCategoryClosureTableEntity(
            $initialParentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
            $childNodeTransfer->getIdCategoryNodeOrFail(),
        );

        $childNodeTransfer->setFkParentCategoryNode($newParentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail());
        $this->tester->updateCategoryNode($childNodeTransfer);

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => static::TABLE_NAME_CATEGORY_NODE,
        ]))->withRawDynamicEntity([
            RawDynamicEntityTransfer::FIELDS => $childNodeTransfer->toArray(),
        ])->build();

        // Act
        $dynamicEntityPostEditResponseTransfer = $this->tester->getFacade()->updateCategoryClosureTableByDynamicEntityRequest(
            $dynamicEntityPostEditRequestTransfer,
        );

        // Assert
        $this->assertCount(0, $dynamicEntityPostEditResponseTransfer->getErrors());
        $this->assertNotNull($initialCategoryClosureTableEntity);
        $this->assertNull($this->tester->findCategoryClosureTableEntity(
            $initialParentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
            $childNodeTransfer->getIdCategoryNodeOrFail(),
        ));
        $this->assertNotNull($this->tester->findCategoryClosureTableEntity(
            $newParentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
            $childNodeTransfer->getIdCategoryNodeOrFail(),
        ));
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenFkParentCategoryNodeIsNotProvided(): void
    {
        // Arrange
        $initialParentCategoryTransfer = $this->tester->haveCategory();
        $newParentCategoryTransfer = $this->tester->haveCategory();
        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $initialParentCategoryTransfer->getCategoryNodeOrFail()->toArray(),
        ]);

        $childNodeTransfer = $childCategoryTransfer->getCategoryNodeOrFail();
        $childNodeTransfer->setFkParentCategoryNode($newParentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail());
        $this->tester->updateCategoryNode($childNodeTransfer);

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => 'spy_table_name',
        ]))->withRawDynamicEntity([
            RawDynamicEntityTransfer::FIELDS => [
                NodeTransfer::ID_CATEGORY_NODE => $childNodeTransfer->getIdCategoryNodeOrFail(),
            ],
        ])->build();

        // Act
        $dynamicEntityPostEditResponseTransfer = $this->tester->getFacade()
            ->updateCategoryClosureTableByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);

        // Assert
        $this->assertCount(0, $dynamicEntityPostEditResponseTransfer->getErrors());
        $this->assertNotNull($this->tester->findCategoryClosureTableEntity(
            $initialParentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
            $childNodeTransfer->getIdCategoryNodeOrFail(),
        ));
        $this->assertNull($this->tester->findCategoryClosureTableEntity(
            $newParentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
            $childNodeTransfer->getIdCategoryNodeOrFail(),
        ));
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenNotApplicableTableNameIsProvided(): void
    {
        // Arrange
        $initialParentCategoryTransfer = $this->tester->haveCategory();
        $newParentCategoryTransfer = $this->tester->haveCategory();
        $childCategoryTransfer = $this->tester->haveCategory([
            CategoryTransfer::PARENT_CATEGORY_NODE => $initialParentCategoryTransfer->getCategoryNodeOrFail()->toArray(),
        ]);

        $childNodeTransfer = $childCategoryTransfer->getCategoryNodeOrFail();
        $childNodeTransfer->setFkParentCategoryNode($newParentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail());
        $this->tester->updateCategoryNode($childNodeTransfer);

        $dynamicEntityPostEditRequestTransfer = (new DynamicEntityPostEditRequestBuilder([
            DynamicEntityPostEditRequestTransfer::TABLE_NAME => 'spy_table_name',
        ]))->withRawDynamicEntity([
            RawDynamicEntityTransfer::FIELDS => $childNodeTransfer->toArray(),
        ])->build();

        // Act
        $dynamicEntityPostEditResponseTransfer = $this->tester->getFacade()
            ->updateCategoryClosureTableByDynamicEntityRequest($dynamicEntityPostEditRequestTransfer);

        // Assert
        $this->assertCount(0, $dynamicEntityPostEditResponseTransfer->getErrors());
        $this->assertNotNull($this->tester->findCategoryClosureTableEntity(
            $initialParentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
            $childNodeTransfer->getIdCategoryNodeOrFail(),
        ));
        $this->assertNull($this->tester->findCategoryClosureTableEntity(
            $newParentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
            $childNodeTransfer->getIdCategoryNodeOrFail(),
        ));
    }
}
