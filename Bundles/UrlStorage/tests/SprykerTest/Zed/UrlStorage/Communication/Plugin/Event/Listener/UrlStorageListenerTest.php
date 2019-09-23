<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
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
use Spryker\Zed\Url\Dependency\UrlEvents;
use Spryker\Zed\UrlStorage\Business\UrlStorageBusinessFactory;
use Spryker\Zed\UrlStorage\Business\UrlStorageFacade;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener\RedirectStorageListener;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener\RedirectStoragePublishListener;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener\RedirectStorageUnpublishListener;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener\UrlStorageListener;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener\UrlStoragePublishListener;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener\UrlStorageUnpublishListener;
use SprykerTest\Zed\UrlStorage\UrlStorageConfigMock;

/**
 * Auto-generated group annotations
 *
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
     * @var \SprykerTest\Zed\UrlStorage\UrlStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\UrlTransfer
     */
    protected $urlTransfer;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $urlRedirectTransfer = $this->tester->haveUrlRedirect();
        $this->urlTransfer = $urlRedirectTransfer->getSource();
    }

    /**
     * @return void
     */
    public function testUrlStorageListenerStoreData(): void
    {
        // Prepare
        $this->createSpyUrlStorageQuery()->filterByUrl($this->urlTransfer->getUrl())->delete();
        $beforeCount = $this->createSpyUrlStorageQuery()->count();

        $urlStorageListener = new UrlStorageListener();
        $urlStorageListener->setFacade($this->getUrlStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->urlTransfer->getIdUrl()),
        ];

        // Act
        $urlStorageListener->handleBulk($eventTransfers, UrlEvents::URL_PUBLISH);

        // Assert
        $this->assertUrlStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testUrlStoragePublishListener(): void
    {
        // Prepare
        $this->createSpyUrlStorageQuery()->filterByUrl($this->urlTransfer->getUrl())->delete();
        $beforeCount = SpyUrlStorageQuery::create()->count();

        $urlStoragePublishListener = new UrlStoragePublishListener();
        $urlStoragePublishListener->setFacade($this->getUrlStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->urlTransfer->getIdUrl()),
        ];

        // Act
        $urlStoragePublishListener->handleBulk($eventTransfers, UrlEvents::URL_PUBLISH);

        // Assert
        $this->assertUrlStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testUrlStorageUnpublishListener(): void
    {
        // Prepare
        $urlStorageUnpublishListener = new UrlStorageUnpublishListener();
        $urlStorageUnpublishListener->setFacade($this->getUrlStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->urlTransfer->getIdUrl()),
        ];

        // Act
        $urlStorageUnpublishListener->handleBulk($eventTransfers, UrlEvents::URL_UNPUBLISH);

        // Assert
        $this->assertSame(0, SpyUrlStorageQuery::create()->filterByFkUrl($this->urlTransfer->getIdUrl())->count());
    }

    /**
     * @return void
     */
    public function testRedirectStorageListenerStoreData(): void
    {
        // Prepare
        $idRedirect = $this->prepareUrlRedirectMockData();
        $beforeCount = SpyUrlRedirectStorageQuery::create()->count();

        $redirectStorageListener = new RedirectStorageListener();
        $redirectStorageListener->setFacade($this->getUrlStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($idRedirect),
        ];

        // Act
        $redirectStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_REDIRECT_CREATE);

        // Assert
        $this->assertRedirectStorage($idRedirect, $beforeCount);
    }

    /**
     * @return void
     */
    public function testRedirectStoragePublishListener(): void
    {
        // Prepare
        $idRedirect = $this->prepareUrlRedirectMockData();
        $beforeCount = SpyUrlRedirectStorageQuery::create()->count();

        $redirectStoragePublishListener = new RedirectStoragePublishListener();
        $redirectStoragePublishListener->setFacade($this->getUrlStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($idRedirect),
        ];

        // Act
        $redirectStoragePublishListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_REDIRECT_CREATE);

        // Assert
        $this->assertRedirectStorage($idRedirect, $beforeCount);
    }

    /**
     * @return void
     */
    public function testRedirectStorageUnpublishListener(): void
    {
        // Prepare
        $idUrlRedirect = $this->prepareUrlRedirectMockData();
        $redirectStorageUnpublishListener = new RedirectStorageUnpublishListener();
        $redirectStorageUnpublishListener->setFacade($this->getUrlStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($idUrlRedirect),
        ];

        // Act
        $redirectStorageUnpublishListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_REDIRECT_DELETE);

        // Assert
        $this->assertSame(0, SpyUrlRedirectStorageQuery::create()->filterByFkUrlRedirect($idUrlRedirect)->count());
    }

    /**
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
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertUrlStorage(int $beforeCount): void
    {
        $urlStorageCount = $this->createSpyUrlStorageQuery()->count();
        $this->assertGreaterThan($beforeCount, $urlStorageCount);
        $spyUrlStorage = $this->createSpyUrlStorageQuery()->orderByIdUrlStorage()->findOneByUrl($this->urlTransfer->getUrl());
        $this->assertNotNull($spyUrlStorage);
        $data = $spyUrlStorage->getData();
        $this->assertSame($this->urlTransfer->getUrl(), $data['url']);
    }

    /**
     * @param int $idRedirect
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertRedirectStorage(int $idRedirect, int $beforeCount): void
    {
        $redirectStorageCount = SpyUrlRedirectStorageQuery::create()->count();
        $this->assertGreaterThan($beforeCount, $redirectStorageCount);
        $spyUrlStorage = SpyUrlRedirectStorageQuery::create()->orderByIdUrlRedirectStorage()->findOneByFkUrlRedirect($idRedirect);
        $data = $spyUrlStorage->getData();
        $this->assertSame('/test-redirect', $data['to_url']);
    }

    /**
     * @return int
     */
    protected function prepareUrlRedirectMockData(): int
    {
        SpyUrlQuery::create()->filterByUrl('/test-pub-sync')->delete();
        SpyUrlRedirectQuery::create()->filterByToUrl('test-redirect')->delete();

        $redirectUrl = new SpyUrlRedirect();
        $redirectUrl->setToUrl('/test-redirect');
        $redirectUrl->setStatus(301);
        $redirectUrl->save();

        $url = new SpyUrl();
        $url->setUrl('/test-pub-sync');
        $url->setFkLocale(46);
        $url->setFkResourceRedirect($redirectUrl->getIdUrlRedirect());
        $url->save();

        return $redirectUrl->getIdUrlRedirect();
    }

    /**
     * @return \Orm\Zed\UrlStorage\Persistence\SpyUrlStorageQuery
     */
    protected function createSpyUrlStorageQuery(): SpyUrlStorageQuery
    {
        return new SpyUrlStorageQuery();
    }
}
