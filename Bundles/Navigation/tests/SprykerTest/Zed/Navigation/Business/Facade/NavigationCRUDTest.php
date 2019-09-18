<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Navigation\Business\Facade;

use Codeception\Test\Unit;
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
    public function setUp()
    {
        parent::setUp();

        $this->navigationFacade = new NavigationFacade();
        $this->navigationQueryContainer = new NavigationQueryContainer();
    }

    /**
     * @return void
     */
    public function testCreateNewNavigationPersistsToDatabase()
    {
        $navigationTransfer = $this->createNavigationTransfer('test-navigation-1', 'Test navigation 1', true);

        $navigationTransfer = $this->navigationFacade->createNavigation($navigationTransfer);

        $this->assertGreaterThan(0, $navigationTransfer->getIdNavigation());
    }

    /**
     * @return void
     */
    public function testUpdateExistingNavigationPersistsToDatabase()
    {
        $navigationEntity = $this->createNavigationEntity('test-navigation-1', 'Test navigation 1', true);

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
    public function testReadExistingNavigationReadsFromDatabase()
    {
        $navigationEntity = $this->createNavigationEntity('test-navigation-1', 'Test navigation 1', true);

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
    public function testDeleteExistingNavigationDeletesFromDatabase()
    {
        $navigationEntity = $this->createNavigationEntity('test-navigation-1', 'Test navigation 1', true);

        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($navigationEntity->getIdNavigation());

        $this->navigationFacade->deleteNavigation($navigationTransfer);

        $actualCount = $this->navigationQueryContainer
            ->queryNavigationById($navigationEntity->getIdNavigation())
            ->count();

        $this->assertSame(0, $actualCount, 'Navigation entity should not be in database.');
    }

    /**
     * @param string $key
     * @param string $name
     * @param bool $isActive
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    protected function createNavigationTransfer($key, $name, $isActive)
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
    protected function createNavigationEntity($key, $name, $isActive)
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
