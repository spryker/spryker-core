<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Navigation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DuplicateNavigationTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Navigation\Business\NavigationFacade;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Navigation
 * @group Business
 * @group Facade
 * @group NavigationCRUDTest
 * Add your own group annotations below this line
 */
class NavigationCRUDTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Navigation\Business\Navigation\NavigationDuplicator::ERROR_MESSAGE_NAVIGATION_TREE_NOT_FOUND
     */
    protected const ERROR_MESSAGE_NAVIGATION_TREE_NOT_FOUND = 'Navigation tree transfer is not found.';

    /**
     * @uses \Spryker\Zed\Navigation\Business\Navigation\NavigationDuplicator::ERROR_MESSAGE_NAVIGATION_KEY_ALREADY_EXISTS
     */
    protected const ERROR_MESSAGE_NAVIGATION_KEY_ALREADY_EXISTS = 'Navigation with the same key already exists.';

    protected const NON_EXISTENT_NAVIGATION_ID = -1;

    /**
     * @var \Spryker\Zed\Navigation\Business\NavigationFacade
     */
    protected $navigationFacade;

    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationQueryContainer
     */
    protected $navigationQueryContainer;

    /**
     * @var \SprykerTest\Zed\Navigation\NavigationBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->navigationFacade = new NavigationFacade();
        $this->navigationQueryContainer = new NavigationQueryContainer();
    }

    /**
     * @return void
     */
    public function testCreateNewNavigationPersistsToDatabase(): void
    {
        $navigationTransfer = $this->tester->createNavigationTransfer('test-navigation-1', 'Test navigation 1', true);

        $navigationTransfer = $this->navigationFacade->createNavigation($navigationTransfer);

        $this->assertGreaterThan(0, $navigationTransfer->getIdNavigation());
    }

    /**
     * @return void
     */
    public function testUpdateExistingNavigationPersistsToDatabase(): void
    {
        $navigationEntity = $this->tester->createNavigation('Test navigation 1', 'test-navigation-1', true);

        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer
            ->setIdNavigation($navigationEntity->getIdNavigation())
            ->setName('Test navigation 1 (edited)');

        $updatedNavigationTransfer = $this->navigationFacade->updateNavigation($navigationTransfer);

        $this->assertSame('Test navigation 1 (edited)', $updatedNavigationTransfer->getName(), 'Name should have changed when updating navigation.');
        $this->assertSame('test-navigation-1', $updatedNavigationTransfer->getKey(), 'Returned navigation transfer should contain non-updated data as well.');
    }

    /**
     * @return void
     */
    public function testReadExistingNavigationReadsFromDatabase(): void
    {
        $navigationEntity = $this->tester->createNavigation('test-navigation-1', 'Test navigation 1', true);

        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($navigationEntity->getIdNavigation());

        $navigationTransfer = $this->navigationFacade->findNavigation($navigationTransfer);

        $this->assertSame($navigationEntity->getKey(), $navigationTransfer->getKey(), 'Key read from database should match expected value.');
        $this->assertSame($navigationEntity->getName(), $navigationTransfer->getName(), 'Name read from database should match expected value.');
        $this->assertSame($navigationEntity->getIsActive(), $navigationTransfer->getIsActive(), 'Active status read from database should match expected value.');
    }

    /**
     * @return void
     */
    public function testDeleteExistingNavigationDeletesFromDatabase(): void
    {
        $navigationEntity = $this->tester->createNavigation('test-navigation-1', 'Test navigation 1', true);

        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($navigationEntity->getIdNavigation());

        $this->navigationFacade->deleteNavigation($navigationTransfer);

        $actualCount = $this->navigationQueryContainer
            ->queryNavigationById($navigationEntity->getIdNavigation())
            ->count();

        $this->assertSame(0, $actualCount, 'Navigation entity should not be in database.');
    }

    /**
     * @return void
     */
    public function testDuplicateNavigationWillPersistTheSameNavigationAsExistingOne(): void
    {
        // Arrange
        $baseNavigationTransfer = $this->tester->createNavigation('test-key', 'Test navigation', true);
        $duplicateNavigationTransfer = $this->tester->createDuplicateNavigationTransfer(
            'test-navigation-1',
            'Test navigation 1',
            $baseNavigationTransfer->getIdNavigation()
        );
        $navigationNodeTransfer = $this->tester->createNavigationNode($baseNavigationTransfer->getIdNavigation());
        $this->tester->createNavigationNode(
            $baseNavigationTransfer->getIdNavigation(),
            $navigationNodeTransfer->getIdNavigationNode()
        );

        // Act
        $navigationResponseTransfer = $this->navigationFacade->duplicateNavigation($duplicateNavigationTransfer);

        // Assert
        $navigationTreeNodeTransfer = $this->navigationFacade
            ->findNavigationTree($navigationResponseTransfer->getNavigationTransfer())
            ->getNodes()[0];

        $duplicatedNavigationNodeTransfer1 = $navigationTreeNodeTransfer->getNavigationNode();
        $duplicatedNavigationNodeTransfer2 = $navigationTreeNodeTransfer
            ->getChildren()[0]
            ->getNavigationNode();

        $actualNavigationTransfer = $navigationResponseTransfer->getNavigationTransfer();
        [$navigationNodeLocalizedAttributesTransfer1, $navigationNodeLocalizedAttributesTransfer2]
            = $navigationNodeTransfer->getNavigationNodeLocalizedAttributes();
        [$duplicatedNavigationNodeLocalizedAttributesTransfer1, $duplicatedNavigationNodeLocalizedAttributesTransfer2]
            = $duplicatedNavigationNodeTransfer1->getNavigationNodeLocalizedAttributes();
        $this->assertEquals($duplicateNavigationTransfer->getName(), $actualNavigationTransfer->getName());
        $this->assertEquals($duplicateNavigationTransfer->getKey(), $actualNavigationTransfer->getKey());
        $this->assertEquals($duplicatedNavigationNodeTransfer1->getIsActive(), $navigationNodeTransfer->getIsActive());
        $this->assertEquals(
            $duplicatedNavigationNodeTransfer2->getFkParentNavigationNode(),
            $duplicatedNavigationNodeTransfer1->getIdNavigationNode()
        );
        $this->assertEquals(
            $duplicatedNavigationNodeLocalizedAttributesTransfer1->getExternalUrl(),
            $navigationNodeLocalizedAttributesTransfer1->getExternalUrl()
        );
        $this->assertEquals(
            $duplicatedNavigationNodeLocalizedAttributesTransfer1->getTitle(),
            $navigationNodeLocalizedAttributesTransfer1->getTitle()
        );
        $this->assertEquals(
            $duplicatedNavigationNodeLocalizedAttributesTransfer2->getExternalUrl(),
            $navigationNodeLocalizedAttributesTransfer2->getExternalUrl()
        );
        $this->assertEquals(
            $duplicatedNavigationNodeLocalizedAttributesTransfer2->getTitle(),
            $navigationNodeLocalizedAttributesTransfer2->getTitle()
        );
    }

    /**
     * @return void
     */
    public function testDuplicateNonExistentNavigationWillReturnResponseWithError(): void
    {
        // Arrange
        $duplicateNavigationTransfer = $this->tester->createDuplicateNavigationTransfer(
            'test-navigation-1',
            'Test navigation 1',
            static::NON_EXISTENT_NAVIGATION_ID
        );

        // Act
        $navigationResponseTransfer = $this->navigationFacade->duplicateNavigation($duplicateNavigationTransfer);

        // Assert
        $this->assertFalse($navigationResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::ERROR_MESSAGE_NAVIGATION_TREE_NOT_FOUND,
            $navigationResponseTransfer->getError()->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testDuplicateNavigationWithExistentKeyWillReturnResponseWithError(): void
    {
        // Arrange
        $navigationTransfer = $this->tester->createNavigation('test-key', 'test-navigation-1', true);
        $duplicateNavigationTransfer = $this->tester->createDuplicateNavigationTransfer(
            'test-navigation-1',
            'Test navigation 1',
            $navigationTransfer->getIdNavigation()
        );

        // Act
        $navigationResponseTransfer = $this->navigationFacade->duplicateNavigation($duplicateNavigationTransfer);

        // Assert
        $this->assertFalse($navigationResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::ERROR_MESSAGE_NAVIGATION_KEY_ALREADY_EXISTS,
            $navigationResponseTransfer->getError()->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testDuplicateNavigationWillThrowExceptionWithoutKey(): void
    {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->navigationFacade->duplicateNavigation(
            (new DuplicateNavigationTransfer())
                ->setIdBaseNavigation(666)
                ->setName('Test key 1')
        );
    }

    /**
     * @return void
     */
    public function testDuplicateNavigationWillThrowExceptionWithoutName(): void
    {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->navigationFacade->duplicateNavigation(
            (new DuplicateNavigationTransfer())
                ->setIdBaseNavigation(666)
                ->setKey('Test key 1')
        );
    }

    /**
     * @return void
     */
    public function testDuplicateNavigationWillThrowExceptionWithoutIdBaseNavigation(): void
    {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->navigationFacade->duplicateNavigation(
            (new DuplicateNavigationTransfer())
                ->setName('Test key 1')
                ->setKey('Test key 1')
        );
    }
}
