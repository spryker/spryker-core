<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Category\CategoryDependencyProvider;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;
use SprykerTest\Zed\Category\CategoryBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Facade
 * @group ReorderCategoryNodeCollectionTest
 * Add your own group annotations below this line
 */
class ReorderCategoryNodeCollectionTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_ID_CATEGORY_NODE = 888;

    /**
     * @var int
     */
    protected const TEST_NODE_ORDER = 111;

    /**
     * @var int
     */
    protected const TEST_NODE_ORDER_2 = 222;

    /**
     * @var int
     */
    protected const TEST_NODE_ORDER_3 = 333;

    /**
     * @uses \Spryker\Zed\Category\Business\Validator\Rule\CategoryNode\CategoryNodeExistsCategoryNodeValidationRule::GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND = 'category.validation.category_node_entity_not_found';

    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected CategoryBusinessTester $tester;

    /**
     * @dataProvider getRequiredFieldsDataProvider
     *
     * @param \Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer $categoryNodeCollectionRequestTransfer
     *
     * @return void
     */
    public function testShouldThrowAnExceptionWhenRequiredFieldsAreNotProvided(
        CategoryNodeCollectionRequestTransfer $categoryNodeCollectionRequestTransfer
    ): void {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->reorderCategoryNodeCollection($categoryNodeCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testValidatesCategoryNodeExistence(): void
    {
        // Arrange
        $categoryNodeTransfer = $this->tester->haveCategory()->getCategoryNodeOrFail();
        $notExistingCategoryNodeTransfer = (new NodeTransfer())->setIdCategoryNode(static::TEST_ID_CATEGORY_NODE);

        $categoryNodeCollectionRequestTransfer = (new CategoryNodeCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setCategoryNodes(new ArrayObject([
                $categoryNodeTransfer,
                $notExistingCategoryNodeTransfer,
            ]));

        // Act
        $categoryNodeCollectionResponseTransfer = $this->tester->getFacade()
            ->reorderCategoryNodeCollection($categoryNodeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $categoryNodeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        $errorTransfer = $categoryNodeCollectionResponseTransfer->getErrors()->getIterator()->current();

        $this->assertSame('1', $errorTransfer->getEntityIdentifierOrFail());
        $this->assertSame(static::GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND, $errorTransfer->getMessageOrFail());
    }

    /**
     * @dataProvider getIsTransactionalDataProvider
     *
     * @param bool $isTransactional
     * @param int $expectedNodeOrder
     *
     * @return void
     */
    public function testIsTransactional(bool $isTransactional, int $expectedNodeOrder): void
    {
        // Arrange
        $categoryNodeTransfer = $this->tester->haveCategory([
            CategoryTransfer::CATEGORY_NODE => [NodeTransfer::NODE_ORDER => static::TEST_NODE_ORDER],
        ])->getCategoryNodeOrFail();

        $categoryNodeCollectionRequestTransfer = (new CategoryNodeCollectionRequestTransfer())
            ->setIsTransactional($isTransactional)
            ->setCategoryNodes(new ArrayObject([
                $categoryNodeTransfer,
                (new NodeTransfer())->setIdCategoryNode(static::TEST_ID_CATEGORY_NODE),
            ]));

        // Act
        $categoryNodeCollectionResponseTransfer = $this->tester->getFacade()
            ->reorderCategoryNodeCollection($categoryNodeCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $categoryNodeCollectionResponseTransfer->getErrors());

        /** @var \Generated\Shared\Transfer\NodeTransfer $resultCategoryNodeTransfer */
        $resultCategoryNodeTransfer = $categoryNodeCollectionResponseTransfer->getCategoryNodes()->getIterator()->current();
        $this->assertSame($expectedNodeOrder, $resultCategoryNodeTransfer->getNodeOrderOrFail());
    }

    /**
     * @dataProvider getTriggerCategoryTreePublishEventDataProvider
     *
     * @param bool $shouldTriggerEvent
     * @param int $nodeOrder
     *
     * @return void
     */
    public function testTriggerCategoryTreePublishEvent(bool $shouldTriggerEvent, int $nodeOrder): void
    {
        // Arrange
        $categoryNodeTransfer = $this->tester->haveCategory([
            CategoryTransfer::CATEGORY_NODE => [NodeTransfer::NODE_ORDER => $nodeOrder]])->getCategoryNodeOrFail();

        $categoryNodeCollectionRequestTransfer = (new CategoryNodeCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->addCategoryNode($categoryNodeTransfer);

        $eventFacadeMock = $this->createEventFacadeMock();
        $this->tester->setDependency(CategoryDependencyProvider::FACADE_EVENT, $eventFacadeMock);

        // Assert
        $eventFacadeMock->expects($shouldTriggerEvent ? $this->once() : $this->never())->method('trigger');

        // Act
        $this->tester->getFacade()->reorderCategoryNodeCollection($categoryNodeCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldSetCorrectNodeOrderWhenRequestHasNotValidCategoryNodes(): void
    {
        // Arrange
        $categoryNodeTransfer = $this->tester->haveCategory([
            CategoryTransfer::CATEGORY_NODE => [NodeTransfer::NODE_ORDER => static::TEST_NODE_ORDER]])->getCategoryNodeOrFail();

        $categoryNodeTransfer2 = $this->tester->haveCategory([
            CategoryTransfer::CATEGORY_NODE => [NodeTransfer::NODE_ORDER => static::TEST_NODE_ORDER_2]])->getCategoryNodeOrFail();

        $categoryNodeCollectionRequestTransfer = (new CategoryNodeCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->setCategoryNodes(new ArrayObject([
                $categoryNodeTransfer,
                (new NodeTransfer())->setIdCategoryNode(static::TEST_ID_CATEGORY_NODE)->setNodeOrder(static::TEST_NODE_ORDER_3),
                $categoryNodeTransfer2,
            ]));

        // Act
        $categoryNodeCollectionResponseTransfer = $this->tester->getFacade()
            ->reorderCategoryNodeCollection($categoryNodeCollectionRequestTransfer);

        // Assert
        $categoryNodeTransfers = $categoryNodeCollectionResponseTransfer->getCategoryNodes();
        $this->assertCount(3, $categoryNodeTransfers);

        /** @var \Generated\Shared\Transfer\NodeTransfer $resultCategoryNodeTransfer */
        $resultCategoryNodeTransfer = $categoryNodeTransfers->offsetGet(0);
        $this->assertSame($categoryNodeTransfer->getIdCategoryNodeOrFail(), $resultCategoryNodeTransfer->getIdCategoryNodeOrFail());
        $this->assertSame(2, $resultCategoryNodeTransfer->getNodeOrder());

        /** @var \Generated\Shared\Transfer\NodeTransfer $resultCategoryNodeTransfer2 */
        $resultCategoryNodeTransfer2 = $categoryNodeTransfers->offsetGet(1);
        $this->assertSame($categoryNodeTransfer2->getIdCategoryNodeOrFail(), $resultCategoryNodeTransfer2->getIdCategoryNodeOrFail());
        $this->assertSame(1, $resultCategoryNodeTransfer2->getNodeOrderOrFail());

        /** @var \Generated\Shared\Transfer\NodeTransfer $resultCategoryNodeTransfer3 */
        $resultCategoryNodeTransfer3 = $categoryNodeTransfers->offsetGet(2);
        $this->assertSame(static::TEST_ID_CATEGORY_NODE, $resultCategoryNodeTransfer3->getIdCategoryNodeOrFail());
        $this->assertSame(static::TEST_NODE_ORDER_3, $resultCategoryNodeTransfer3->getNodeOrderOrFail());
    }

    /**
     * @return array<string, array<\Generated\Shared\Transfer\CategoryNodeCollectionRequestTransfer>>
     */
    protected function getRequiredFieldsDataProvider(): array
    {
        return [
            'Should throw an exception when `isTransactional` field is not provided.' => [
                (new CategoryNodeCollectionRequestTransfer())->setCategoryNodes(
                    new ArrayObject([(new NodeTransfer())->setIdCategoryNode(static::TEST_ID_CATEGORY_NODE)]),
                ),
            ],
            'Should throw an exception when empty category node collection is provided.' => [
                (new CategoryNodeCollectionRequestTransfer())->setIsTransactional(true),
            ],
            'Should throw exception when category node without ID is provided.' => [
                (new CategoryNodeCollectionRequestTransfer())->setIsTransactional(true)
                    ->setCategoryNodes(new ArrayObject([new NodeTransfer()])),
            ],
        ];
    }

    /**
     * @return array<string, array<bool|int>>
     */
    protected function getIsTransactionalDataProvider(): array
    {
        return [
            'Should not update category node order when `isTransactional` is set to true.' => [true, static::TEST_NODE_ORDER],
            'Should update category node order when `isTransactional` is set to false.' => [false, 1],
        ];
    }

    /**
     * @return array<string, array<bool|int>>
     */
    protected function getTriggerCategoryTreePublishEventDataProvider(): array
    {
        return [
            'Should not trigger category tree publish event when category node order is not updated.' => [false, 1],
            'Should trigger category tree publish event when category node order is updated.' => [true, static::TEST_NODE_ORDER],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface
     */
    protected function createEventFacadeMock(): CategoryToEventFacadeInterface
    {
        return $this->getMockBuilder(CategoryToEventFacadeInterface::class)->getMock();
    }
}
