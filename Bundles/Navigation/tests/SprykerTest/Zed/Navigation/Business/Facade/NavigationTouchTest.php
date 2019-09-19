<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Navigation\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes;
use Orm\Zed\Url\Persistence\SpyUrl;
use Spryker\Shared\Navigation\NavigationConfig;
use Spryker\Zed\Navigation\Business\NavigationFacade;
use Spryker\Zed\Touch\Persistence\TouchQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Navigation
 * @group Business
 * @group Facade
 * @group NavigationTouchTest
 * Add your own group annotations below this line
 */
class NavigationTouchTest extends Unit
{
    /**
     * @var \Spryker\Zed\Navigation\Business\NavigationFacade
     */
    protected $navigationFacade;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->navigationFacade = new NavigationFacade();
        $this->touchQueryContainer = new TouchQueryContainer();
    }

    /**
     * @return void
     */
    public function testTouchNavigationByUrlTouchesExpectedEntity()
    {
        $navigationEntity = $this->createNavigationEntity('Test navigation 1', 'test-navigation-1', true);
        $navigationNodeEntity = $this->createNavigationNodeEntity($navigationEntity->getIdNavigation());
        $idLocale = $this->createLocale('ab_CD');
        $urlEntity = $this->createUrlEntity('/test/navigation/url/1', $idLocale);
        $this->createNavigationNodeLocalizedAttributes('Node 1 (ab_CD)', $urlEntity->getIdUrl(), $navigationNodeEntity->getIdNavigationNode(), $idLocale);

        $urlTransfer = new UrlTransfer();
        $urlTransfer->fromArray($urlEntity->toArray(), true);

        $this->assertNull($this->findNavigationTouchEntries($navigationEntity->getIdNavigation()), 'Navigation entry should not have been touched before execution.');
        $this->navigationFacade->touchNavigationByUrl($urlTransfer);
        $this->assertNotNull($this->findNavigationTouchEntries($navigationEntity->getIdNavigation()), 'Navigation entry should have been touched after execution.');
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
     * @param int $idNavigation
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode
     */
    protected function createNavigationNodeEntity($idNavigation)
    {
        $navigationNodeEntity = new SpyNavigationNode();
        $navigationNodeEntity
            ->setFkNavigation($idNavigation)
            ->setIsActive(false)
            ->save();

        return $navigationNodeEntity;
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
     * @param string $title
     * @param int $idUrl
     * @param int $idNavigationNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes
     */
    protected function createNavigationNodeLocalizedAttributes($title, $idUrl, $idNavigationNode, $idLocale)
    {
        $navigationNodeLocalizedAttributesEntity = new SpyNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesEntity
            ->setFkNavigationNode($idNavigationNode)
            ->setFkLocale($idLocale)
            ->setTitle($title)
            ->setFkUrl($idUrl)
            ->save();

        return $navigationNodeLocalizedAttributesEntity;
    }

    /**
     * @param string $url
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    protected function createUrlEntity($url, $idLocale)
    {
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl($url)
            ->setFkLocale($idLocale)
            ->save();

        return $urlEntity;
    }

    /**
     * @param int $idNavigation
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouch|null
     */
    protected function findNavigationTouchEntries($idNavigation)
    {
        return $this->touchQueryContainer
            ->queryTouchEntriesByItemTypeAndItemIds(NavigationConfig::RESOURCE_TYPE_NAVIGATION_MENU, [$idNavigation])
            ->findOne();
    }
}
