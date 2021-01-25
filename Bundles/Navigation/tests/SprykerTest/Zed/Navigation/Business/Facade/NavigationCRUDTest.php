<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Navigation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\DuplicateNavigationTransfer;
use Generated\Shared\Transfer\NavigationCriteriaTransfer;
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
    protected const TEST_NAVIGATION_KEY = 'test-navigation-1';
    protected const TEST_NAVIGATION_NAME = 'Test navigation 1';

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
        $navigationTransfer = $this->tester->createNavigationTransfer(static::TEST_NAVIGATION_NAME, static::TEST_NAVIGATION_KEY, true);

        $resultNavigationTransfer = $this->navigationFacade->createNavigation($navigationTransfer);

        $this->assertGreaterThan(0, $resultNavigationTransfer->getIdNavigation());
    }

    /**
     * @return void
     */
    public function testUpdateExistingNavigationPersistsToDatabase(): void
    {
        $navigationTransfer = $this->tester->createNavigation(static::TEST_NAVIGATION_NAME, static::TEST_NAVIGATION_KEY, true);

        $navigationTransferForUpdate = (new NavigationTransfer())
            ->setIdNavigation($navigationTransfer->getIdNavigation())
            ->setName('Test navigation 1 (edited)');

        $updatedNavigationTransfer = $this->navigationFacade->updateNavigation($navigationTransferForUpdate);

        $this->assertSame('Test navigation 1 (edited)', $updatedNavigationTransfer->getName(), 'Name should have changed when updating navigation.');
        $this->assertSame(static::TEST_NAVIGATION_KEY, $updatedNavigationTransfer->getKey(), 'Returned navigation transfer should contain non-updated data as well.');
    }

    /**
     * @return void
     */
    public function testReadExistingNavigationReadsFromDatabase(): void
    {
        $navigationTransfer = $this->tester->createNavigation(static::TEST_NAVIGATION_KEY, static::TEST_NAVIGATION_NAME, true);

        $resultNavigationTransfer = $this->navigationFacade->findNavigation(
            (new NavigationTransfer())->setIdNavigation($navigationTransfer->getIdNavigation())
        );

        $this->assertSame($resultNavigationTransfer->getKey(), $resultNavigationTransfer->getKey(), 'Key read from database should match expected value.');
        $this->assertSame($resultNavigationTransfer->getName(), $resultNavigationTransfer->getName(), 'Name read from database should match expected value.');
        $this->assertSame($resultNavigationTransfer->getIsActive(), $resultNavigationTransfer->getIsActive(), 'Active status read from database should match expected value.');
    }

    /**
     * @return void
     */
    public function testDeleteExistingNavigationDeletesFromDatabase(): void
    {
        $navigationEntity = $this->tester->createNavigation(static::TEST_NAVIGATION_KEY, static::TEST_NAVIGATION_NAME, true);

        $this->navigationFacade->deleteNavigation((new NavigationTransfer())->setIdNavigation($navigationEntity->getIdNavigation()));

        $actualCount = $this->navigationQueryContainer
            ->queryNavigationById($navigationEntity->getIdNavigation())
            ->count();

        $this->assertSame(0, $actualCount, 'Navigation entity should not be in database.');
    }

    /**
     * @return void
     */
    public function testFindNavigationByCriteriaWillFindNavigationByExistingKey(): void
    {
        //Arrange
        $navigationTransfer = $this->tester->createNavigation(static::TEST_NAVIGATION_NAME, static::TEST_NAVIGATION_KEY, true);

        $navigationCriteriaTransfer = (new NavigationCriteriaTransfer())->setKey($navigationTransfer->getKey());

        //Act
        $navigationTransfer = $this->navigationFacade->findNavigationByCriteria($navigationCriteriaTransfer);

        //Assert
        $this->assertNotNull($navigationTransfer, 'Result should not be null.');
        $this->assertSame(static::TEST_NAVIGATION_KEY, $navigationTransfer->getKey(), 'Navigation key does not match expected value.');
        $this->assertSame(static::TEST_NAVIGATION_NAME, $navigationTransfer->getName(), 'Navigation name does not match expected value.');
    }

    /**
     * @return void
     */
    public function testFindNavigationByCriteriaWillFindNavigationByExistingNavigationId(): void
    {
        //Arrange
        $navigationTransfer = $this->tester->createNavigation(static::TEST_NAVIGATION_NAME, static::TEST_NAVIGATION_KEY, true);

        $navigationCriteriaTransfer = (new NavigationCriteriaTransfer())->setIdNavigation($navigationTransfer->getIdNavigation());

        //Act
        $resultNavigationTransfer = $this->navigationFacade->findNavigationByCriteria($navigationCriteriaTransfer);

        //Assert
        $this->assertNotNull($resultNavigationTransfer, 'Result should not be null.');
        $this->assertSame(static::TEST_NAVIGATION_KEY, $resultNavigationTransfer->getKey(), 'Navigation key does not match expected value.');
        $this->assertSame(static::TEST_NAVIGATION_NAME, $resultNavigationTransfer->getName(), 'Navigation name does not match expected value.');
    }

    /**
     * @return void
     */
    public function testFindAllNavigationsWillReturnACollectionOfNavigationTransfers(): void
    {
        //Arrange
        $this->tester->createNavigation(static::TEST_NAVIGATION_NAME, static::TEST_NAVIGATION_KEY, true);

        //Act
        $navigationTransfers = $this->navigationFacade->getAllNavigations();

        //Arrange
        $this->assertGreaterThanOrEqual(1, count($navigationTransfers), 'Collection count does not match an expected value.');
        $this->assertInstanceOf(
            NavigationTransfer::class,
            $navigationTransfers[0],
            sprintf('Collection elements expected to be an instance of %s', NavigationTransfer::class)
        );
    }

    /**
     * @return void
     */
    public function testDuplicateNavigationWillPersistTheSameNavigationAsExistingOne(): void
    {
        // Arrange
        $baseNavigationTransfer = $this->tester->createNavigation('test-key', 'Test navigation', true);
        $duplicateNavigationTransfer = $this->tester->createDuplicateNavigationTransfer(
            static::TEST_NAVIGATION_KEY,
            static::TEST_NAVIGATION_NAME,
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
            ->findNavigationTree($navigationResponseTransfer->getNavigation())
            ->getNodes()[0];

        $duplicatedNavigationNodeTransfer1 = $navigationTreeNodeTransfer->getNavigationNode();
        $duplicatedNavigationNodeTransfer2 = $navigationTreeNodeTransfer
            ->getChildren()[0]
            ->getNavigationNode();

        $actualNavigationTransfer = $navigationResponseTransfer->getNavigation();
        [$navigationNodeLocalizedAttributesTransfer1, $navigationNodeLocalizedAttributesTransfer2]
            = $navigationNodeTransfer->getNavigationNodeLocalizedAttributes();
        [$duplicatedNavigationNodeLocalizedAttributesTransfer1, $duplicatedNavigationNodeLocalizedAttributesTransfer2]
            = $duplicatedNavigationNodeTransfer1->getNavigationNodeLocalizedAttributes();
        $this->assertSame($duplicateNavigationTransfer->getName(), $actualNavigationTransfer->getName());
        $this->assertSame($duplicateNavigationTransfer->getKey(), $actualNavigationTransfer->getKey());
        $this->assertSame($duplicatedNavigationNodeTransfer1->getIsActive(), $navigationNodeTransfer->getIsActive());
        $this->assertSame(
            $duplicatedNavigationNodeTransfer2->getFkParentNavigationNode(),
            $duplicatedNavigationNodeTransfer1->getIdNavigationNode()
        );
        $this->assertSame(
            $duplicatedNavigationNodeLocalizedAttributesTransfer1->getExternalUrl(),
            $navigationNodeLocalizedAttributesTransfer1->getExternalUrl()
        );
        $this->assertSame(
            $duplicatedNavigationNodeLocalizedAttributesTransfer1->getTitle(),
            $navigationNodeLocalizedAttributesTransfer1->getTitle()
        );
        $this->assertSame(
            $duplicatedNavigationNodeLocalizedAttributesTransfer2->getExternalUrl(),
            $navigationNodeLocalizedAttributesTransfer2->getExternalUrl()
        );
        $this->assertSame(
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
            static::TEST_NAVIGATION_KEY,
            static::TEST_NAVIGATION_NAME,
            static::NON_EXISTENT_NAVIGATION_ID
        );

        // Act
        $navigationResponseTransfer = $this->navigationFacade->duplicateNavigation($duplicateNavigationTransfer);

        // Assert
        $this->assertFalse($navigationResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::ERROR_MESSAGE_NAVIGATION_TREE_NOT_FOUND,
            $navigationResponseTransfer->getErrors()[0]->getMessage()
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
            static::TEST_NAVIGATION_KEY,
            static::TEST_NAVIGATION_NAME,
            $navigationTransfer->getIdNavigation()
        );

        // Act
        $navigationResponseTransfer = $this->navigationFacade->duplicateNavigation($duplicateNavigationTransfer);

        // Assert
        $this->assertFalse($navigationResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::ERROR_MESSAGE_NAVIGATION_KEY_ALREADY_EXISTS,
            $navigationResponseTransfer->getErrors()[0]->getMessage()
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
                ->setName(static::TEST_NAVIGATION_NAME)
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
                ->setKey(static::TEST_NAVIGATION_KEY)
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
                ->setName(static::TEST_NAVIGATION_NAME)
                ->setKey(static::TEST_NAVIGATION_KEY)
        );
    }
}
