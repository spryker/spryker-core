<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CategoryCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeBridge;
use Spryker\Zed\Event\Business\EventFacade;
use SprykerTest\Zed\Category\CategoryBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Category
 * @group Business
 * @group Facade
 * @group CategoryCrudFacadeTest
 * Add your own group annotations below this line
 */
class CategoryCrudFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Category\CategoryBusinessTester
     */
    protected CategoryBusinessTester $tester;

    /**
     * Test ensures to always get a Collection back even if no entity was found.
     *
     * @return void
     */
    public function testGetCategoryReturnsEmptyCollectionWhenNoEntityMatchedByCriteria(): void
    {
        // Arrange
        $this->tester->haveCategoryTransferTwoPersisted();
        $categoryCriteriaTransfer = $this->tester->haveCategoryCriteriaTransferOneCriteria();

        // Act
        $categoryCollectionTransfer = $this->tester->getFacade()->getCategoryCollection($categoryCriteriaTransfer);

        // Assert
        $this->tester->assertCategoryCollectionIsEmpty($categoryCollectionTransfer);
    }

    /**
     * Test ensures to get a Collection with entities back when criteria was matching.
     *
     * @return void
     */
    public function testGetCategoryReturnsCollectionWithOneCategoryEntityWhenCriteriaMatched(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryTransferOnePersisted();
        $categoryCriteriaTransfer = $this->tester->haveCategoryCriteriaTransferOneCriteria();

        // Act
        $categoryCollectionTransfer = $this->tester->getFacade()->getCategoryCollection($categoryCriteriaTransfer);

        // Assert
        $this->tester->assertCategoryCollectionContainsTransferWithId($categoryCollectionTransfer, $categoryTransfer);
    }

    /**
     * Test ensures that expanders are applied to found entities.
     *
     * @return void
     */
    public function testGetCategoryCollectionReturnsCollectionWithOneExpandedCategoryEntity(): void
    {
        // Arrange
        $this->tester->haveCategoryExpanderPluginSetUuidTwoEnabled();
        $categoryTransfer = $this->tester->haveCategoryTransferOnePersisted();

        $categoryCriteriaTransfer = $this->tester->haveCategoryCriteriaTransferOneCriteria();

        // Act
        $categoryCollectionTransfer = $this->tester->getFacade()->getCategoryCollection($categoryCriteriaTransfer);

        // Assert
        $this->tester->assertCategoryCollectionContainsTransferWithId($categoryCollectionTransfer, $categoryTransfer);
    }

    /**
     * @return void
     */
    public function testCreateCategoryCollectionReturnsCollectionWithOneCategoryEntityWhenEntityWasSaved(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryTransferOne();
        $categoryCollectionRequestTransfer = new CategoryCollectionRequestTransfer();
        $categoryCollectionRequestTransfer->addCategory($categoryTransfer);

        // Act
        $categoryCollectionResponseTransfer = $this->tester->getFacade()->createCategoryCollection($categoryCollectionRequestTransfer);

        // Assert
        $this->tester->assertcategoryCollectionResponseContainsOneOneTransfer($categoryCollectionResponseTransfer);
    }

    /**
     * Tests that post-create plugins are applied to entities.
     *
     * @return void
     */
    public function testCreateCategoryCollectionAppliesPostCreatePlugins(): void
    {
        // Arrange
        $this->tester->haveCategoryPostCreatePluginSetUuidTwoEnabled();
        $categoryTransfer = $this->tester->haveCategoryTransferOne();
        $categoryCollectionRequestTransfer = new CategoryCollectionRequestTransfer();
        $categoryCollectionRequestTransfer->addCategory($categoryTransfer);

        // Act
        $this->tester->getFacade()->createCategoryCollection($categoryCollectionRequestTransfer);

        // Call assertion performed by plugin mock
    }

    /**
     * Tests that EventFacade is trigger to perform the asynchronous P&S process.
     *
     * @return void
     */
    public function testCreateCategoryCollectionTriggersEventFacadeToPerformTheAsynchronousPublishAndSynchronizeProcess(): void
    {
        // Arrange
        $this->tester->haveCategoryPostCreatePluginSetUuidTwoEnabled();
        $categoryTransfer = $this->tester->haveCategoryTransferOne();
        $categoryCollectionRequestTransfer = new CategoryCollectionRequestTransfer();
        $categoryCollectionRequestTransfer->addCategory($categoryTransfer);

        // Event Facade mock to perform expectations
        $eventFacadeMock = $this->createMock(EventFacade::class);
        $matcher = $this->exactly(3);
        $eventFacadeMock->expects($matcher)
            ->method('trigger')
            ->willReturnCallback(function ($event, $subject) use ($matcher, $categoryTransfer) {
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals(CategoryEvents::CATEGORY_BEFORE_CREATE, $event),
                    2 => $this->assertEquals(CategoryEvents::CATEGORY_AFTER_CREATE, $event),
                    3 => $this->assertEquals(CategoryEvents::CATEGORY_AFTER_PUBLISH_CREATE, $event),
                };

            match ($matcher->numberOfInvocations()) {
                1, 2 => $this->assertInstanceOf(CategoryTransfer::class, $subject) && $this->assertEquals($categoryTransfer->getIdCategory(), $subject->getIdCategory()),
                3 => $this->assertInstanceOf(EventEntityTransfer::class, $subject) && $this->assertEquals($categoryTransfer->getIdCategory(), $subject->getId()),
            };
            });

        $this->tester->mockFactoryMethod('getEventFacade', new CategoryToEventFacadeBridge($eventFacadeMock));

        // Act
        $this->tester->getFacade()->createCategoryCollection($categoryCollectionRequestTransfer);

        // Call assertion performed by plugin mock
    }

    /**
     * Tests that entities are validated with internal ValidatorRuleInterface.
     *
     * @return void
     */
    public function testCreateCategoryCollectionReturnsErroredCollectionResponseWhenValidationRuleFailed(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryTransferOnePersisted();

        $this->tester->haveCategoryAlwaysFailingCreateValidatorRuleEnabled(); // This will always return a validation error
        $categoryCollectionRequestTransfer = new CategoryCollectionRequestTransfer();
        $categoryCollectionRequestTransfer->addCategory($categoryTransfer);

        // Act
        $categoryCollectionResponseTransfer = $this->tester->getFacade()->createCategoryCollection($categoryCollectionRequestTransfer);

        // Assert
        $this->tester->assertCategoryCollectionResponseContainsFailedValidationRuleError($categoryCollectionResponseTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateCategoryCollectionReturnsCollectionWithOneCategoryEntityWhenEntityWasSaved(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryTransferOnePersisted();
        $categoryCollectionRequestTransfer = new CategoryCollectionRequestTransfer();
        $categoryCollectionRequestTransfer->addCategory($categoryTransfer);

        // Act
        $categoryCollectionResponseTransfer = $this->tester->getFacade()->updateCategoryCollection($categoryCollectionRequestTransfer);

        // Assert
        $this->tester->assertCategoryCollectionResponseContainsOneOneTransferWithId($categoryCollectionResponseTransfer, $categoryTransfer);
    }

    /**
     * Tests that post-update plugins are applied to entities.
     *
     * @return void
     */
    public function testUpdateCategoryCollectionAppliesPostUpdatePlugins(): void
    {
        // Arrange
        $this->tester->haveCategoryPostUpdatePluginSetUuidTwoEnabled();
        $categoryTransfer = $this->tester->haveCategoryTransferOnePersisted();
        $categoryCollectionRequestTransfer = new CategoryCollectionRequestTransfer();
        $categoryCollectionRequestTransfer->addCategory($categoryTransfer);

        // Act
        $this->tester->getFacade()->updateCategoryCollection($categoryCollectionRequestTransfer);

        // Call assertion performed by plugin mock
    }

    /**
     * Tests that entities are validated with internal ValidatorRuleInterface.
     *
     * @return void
     */
    public function testUpdateCategoryCollectionReturnsErroredCollectionResponseWhenValidationRuleFailed(): void
    {
        // Arrange
        $this->tester->haveCategoryAlwaysFailingUpdateValidatorRuleEnabled(); // This will always return a validation error
        $categoryTransfer = $this->tester->haveCategoryTransferOnePersisted();
        $categoryCollectionRequestTransfer = new CategoryCollectionRequestTransfer();
        $categoryCollectionRequestTransfer->addCategory($categoryTransfer);

        // Act
        $categoryCollectionResponseTransfer = $this->tester->getFacade()->updateCategoryCollection($categoryCollectionRequestTransfer);

        // Assert
        $this->tester->assertCategoryCollectionResponseContainsFailedValidationRuleError($categoryCollectionResponseTransfer);
    }

    /**
     * Test ensures to always get a Collection back even if no entity was deleted.
     *
     * @return void
     */
    public function testDeleteCategoryReturnsEmptyCollectionWhenNoEntityMatchedByCriteria(): void
    {
        // Arrange
        $this->tester->haveCategoryTransferTwoPersisted();
        $categoryDeleteCriteriaTransfer = $this->tester->haveCategoryDeleteCriteriaTransferOneCriteria();

        // Act
        $categoryCollectionResponseTransfer = $this->tester->getFacade()->deleteCategoryCollection($categoryDeleteCriteriaTransfer);

        // Assert
        $this->tester->assertCategoryCollectionResponseIsEmpty($categoryCollectionResponseTransfer);
    }

    /**
     * Test ensures to get a Collection with deleted entities back when criteria was matching.
     *
     * @return void
     */
    public function testDeleteCategoryReturnsCollectionWithOneCategoryEntityWhenCriteriaMatched(): void
    {
        // Arrange
        $categoryTransfer = $this->tester->haveCategoryTransferOnePersisted();
        $categoryDeleteCriteriaTransfer = $this->tester->haveCategoryDeleteCriteriaTransferOneCriteria();

        // Act
        $categoryCollectionResponseTransfer = $this->tester->getFacade()->deleteCategoryCollection($categoryDeleteCriteriaTransfer);

        // Assert
        $this->tester->assertCategoryCollectionResponseContainsOneOneTransferWithId($categoryCollectionResponseTransfer, $categoryTransfer);
    }
}
