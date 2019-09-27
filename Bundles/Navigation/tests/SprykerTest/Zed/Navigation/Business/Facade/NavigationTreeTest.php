<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Navigation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
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
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Navigation
 * @group Business
 * @group Facade
 * @group NavigationTreeTest
 * Add your own group annotations below this line
 */
class NavigationTreeTest extends Unit
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
    protected $idLocale1;

    /**
     * @var int
     */
    protected $idLocale2;

    /**
     * @var \Generated\Shared\Transfer\NavigationTransfer
     */
    protected $navigationTransfer;

    /**
     * @var array
     */
    protected $navigationNodeIdCache = [];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->navigationFacade = new NavigationFacade();
        $this->navigationQueryContainer = new NavigationQueryContainer();
        $this->idLocale1 = $this->createLocale('ab_CD');
        $this->idLocale2 = $this->createLocale('ef_GH');
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

        // Assert navigation base data
        $this->assertSame('Test navigation 1', $actualTreeTransfer->getNavigation()->getName(), 'Navigation name should match expected data.');

        // Assert root node count
        $this->assertCount(3, $actualTreeTransfer->getNodes(), 'Navigation tree should contain expected number of root nodes.');

        // Assert root node order
        $this->assertSame('Node 1', $actualTreeTransfer->getNodes()[0]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 1/3 should have expected title at position 1.');
        $this->assertSame('Node 2', $actualTreeTransfer->getNodes()[1]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 2/3 should have expected title at position 2.');
        $this->assertSame('Node 3', $actualTreeTransfer->getNodes()[2]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 3/3 should have expected title at position 3.');

        // Assert root node children count
        $this->assertCount(2, $actualTreeTransfer->getNodes()[0]->getChildren(), 'Node 1/3 should contain expected number of nodes.');
        $this->assertCount(0, $actualTreeTransfer->getNodes()[1]->getChildren(), 'Node 2/3 should contain expected number of nodes.');
        $this->assertCount(1, $actualTreeTransfer->getNodes()[2]->getChildren(), 'Node 3/3 should contain expected number of nodes.');

        // Assert "Node 1" children data and order
        $node1 = $actualTreeTransfer->getNodes()[0];
        $this->assertSame('Node 1.1', $node1->getChildren()[0]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 1.1/2 should have expected title at position 1.');
        $this->assertSame('Node 1.2', $node1->getChildren()[1]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 1.2/2 should have expected title at position 2.');

        // Assert "Node 3" children data
        $node3 = $actualTreeTransfer->getNodes()[2];
        $this->assertSame('Node 3.1', $node3->getChildren()[0]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 3.1/1 should have expected title.');

        // Assert "Node 3.1" children count
        $this->assertCount(1, $node3->getChildren(), 'Node 3.1/1 should contain expected number of nodes.');

        // Assert "Node 3.1" children data
        $node3_1 = $node3->getChildren()[0];
        $this->assertSame('Node 3.1.1', $node3_1->getChildren()[0]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 3.1.1/1 should have expected title.');
    }

    /**
     * @return void
     */
    public function testReadExistingNavigationTreeWithoutLocaleConstraintReturnsAllLocalizedAttributes()
    {
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($this->navigationTransfer->getIdNavigation());
        $actualTreeTransfer = $this->navigationFacade->findNavigationTree($navigationTransfer);

        // Assert localized attribute count
        $this->assertCount(2, $actualTreeTransfer->getNodes()[0]->getNavigationNode()->getNavigationNodeLocalizedAttributes(), 'Nodes should have expected number of localized attributes returned.');
    }

    /**
     * @return void
     */
    public function testReadExistingNavigationTreeWithLocaleConstraintReturnsExpectedLocalizedAttributes()
    {
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($this->navigationTransfer->getIdNavigation());
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setIdLocale($this->idLocale1);

        $actualTreeTransfer = $this->navigationFacade->findNavigationTree($navigationTransfer, $localeTransfer);

        // Assert localized attribute count
        $this->assertCount(1, $actualTreeTransfer->getNodes()[0]->getNavigationNode()->getNavigationNodeLocalizedAttributes(), 'Nodes should have expected number of localized attributes returned.');
    }

    /**
     * @return void
     */
    public function testUpdateNavigationTreeHierarchyPersistsToDatabase()
    {
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($this->navigationTransfer->getIdNavigation());

        $navigationTreeTransfer = new NavigationTreeTransfer();
        $navigationTreeTransfer->setNavigation($navigationTransfer);

        // Node 1 -> position 2
        $navigationTreeNodeTransfer1 = new NavigationTreeNodeTransfer();
        $navigationNodeTransfer1 = new NavigationNodeTransfer();
        $navigationNodeTransfer1
            ->setIdNavigationNode($this->navigationNodeIdCache['Node 1'])
            ->setPosition(2);
        $navigationTreeNodeTransfer1->setNavigationNode($navigationNodeTransfer1);

        // Node 1.1 -> position 1.2
        $navigationTreeNodeTransfer1_1 = new NavigationTreeNodeTransfer();
        $navigationNodeTransfer1_1 = new NavigationNodeTransfer();
        $navigationNodeTransfer1_1
            ->setIdNavigationNode($this->navigationNodeIdCache['Node 1.1'])
            ->setPosition(2);
        $navigationTreeNodeTransfer1_1->setNavigationNode($navigationNodeTransfer1_1);
        $navigationTreeNodeTransfer1->addChild($navigationTreeNodeTransfer1_1);

        // Node 1.2 -> position 1.1
        $navigationTreeNodeTransfer1_2 = new NavigationTreeNodeTransfer();
        $navigationNodeTransfer1_2 = new NavigationNodeTransfer();
        $navigationNodeTransfer1_2
            ->setIdNavigationNode($this->navigationNodeIdCache['Node 1.2'])
            ->setPosition(1);
        $navigationTreeNodeTransfer1_2->setNavigationNode($navigationNodeTransfer1_2);
        $navigationTreeNodeTransfer1->addChild($navigationTreeNodeTransfer1_2);

        $navigationTreeTransfer->addNode($navigationTreeNodeTransfer1);

        // Node 2 -> position 1
        $navigationTreeNodeTransfer2 = new NavigationTreeNodeTransfer();
        $navigationNodeTransfer2 = new NavigationNodeTransfer();
        $navigationNodeTransfer2
            ->setIdNavigationNode($this->navigationNodeIdCache['Node 2'])
            ->setPosition(1);
        $navigationTreeNodeTransfer2->setNavigationNode($navigationNodeTransfer2);

        // Node 3 -> move under Node 2 along with all its children
        $navigationTreeNodeTransfer3 = new NavigationTreeNodeTransfer();
        $navigationNodeTransfer3 = new NavigationNodeTransfer();
        $navigationNodeTransfer3->setIdNavigationNode($this->navigationNodeIdCache['Node 3']);
        $navigationTreeNodeTransfer3->setNavigationNode($navigationNodeTransfer3);

        $navigationTreeNodeTransfer2->addChild($navigationTreeNodeTransfer3);

        $navigationTreeTransfer->addNode($navigationTreeNodeTransfer2);

        // Update navigation tree hierarchy
        $this->navigationFacade->updateNavigationTreeHierarchy($navigationTreeTransfer);

        // Read updated navigation tree
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($this->navigationTransfer->getIdNavigation());

        $actualTreeTransfer = $this->navigationFacade->findNavigationTree($navigationTransfer);

        // Assert root node count
        $this->assertCount(2, $actualTreeTransfer->getNodes(), 'Navigation tree should contain expected number of root nodes.');

        // Assert root node order
        $this->assertSame('Node 2', $actualTreeTransfer->getNodes()[0]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 2/3 should have expected title at position 1.');
        $this->assertSame('Node 1', $actualTreeTransfer->getNodes()[1]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 1/3 should have expected title at position 2.');

        // Assert "Node 1" children new order
        $node1 = $actualTreeTransfer->getNodes()[1];
        $this->assertSame('Node 1.2', $node1->getChildren()[0]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 1.2/2 should have expected title at position 1.');
        $this->assertSame('Node 1.1', $node1->getChildren()[1]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 1.1/2 should have expected title at position 2.');

        // Assert "Node 3" moved under "Node 2"
        $node2 = $actualTreeTransfer->getNodes()[0];
        $this->assertSame('Node 3', $node2->getChildren()[0]->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle(), 'Node 3 should have been moved under Node 2.');

        // Assert "Node 3" still have its children
        $node3 = $node2->getChildren()[0];
        $this->assertCount(1, $node3->getChildren(), 'Node 3.1/1 should contain expected number of nodes.');
        $this->assertCount(1, $node3->getChildren()[0]->getChildren(), 'Node 3.1.1/1 should contain expected number of nodes.');
    }

    /**
     * @return void
     */
    protected function setUpNavigationTree()
    {
        $navigationEntity = $this->createNavigationEntity('test-navigation-1', 'Test navigation 1', true);
        $this->navigationTransfer = new NavigationTransfer();
        $this->navigationTransfer->fromArray($navigationEntity->toArray(), true);

        $this->createNavigationNodeEntity($navigationEntity, 2, 'Node 2', '/node/2');

        $navigationNodeEntity1 = $this->createNavigationNodeEntity($navigationEntity, 1, 'Node 1', '/node/1');
        $this->createNavigationNodeEntity($navigationEntity, 2, 'Node 1.2', '/node/1/2', $navigationNodeEntity1);
        $this->createNavigationNodeEntity($navigationEntity, 1, 'Node 1.1', '/node/1/1', $navigationNodeEntity1);

        $navigationNodeEntity3 = $this->createNavigationNodeEntity($navigationEntity, null, 'Node 3', '/node/3');
        $navigationNodeEntity3_1 = $this->createNavigationNodeEntity($navigationEntity, null, 'Node 3.1', '/node/3/1', $navigationNodeEntity3);
        $this->createNavigationNodeEntity($navigationEntity, null, 'Node 3.1.1', '/node/3/1/1', $navigationNodeEntity3_1);
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
     * @param int $position
     * @param string $title
     * @param string $externalUrl
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode|null $parentNavigationNodeEntity
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode
     */
    protected function createNavigationNodeEntity(SpyNavigation $navigationEntity, $position, $title, $externalUrl, ?SpyNavigationNode $parentNavigationNodeEntity = null)
    {
        $navigationNodeEntity = new SpyNavigationNode();
        if ($parentNavigationNodeEntity) {
            $navigationNodeEntity->setParentNavigationNode($parentNavigationNodeEntity);
        }
        $navigationNodeEntity
            ->setSpyNavigation($navigationEntity)
            ->setPosition($position)
            ->setIsActive(true)
            ->save();

        $navigationNodeLocalizedAttributesEntity1 = new SpyNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesEntity1
            ->setSpyNavigationNode($navigationNodeEntity)
            ->setTitle($title)
            ->setExternalUrl($externalUrl)
            ->setFkLocale($this->idLocale1)
            ->save();

        $navigationNodeLocalizedAttributesEntity2 = new SpyNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesEntity2
            ->setSpyNavigationNode($navigationNodeEntity)
            ->setTitle($title)
            ->setExternalUrl($externalUrl)
            ->setFkLocale($this->idLocale2)
            ->save();

        $this->navigationNodeIdCache[$title] = $navigationNodeEntity->getIdNavigationNode();

        return $navigationNodeEntity;
    }
}
