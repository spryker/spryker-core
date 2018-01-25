<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\UrlStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Orm\Zed\Url\Persistence\SpyUrlRedirectQuery;
use Orm\Zed\UrlStorage\Persistence\SpyUrlRedirectStorageQuery;
use Orm\Zed\UrlStorage\Persistence\SpyUrlStorageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Silex\Application;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;
use Spryker\Zed\Url\Dependency\UrlEvents;
use Spryker\Zed\UrlStorage\Business\UrlStorageBusinessFactory;
use Spryker\Zed\UrlStorage\Business\UrlStorageFacade;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener\RedirectStorageListener;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener\UrlStorageListener;
use SprykerTest\Zed\UrlStorage\UrlStorageConfigMock;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group UrlStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group UrlStorageListenerTest
 * Add your own group annotations below this line
 */
class UrlStorageListenerTest extends Unit
{
    /**
     * @return void
     */
    protected function setUp()
    {
        Propel::disableInstancePooling();
        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());
    }

    /**
     * @return void
     */
    protected function tearDown()
    {
        SpyUrlStorageQuery::create()->deleteAll();
        SpyUrlRedirectStorageQuery::create()->deleteAll();
        SpyUrlQuery::create()->filterByFkResourceRedirect(null, Criteria::NOT_EQUAL);
        SpyUrlRedirectQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function testUrlStorageListenerStoreData()
    {
        $urlStorageCount = SpyUrlStorageQuery::create()->count();
        $this->assertSame(0, $urlStorageCount);

        $urlStorageListener = new UrlStorageListener();
        $urlStorageListener->setFacade($this->getUrlStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $urlStorageListener->handleBulk($eventTransfers, UrlEvents::URL_PUBLISH);

        // Assert
        $this->assertUrlStorage();
    }

    /**
     * @return void
     */
    public function testRedirectStorageListenerStoreData()
    {
        $idRedirect = $this->prepareUrlRedirectMockData();

        $redirectStorageCount = SpyUrlRedirectStorageQuery::create()->count();
        $this->assertSame(0, $redirectStorageCount);

        $redirectStorageListener = new RedirectStorageListener();
        $redirectStorageListener->setFacade($this->getUrlStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($idRedirect),
        ];
        $redirectStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_REDIRECT_CREATE);

        // Assert
        $this->assertRedirectStorage();
    }

    /**

    /**
     *
     * @return \Spryker\Zed\UrlStorage\Business\UrlStorageFacade
     */
    protected function getUrlStorageFacade()
    {
        $factory = new UrlStorageBusinessFactory();
        $factory->setConfig(new UrlStorageConfigMock());

        $facade = new UrlStorageFacade();
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return void
     */
    protected function assertUrlStorage()
    {
        $urlStorageCount = SpyUrlStorageQuery::create()->count();
        $this->assertEquals(1, $urlStorageCount);
        $spyUrlStorage = SpyUrlStorageQuery::create()->findOne();
        $this->assertEquals(1, $spyUrlStorage->getFkUrl());
        $data = $spyUrlStorage->getData();
        $this->assertEquals('/de', $data['url']);
    }

    /**
     * @return void
     */
    protected function assertRedirectStorage()
    {
        $redirectStorageCount = SpyUrlRedirectStorageQuery::create()->count();
        $this->assertEquals(1, $redirectStorageCount);
        $spyUrlStorage = SpyUrlRedirectStorageQuery::create()->findOne();
        $data = $spyUrlStorage->getData();
        $this->assertEquals('/test-redirect', $data['to_url']);
    }

    /**
     * @return int
     */
    protected function prepareUrlRedirectMockData()
    {
        $redirectUrl = new SpyUrlRedirect();
        $redirectUrl->setToUrl('/test-redirect');
        $redirectUrl->setStatus(301);
        $redirectUrl->save();

        $url = new SpyUrl();
        $url->setUrl('/test');
        $url->setFkLocale(46);
        $url->setFkResourceRedirect($redirectUrl->getIdUrlRedirect());
        $url->save();

        return $redirectUrl->getIdUrlRedirect();
    }
}
