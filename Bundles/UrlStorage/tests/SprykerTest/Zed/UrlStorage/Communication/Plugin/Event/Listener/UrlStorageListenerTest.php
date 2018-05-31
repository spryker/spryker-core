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
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
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
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }
    }

    /**
     * @return void
     */
    public function testUrlStorageListenerStoreData()
    {
        SpyUrlStorageQuery::create()->filterByFkUrl(1)->delete();
        $beforeCount = SpyUrlStorageQuery::create()->count();

        $urlStorageListener = new UrlStorageListener();
        $urlStorageListener->setFacade($this->getUrlStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId(1),
        ];
        $urlStorageListener->handleBulk($eventTransfers, UrlEvents::URL_PUBLISH);

        // Assert
        $this->assertUrlStorage($beforeCount);
    }

    /**
     * @return void
     */
    public function testRedirectStorageListenerStoreData()
    {
        $idRedirect = $this->prepareUrlRedirectMockData();
        $beforeCount = SpyUrlRedirectStorageQuery::create()->count();

        $redirectStorageListener = new RedirectStorageListener();
        $redirectStorageListener->setFacade($this->getUrlStorageFacade());

        $eventTransfers = [
            (new EventEntityTransfer())->setId($idRedirect),
        ];
        $redirectStorageListener->handleBulk($eventTransfers, UrlEvents::ENTITY_SPY_URL_REDIRECT_CREATE);

        // Assert
        $this->assertRedirectStorage($idRedirect, $beforeCount);
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
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertUrlStorage($beforeCount)
    {
        $urlStorageCount = SpyUrlStorageQuery::create()->count();
        $this->assertSame($beforeCount + 1, $urlStorageCount);
        $spyUrlStorage = SpyUrlStorageQuery::create()->orderByIdUrlStorage()->findOneByFkUrl(1);
        $this->assertNotNull($spyUrlStorage);
        $data = $spyUrlStorage->getData();
        $this->assertSame('/de', $data['url']);
    }

    /**
     * @param int $idRedirect
     * @param int $beforeCount
     *
     * @return void
     */
    protected function assertRedirectStorage($idRedirect, $beforeCount)
    {
        $redirectStorageCount = SpyUrlRedirectStorageQuery::create()->count();
        $this->assertSame($beforeCount + 1, $redirectStorageCount);
        $spyUrlStorage = SpyUrlRedirectStorageQuery::create()->orderByIdUrlRedirectStorage()->findOneByFkUrlRedirect($idRedirect);
        $data = $spyUrlStorage->getData();
        $this->assertSame('/test-redirect', $data['to_url']);
    }

    /**
     * @return int
     */
    protected function prepareUrlRedirectMockData()
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
}
