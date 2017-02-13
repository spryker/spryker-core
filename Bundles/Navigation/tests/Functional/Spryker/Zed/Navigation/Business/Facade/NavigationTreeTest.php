<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Navigation\Business\Facade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\NavigationTreeNodeTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes;
use Spryker\Zed\Navigation\Business\NavigationFacade;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Navigation
 * @group Business
 * @group Facade
 * @group NavigationTreeTest
 */
class NavigationTreeTest extends Test
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
     * @var int
     */
    protected $idLocale;

    /**
     * @var \Generated\Shared\Transfer\NavigationTransfer
     */
    protected $navigationTransfer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->navigationFacade = new NavigationFacade();
        $this->navigationQueryContainer = new NavigationQueryContainer();
        $this->idLocale = $this->createLocale('ab_CD');
        $this->setUpNavigationTree();
    }

    /**
     * @return void
     */
    public function testReadExistingNavigationTreeFromDatabaseReturnsCorrectHierarchy()
    {
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($this->navigationTransfer->getIdNavigation());
        $actualTreeTransfer = $this->navigationFacade->findNavigationTree($navigationTransfer);

        $expectedTreeTransfer = (new NavigationTreeTransfer())
            ->setNavigation((new NavigationTransfer())
                ->setKey('test-navigation-1')
                ->setName('Test navigation 1')
                ->setIsActive(true))
            ->addNode((new NavigationTreeNodeTransfer())
                ->setNavigationNode((new NavigationNodeTransfer())
                    ->addNavigationNodeLocalizedAttribute((new NavigationNodeLocalizedAttributesTransfer())
                        ->setTitle('Node 1')
                        ->setExternalUrl('/node/1')
                        ->setFkLocale($this->idLocale))
                    ->setIsActive(true)
                    ->setWeight(10))
                ->addChild((new NavigationTreeNodeTransfer())
                    ->setNavigationNode((new NavigationNodeTransfer())
                        ->addNavigationNodeLocalizedAttribute((new NavigationNodeLocalizedAttributesTransfer())
                            ->setTitle('Node 1.1')
                            ->setExternalUrl('/node/1/1')
                            ->setFkLocale($this->idLocale))
                        ->setIsActive(true)
                        ->setWeight(20)))
                ->addChild((new NavigationTreeNodeTransfer())
                    ->setNavigationNode((new NavigationNodeTransfer())
                        ->addNavigationNodeLocalizedAttribute((new NavigationNodeLocalizedAttributesTransfer())
                            ->setTitle('Node 1.2')
                            ->setExternalUrl('/node/1/2')
                            ->setFkLocale($this->idLocale))
                        ->setIsActive(true)
                        ->setWeight(10))))
            ->addNode((new NavigationTreeNodeTransfer())
                ->setNavigationNode((new NavigationNodeTransfer())
                    ->addNavigationNodeLocalizedAttribute((new NavigationNodeLocalizedAttributesTransfer())
                        ->setTitle('Node 2')
                        ->setExternalUrl('/node/2')
                        ->setFkLocale($this->idLocale))
                    ->setIsActive(true)
                    ->setWeight(0)))
            ->addNode((new NavigationTreeNodeTransfer())
                ->setNavigationNode((new NavigationNodeTransfer())
                    ->addNavigationNodeLocalizedAttribute((new NavigationNodeLocalizedAttributesTransfer())
                        ->setTitle('Node 3')
                        ->setExternalUrl('/node/3')
                        ->setFkLocale($this->idLocale))
                    ->setIsActive(true)
                    ->setWeight(0))
                ->addChild((new NavigationTreeNodeTransfer())
                    ->setNavigationNode((new NavigationNodeTransfer())
                        ->addNavigationNodeLocalizedAttribute((new NavigationNodeLocalizedAttributesTransfer())
                            ->setTitle('Node 3.1')
                            ->setExternalUrl('/node/3/1')
                            ->setFkLocale($this->idLocale))
                        ->setIsActive(true)
                        ->setWeight(0))));

        $this->assertEquals($expectedTreeTransfer->toArray(), $actualTreeTransfer->toArray(), 'Navigation tree read from database should match expected structure and order.');
    }

    /**
     * @return void
     */
    protected function setUpNavigationTree()
    {
        $navigationEntity = $this->createNavigationEntity('test-navigation-1', 'Test navigation 1', true);
        $this->navigationTransfer = new NavigationTransfer();
        $this->navigationTransfer->fromArray($navigationEntity->toArray(), true);

        $navigationNodeEntity1 = $this->createNavigationNodeEntity($navigationEntity, 10, 'Node 1', '/node/1');
        $this->createNavigationNodeEntity($navigationEntity, 20, 'Node 1.1', '/node/1/1', $navigationNodeEntity1);
        $this->createNavigationNodeEntity($navigationEntity, 10, 'Node 1.2', '/node/1/2', $navigationNodeEntity1);
        $this->createNavigationNodeEntity($navigationEntity, 0, 'Node 2', '/node/2');
        $navigationNodeEntity3 = $this->createNavigationNodeEntity($navigationEntity, 0, 'Node 3', '/node/3');
        $this->createNavigationNodeEntity($navigationEntity, 0, 'Node 3.1', '/node/3/1', $navigationNodeEntity3);
    }

    /**
     * @param string $localeName
     *
     * @return int
     */
    protected function createLocale($localeName)
    {
        $localeEntity = new SpyLocale();
        $localeEntity
            ->setLocaleName($localeName)
            ->save();

        return $localeEntity->getIdLocale();
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

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     * @param int $weight
     * @param string $title
     * @param string $externalUrl
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode|null $parentNavigationNodeEntity
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode
     */
    protected function createNavigationNodeEntity(SpyNavigation $navigationEntity, $weight, $title, $externalUrl, SpyNavigationNode $parentNavigationNodeEntity = null)
    {
        $navigationNodeEntity = new SpyNavigationNode();
        if ($parentNavigationNodeEntity) {
            $navigationNodeEntity->setParentNavigationNode($parentNavigationNodeEntity);
        }
        $navigationNodeEntity
            ->setSpyNavigation($navigationEntity)
            ->setWeight($weight)
            ->setIsActive(true)
            ->save();

        $navigationNodeLocalizedAttributesEntity = new SpyNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesEntity
            ->setSpyNavigationNode($navigationNodeEntity)
            ->setTitle($title)
            ->setExternalUrl($externalUrl)
            ->setFkLocale($this->idLocale)
            ->save();

        return $navigationNodeEntity;
    }

}
