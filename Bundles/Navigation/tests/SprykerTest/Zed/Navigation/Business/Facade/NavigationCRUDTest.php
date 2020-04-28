<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Navigation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\NavigationCriteriaTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
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
     * @var \Spryker\Zed\Navigation\Business\NavigationFacade
     */
    protected $navigationFacade;

    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationQueryContainer
     */
    protected $navigationQueryContainer;

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
        $navigationTransfer = $this->createNavigationTransfer(static::TEST_NAVIGATION_KEY, static::TEST_NAVIGATION_NAME, true);

        $navigationTransfer = $this->navigationFacade->createNavigation($navigationTransfer);

        $this->assertGreaterThan(0, $navigationTransfer->getIdNavigation());
    }

    /**
     * @return void
     */
    public function testUpdateExistingNavigationPersistsToDatabase(): void
    {
        $navigationEntity = $this->createNavigationEntity(static::TEST_NAVIGATION_KEY, static::TEST_NAVIGATION_NAME, true);

        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer
            ->setIdNavigation($navigationEntity->getIdNavigation())
            ->setName('Test navigation 1 (edited)');

        $updatedNavigationTransfer = $this->navigationFacade->updateNavigation($navigationTransfer);

        $this->assertSame('Test navigation 1 (edited)', $updatedNavigationTransfer->getName(), 'Name should have changed when updating navigation.');
        $this->assertSame(static::TEST_NAVIGATION_KEY, $updatedNavigationTransfer->getKey(), 'Returned navigation transfer should contain non-updated data as well.');
    }

    /**
     * @return void
     */
    public function testReadExistingNavigationReadsFromDatabase(): void
    {
        $navigationEntity = $this->createNavigationEntity(static::TEST_NAVIGATION_KEY, static::TEST_NAVIGATION_NAME, true);

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
        $navigationEntity = $this->createNavigationEntity(static::TEST_NAVIGATION_KEY, static::TEST_NAVIGATION_NAME, true);

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
    public function testFindNavigationByCriteriaWillFindNavigationByExistingKey(): void
    {
        //Arrange
        $navigationEntity = $this->createNavigationEntity(static::TEST_NAVIGATION_KEY, static::TEST_NAVIGATION_NAME, true);

        $navigationCriteriaTransfer = new NavigationCriteriaTransfer();
        $navigationCriteriaTransfer->setKey($navigationEntity->getKey());

        //Act
        $navigationTransfer = $this->navigationFacade->findNavigationByCriteria($navigationCriteriaTransfer);

        //Assert
        $this->assertNotNull($navigationTransfer, 'Result should not be null.');
        $this->assertEquals(static::TEST_NAVIGATION_KEY, $navigationTransfer->getKey(), 'Navigation key does not match expected value.');
        $this->assertEquals(static::TEST_NAVIGATION_NAME, $navigationTransfer->getName(), 'Navigation name does not match expected value.');
    }

    /**
     * @return void
     */
    public function testFindNavigationByCriteriaWillFindNavigationByExistingNavigationId(): void
    {
        //Arrange
        $navigationEntity = $this->createNavigationEntity(static::TEST_NAVIGATION_KEY, static::TEST_NAVIGATION_NAME, true);

        $navigationCriteriaTransfer = new NavigationCriteriaTransfer();
        $navigationCriteriaTransfer->setIdNavigation($navigationEntity->getIdNavigation());

        //Act
        $navigationTransfer = $this->navigationFacade->findNavigationByCriteria($navigationCriteriaTransfer);

        //Assert
        $this->assertNotNull($navigationTransfer, 'Result should not be null.');
        $this->assertEquals(static::TEST_NAVIGATION_KEY, $navigationTransfer->getKey(), 'Navigation key does not match expected value.');
        $this->assertEquals(static::TEST_NAVIGATION_NAME, $navigationTransfer->getName(), 'Navigation name does not match expected value.');
    }

    /**
     * @return void
     */
    public function testFindAllNavigationsWillReturnACollectionOfNavigationTransfers(): void
    {
        //Arrange
        $this->createNavigationEntity(static::TEST_NAVIGATION_KEY, static::TEST_NAVIGATION_NAME, true);

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
     * @param string $key
     * @param string $name
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    protected function createNavigationTransfer(string $key, string $name, bool $isActive): NavigationTransfer
    {
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer
            ->setKey($key)
            ->setName($name)
            ->setIsActive($isActive);

        return $navigationTransfer;
    }

    /**
     * @param string $key
     * @param string $name
     * @param bool $isActive
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigation
     */
    protected function createNavigationEntity(string $key, string $name, bool $isActive): SpyNavigation
    {
        $navigationEntity = new SpyNavigation();
        $navigationEntity
            ->setKey($key)
            ->setName($name)
            ->setIsActive($isActive)
            ->save();

        return $navigationEntity;
    }
}
