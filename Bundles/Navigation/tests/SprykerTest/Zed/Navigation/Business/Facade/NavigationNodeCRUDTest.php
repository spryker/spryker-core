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
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes;
use Orm\Zed\Url\Persistence\SpyUrl;
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
 * @group NavigationNodeCRUDTest
 * Add your own group annotations below this line
 */
class NavigationNodeCRUDTest extends Unit
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
        $this->setUpNavigationTransfer();
    }

    /**
     * @return void
     */
    public function testCreateNewNavigationNodePersistsToDatabase()
    {
        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer
            ->setFkNavigation($this->navigationTransfer->getIdNavigation())
            ->setIsActive(true);

        $idLocale1 = $this->createLocale('ab_CD');
        $navigationNodeLocalizedAttributesTransfer1 = $this->createNavigationNodeLocalizedAttributesTransfer($idLocale1, 'Node 1 (ab_CD)', 'http://example.com/ab/1');

        $idLocale2 = $this->createLocale('ef_GH');
        $navigationNodeLocalizedAttributesTransfer2 = $this->createNavigationNodeLocalizedAttributesTransfer($idLocale2, 'Node 1 (ef_GH)', 'http://example.com/ef/1');

        $navigationNodeTransfer
            ->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer1)
            ->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer2);

        $navigationNodeTransfer = $this->navigationFacade->createNavigationNode($navigationNodeTransfer);

        $this->assertGreaterThan(0, $navigationNodeTransfer->getIdNavigationNode(), 'Navigation node should have ID after creation.');
        $this->assertGreaterThan(0, $navigationNodeTransfer->getNavigationNodeLocalizedAttributes()[0]->getIdNavigationNodeLocalizedAttributes(), 'Navigation node localized attributes should have ID after creation (locale: ab_CD).');
        $this->assertGreaterThan(0, $navigationNodeTransfer->getNavigationNodeLocalizedAttributes()[1]->getIdNavigationNodeLocalizedAttributes(), 'Navigation node localized attributes should have ID after creation (locale: ef_GH).');
    }

    /**
     * @return void
     */
    public function testUpdateExistingNavigationNodePersistsToDatabase()
    {
        $navigationNodeEntity = $this->createNavigationNodeEntity(false);
        $idLocale = $this->createLocale('ab_CD');
        $navigationNodeLocalizedAttributesEntity = new SpyNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesEntity
            ->setFkNavigationNode($navigationNodeEntity->getIdNavigationNode())
            ->setFkLocale($idLocale)
            ->setTitle('Node 1 (ab_CD)')
            ->setExternalUrl('http://example.com/ab/1')
            ->save();

        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer
            ->setIdNavigationNode($navigationNodeEntity->getIdNavigationNode())
            ->setIsActive(true);

        $navigationNodeLocalizedAttributesTransfer = $this->createNavigationNodeLocalizedAttributesTransfer($idLocale, 'Node 1 (ab_CD) - edited', 'http://example.com/ab/1-edited');
        $navigationNodeLocalizedAttributesTransfer->setIdNavigationNodeLocalizedAttributes($navigationNodeLocalizedAttributesEntity->getIdNavigationNodeLocalizedAttributes());
        $navigationNodeTransfer->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer);

        $actualNavigationNodeTransfer = $this->navigationFacade->updateNavigationNode($navigationNodeTransfer);

        $actualNavigationNodeEntity = $this->navigationQueryContainer
            ->queryNavigationNodeById($navigationNodeEntity->getIdNavigationNode())
            ->findOne();

        $this->assertInstanceOf(NavigationNodeTransfer::class, $actualNavigationNodeTransfer);
        $this->assertTrue($actualNavigationNodeEntity->getIsActive(), 'Navigation node should have activated after update.');
        $this->assertSame('Node 1 (ab_CD) - edited', $actualNavigationNodeEntity->getSpyNavigationNodeLocalizedAttributess()[0]->getTitle(), 'Navigation node localized attributes should have new title after update.');
        $this->assertSame('http://example.com/ab/1-edited', $actualNavigationNodeEntity->getSpyNavigationNodeLocalizedAttributess()[0]->getExternalUrl(), 'Navigation node localized attributes should have new external URL after update.');
    }

    /**
     * @return void
     */
    public function testReadExistingNavigationNodeReadsFromDatabase()
    {
        $navigationNodeEntity = $this->createNavigationNodeEntity(true);
        $idLocale = $this->createLocale('ab_CD');
        $navigationNodeLocalizedAttributesEntity = new SpyNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesEntity
            ->setFkNavigationNode($navigationNodeEntity->getIdNavigationNode())
            ->setFkLocale($idLocale)
            ->setTitle('Node 1 (ab_CD)')
            ->setExternalUrl('http://example.com/ab/1')
            ->save();

        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer->setIdNavigationNode($navigationNodeEntity->getIdNavigationNode());

        $actualNavigationNodeTransfer = $this->navigationFacade->findNavigationNode($navigationNodeTransfer);

        $this->assertInstanceOf(NavigationNodeTransfer::class, $actualNavigationNodeTransfer, 'Existing navigation node should have been found when reading from database.');
        $this->assertSame($navigationNodeEntity->getIsActive(), $actualNavigationNodeTransfer->getIsActive(), 'Navigation node should have correct data from database.');
        $this->assertCount(1, $actualNavigationNodeTransfer->getNavigationNodeLocalizedAttributes(), 'Navigation node should have 1 localized attributes read from database.');
        $this->assertSame(
            $navigationNodeLocalizedAttributesEntity->getIdNavigationNodeLocalizedAttributes(),
            $actualNavigationNodeTransfer->getNavigationNodeLocalizedAttributes()[0]->getIdNavigationNodeLocalizedAttributes(),
            'Navigation node localized attributes should have correct data from database.'
        );
    }

    /**
     * @return void
     */
    public function testDeleteExistingNavigationNodeDeletesFromDatabase()
    {
        $navigationNodeEntity = $this->createNavigationNodeEntity(true);
        $idLocale = $this->createLocale('ab_CD');
        $navigationNodeLocalizedAttributesEntity = new SpyNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesEntity
            ->setFkNavigationNode($navigationNodeEntity->getIdNavigationNode())
            ->setFkLocale($idLocale)
            ->setTitle('Node 1 (ab_CD)')
            ->setExternalUrl('http://example.com/ab/1')
            ->save();

        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer->setIdNavigationNode($navigationNodeEntity->getIdNavigationNode());

        $this->navigationFacade->deleteNavigationNode($navigationNodeTransfer);

        $actualNavigationNodeCount = $this->navigationQueryContainer
            ->queryNavigationNodeById($navigationNodeEntity->getIdNavigationNode())
            ->count();

        $actualNavigationNodeLocalizedAttributesCount = $this->navigationQueryContainer
            ->queryNavigationNodeLocalizedAttributesById($navigationNodeLocalizedAttributesEntity->getIdNavigationNodeLocalizedAttributes())
            ->count();

        $this->assertSame(0, $actualNavigationNodeCount, 'Navigation entity should not be in database after deletion.');
        $this->assertSame(0, $actualNavigationNodeLocalizedAttributesCount, 'Navigation entity localized attributes should not be in database after deletion.');
    }

    /**
     * @return void
     */
    public function testDetachUrlFromNavigationNodesPersistsChangedToDatabase()
    {
        $idLocale = $this->createLocale('ab_CD');
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl('/detach/url/from/navigation/test')
            ->setFkLocale($idLocale)
            ->save();

        $navigationNodeEntity = $this->createNavigationNodeEntity(true);
        $navigationNodeLocalizedAttributesEntity = new SpyNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesEntity
            ->setFkNavigationNode($navigationNodeEntity->getIdNavigationNode())
            ->setFkLocale($idLocale)
            ->setTitle('Node 1 (ab_CD)')
            ->setFkUrl($urlEntity->getIdUrl())
            ->save();

        $urlTransfer = new UrlTransfer();
        $urlTransfer->setIdUrl($urlEntity->getIdUrl());

        $this->navigationFacade->detachUrlFromNavigationNodes($urlTransfer);

        $navigationNodeEntity->reload();
        $this->assertFalse($navigationNodeEntity->getIsActive(), 'Detached navigation entity should be set to inactive.');

        $actualCount = $this->navigationQueryContainer->queryNavigationNodeByFkUrl($urlEntity->getIdUrl())->count();
        $this->assertSame(0, $actualCount, 'No navigation entity should exist with previously detached URL entity.');
    }

    /**
     * @return void
     */
    protected function setUpNavigationTransfer()
    {
        $navigationEntity = $this->createNavigationEntity('test-navigation-1', 'Test navigation 1', true);
        $this->navigationTransfer = new NavigationTransfer();
        $this->navigationTransfer->fromArray($navigationEntity->toArray(), true);
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
     * @param int $idLocale
     * @param string $nodeTitle
     * @param string $externalUrl
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    protected function createNavigationNodeLocalizedAttributesTransfer($idLocale, $nodeTitle, $externalUrl)
    {
        $navigationNodeLocalizedAttributesTransfer = new NavigationNodeLocalizedAttributesTransfer();
        $navigationNodeLocalizedAttributesTransfer
            ->setFkLocale($idLocale)
            ->setTitle($nodeTitle)
            ->setExternalUrl($externalUrl);

        return $navigationNodeLocalizedAttributesTransfer;
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
     * @param bool $isActive
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode
     */
    protected function createNavigationNodeEntity($isActive)
    {
        $navigationNodeEntity = new SpyNavigationNode();
        $navigationNodeEntity
            ->setFkNavigation($this->navigationTransfer->getIdNavigation())
            ->setIsActive($isActive)
            ->save();

        return $navigationNodeEntity;
    }
}
