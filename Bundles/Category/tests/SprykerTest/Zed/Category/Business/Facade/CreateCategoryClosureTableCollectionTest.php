<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CategoryClosureTableCollectionRequestBuilder;
use Generated\Shared\DataBuilder\NodeBuilder;
use Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\Category\CategoryBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Facade
 * @group CreateCategoryClosureTableCollectionTest
 * Add your own group annotations below this line
 */
class CreateCategoryClosureTableCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Category\Business\Validator\Rule\AbstractCategoryNodeExistsValidationRule::GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND = 'category.validation.category_node_entity_not_found';

    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected CategoryBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreatesCategoryClosureTableEntity(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryWithoutCategoryNode();
        $nodeTransfer = $this->tester->haveCategoryNodeForCategory($categoryTransfer->getIdCategoryOrFail());

        $categoryClosureTableCollectionRequestTransfer = (new CategoryClosureTableCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addCategoryNode($nodeTransfer);

        // Act
        $categoryClosureTableCollectionResponseTransfer = $this->tester->getFacade()
            ->createCategoryClosureTableCollection($categoryClosureTableCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $categoryClosureTableCollectionResponseTransfer->getErrors());

        $categoryClosureTableEntity = $this->tester->findCategoryClosureTableEntity(
            $nodeTransfer->getIdCategoryNodeOrFail(),
            $nodeTransfer->getIdCategoryNodeOrFail(),
        );
        $this->assertNotNull($categoryClosureTableEntity);
        $this->assertSame(0, $categoryClosureTableEntity->getDepth());
    }

    /**
     * @return void
     */
    public function testCreatesCategoryClosureTableEntityForParentNode(): void
    {
        // Arrange
        $parentCategoryTransfer = $this->tester->haveCategory();
        $categoryTransfer = $this->tester->haveCategoryWithoutCategoryNode();
        $nodeTransfer = $this->tester->haveCategoryNodeForCategory($categoryTransfer->getIdCategoryOrFail(), [
            NodeTransfer::FK_PARENT_CATEGORY_NODE => $parentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
        ]);

        $categoryClosureTableCollectionRequestTransfer = (new CategoryClosureTableCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addCategoryNode($nodeTransfer);

        // Act
        $categoryClosureTableCollectionResponseTransfer = $this->tester->getFacade()
            ->createCategoryClosureTableCollection($categoryClosureTableCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $categoryClosureTableCollectionResponseTransfer->getErrors());

        $categoryClosureTableEntity = $this->tester->findCategoryClosureTableEntity(
            $parentCategoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
            $nodeTransfer->getIdCategoryNodeOrFail(),
        );
        $this->assertNotNull($categoryClosureTableEntity);
        $this->assertSame(1, $categoryClosureTableEntity->getDepth());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCategoryNodeDoesNotExist(): void
    {
        // Arrange
        $this->tester->haveCategoryWithoutCategoryNode();
        $nodeTransfer = (new NodeBuilder([NodeTransfer::ID_CATEGORY_NODE => 0]))->build();

        $categoryClosureTableCollectionRequestTransfer = (new CategoryClosureTableCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addCategoryNode($nodeTransfer);

        // Act
        $categoryClosureTableCollectionResponseTransfer = $this->tester->getFacade()
            ->createCategoryClosureTableCollection($categoryClosureTableCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $categoryClosureTableCollectionResponseTransfer->getErrors());

        $errorTransfer = $categoryClosureTableCollectionResponseTransfer->getErrors()->getIterator()->current();
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND, $errorTransfer->getMessage());
        $this->assertSame('0', $errorTransfer->getEntityIdentifier());
        $this->assertNull($this->tester->findCategoryClosureTableEntity($nodeTransfer->getIdCategoryNodeOrFail()));
    }

    /**
     * @dataProvider throwsExceptionWhenRequiredTransferPropertyIsMissingDataProvider
     *
     * @param \Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
     *
     * @return void
     */
    public function testThrowsExceptionWhenRequiredTransferPropertyIsMissing(
        CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
    ): void {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createCategoryClosureTableCollection($categoryClosureTableCollectionRequestTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer>>
     */
    protected function throwsExceptionWhenRequiredTransferPropertyIsMissingDataProvider(): array
    {
        return [
            'CategoryClosureTableCollectionRequestTransfer.isTransactional property is not set' => [
                (new CategoryClosureTableCollectionRequestBuilder([
                    CategoryClosureTableCollectionRequestTransfer::IS_TRANSACTIONAL => null,
                ]))->build(),
            ],
            'CategoryClosureTableCollectionRequestTransfer.categoryNodes property is not set' => [
                (new CategoryClosureTableCollectionRequestBuilder([
                    CategoryClosureTableCollectionRequestTransfer::IS_TRANSACTIONAL => true,
                    CategoryClosureTableCollectionRequestTransfer::CATEGORY_NODES => [],
                ]))->build(),
            ],
            'CategoryClosureTableCollectionRequestTransfer.categoryNodes.idCategoryNode property is not set' => [
                (new CategoryClosureTableCollectionRequestBuilder([
                    CategoryClosureTableCollectionRequestTransfer::IS_TRANSACTIONAL => true,
                    CategoryClosureTableCollectionRequestTransfer::CATEGORY_NODES => [
                        [NodeTransfer::ID_CATEGORY_NODE => null],
                    ],
                ]))->build(),
            ],
        ];
    }
}
