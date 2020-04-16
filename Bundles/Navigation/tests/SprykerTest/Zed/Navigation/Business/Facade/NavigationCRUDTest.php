<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Navigation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Spryker\Zed\Navigation\Business\NavigationFacade;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainer;
use Spryker\Zed\Navigation\Persistence\NavigationRepository;

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
     * @var \Spryker\Zed\Navigation\Persistence\NavigationRepositoryInterface
     */
    protected $navigationRepository;

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
        $this->navigationRepository = new NavigationRepository();
    }

    /**
     * @return void
     */
    public function testCreateNewNavigationPersistsToDatabase(): void
    {
        $navigationTransfer = $this->createNavigationTransfer('test-navigation-1', 'Test navigation 1', true);

        $navigationTransfer = $this->navigationFacade->createNavigation($navigationTransfer);

        $this->assertGreaterThan(0, $navigationTransfer->getIdNavigation());
    }

    /**
     * @return void
     */
    public function testUpdateExistingNavigationPersistsToDatabase(): void
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
    public function testReadExistingNavigationReadsFromDatabase(): void
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
    public function testDeleteExistingNavigationDeletesFromDatabase(): void
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
     * @return void
     */
    public function testDuplicateNavigationWillPersistTheSameNavigationAsExistingOne(): void
    {
        // Arrange
        $baseNavigationEntity = $this->createNavigationEntity(
            'test-navigation-for-duplication',
            'Test navigation for duplication',
            true
        );
        $newNavigationName = 'new navigation name';
        $newNavigationKey = 'new navigation key';
        $baseNavigationTransfer = (new NavigationTransfer())
            ->setKey($baseNavigationEntity->getKey())
            ->setName($baseNavigationEntity->getName())
            ->setIdNavigation($baseNavigationEntity->getIdNavigation());
        $newNavigationTransfer = (new NavigationTransfer())
            ->setName($newNavigationName)
            ->setKey($newNavigationKey);

        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer
            ->setFkNavigation($baseNavigationTransfer->getIdNavigation())
            ->setIsActive(true);

        $idLocale1 = $this->tester->haveLocale(['localeName' => 'ab_CD'])->getIdLocale();
        $navigationNodeLocalizedAttributesTransfer1 = $this->createNavigationNodeLocalizedAttributesTransfer(
            $idLocale1,
            'Node 1 (ab_CD)',
            'http://example.com/ab/1'
        );

        $idLocale2 = $this->tester->haveLocale(['localeName' => 'ef_GH'])->getIdLocale();
        $navigationNodeLocalizedAttributesTransfer2 = $this->createNavigationNodeLocalizedAttributesTransfer(
            $idLocale2,
            'Node 1 (ef_GH)',
            'http://example.com/ef/1'
        );

        $navigationNodeTransfer
            ->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer1)
            ->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer2);

        $navigationNodeTransfer = $this->navigationFacade->createNavigationNode($navigationNodeTransfer);

        // Act
        $duplicatedNavigationTransfer = $this->navigationFacade->duplicateNavigation($newNavigationTransfer, $baseNavigationTransfer);

        // Assert
        $duplicatedNavigationNodeTransfer = $this->navigationRepository
            ->getNavigationNodesByNavigationId($duplicatedNavigationTransfer->getIdNavigation())[0];
        $duplicatedNavigationNodeLocalizedAttributesTransfers = $duplicatedNavigationNodeTransfer->getNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesTransfers = $navigationNodeTransfer->getNavigationNodeLocalizedAttributes();
        $this->assertEquals($newNavigationTransfer->getName(), $duplicatedNavigationTransfer->getName());
        $this->assertEquals($newNavigationTransfer->getKey(), $duplicatedNavigationTransfer->getKey());
        $this->assertEquals($duplicatedNavigationNodeTransfer->getIsActive(), $navigationNodeTransfer->getIsActive());
        $this->assertEquals($duplicatedNavigationNodeLocalizedAttributesTransfers[0]->getUrl(), $navigationNodeLocalizedAttributesTransfers[0]->getUrl());
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

    /**
     * @param int $idLocale
     * @param string $nodeTitle
     * @param string $externalUrl
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    protected function createNavigationNodeLocalizedAttributesTransfer(
        int $idLocale,
        string $nodeTitle,
        string $externalUrl
    ): NavigationNodeLocalizedAttributesTransfer {
        $navigationNodeLocalizedAttributesTransfer = new NavigationNodeLocalizedAttributesTransfer();
        $navigationNodeLocalizedAttributesTransfer
            ->setFkLocale($idLocale)
            ->setTitle($nodeTitle)
            ->setExternalUrl($externalUrl);

        return $navigationNodeLocalizedAttributesTransfer;
    }
}
