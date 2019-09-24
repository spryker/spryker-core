<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Url\Business\Redirect;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Url\Persistence\Base\SpyUrlQuery;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Spryker\Zed\Url\Business\UrlFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Url
 * @group Business
 * @group Redirect
 * @group CreateRedirectOnUrlUpdateTest
 * Add your own group annotations below this line
 */
class CreateRedirectOnUrlUpdateTest extends Unit
{
    /**
     * @var \Spryker\Zed\Url\Business\UrlFacade
     */
    protected $urlFacade;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->urlFacade = new UrlFacade();
    }

    /**
     * @return void
     */
    public function testModifyingUrlCreatesRedirectToOldUrl()
    {
        $urlTransfer = $this->prepareTestData();

        $updatedUrlTransfer = $this->changeUrl(clone $urlTransfer, '/test-bar');

        $this->assertUrlUpdated($urlTransfer, $updatedUrlTransfer);
        $this->assertUrlRedirectCreated($urlTransfer, $updatedUrlTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function prepareTestData()
    {
        $localeEntity = $this->createLocaleEntity();
        $urlEntity = $this->createUrlEntity($localeEntity, '/test-foo');
        $urlTransfer = $this->mapEntityToTransfer($urlEntity);

        return $urlTransfer;
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocale
     */
    protected function createLocaleEntity()
    {
        $localeEntity = new SpyLocale();
        $localeEntity
            ->setLocaleName('ab_CD')
            ->save();

        return $localeEntity;
    }

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntity
     * @param string $url
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    protected function createUrlEntity(SpyLocale $localeEntity, $url)
    {
        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl($url)
            ->setFkLocale($localeEntity->getIdLocale())
            ->save();

        return $urlEntity;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function mapEntityToTransfer(SpyUrl $urlEntity)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->fromArray($urlEntity->toArray(), true);

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function changeUrl(UrlTransfer $urlTransfer, $url)
    {
        $urlTransfer->setUrl($url);

        return $this->urlFacade->updateUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $updatedUrlTransfer
     *
     * @return void
     */
    protected function assertUrlUpdated(UrlTransfer $urlTransfer, UrlTransfer $updatedUrlTransfer)
    {
        $this->assertEquals($urlTransfer->getIdUrl(), $updatedUrlTransfer->getIdUrl(), 'Ids of url should match after update.');
        $this->assertNotEquals($urlTransfer->getUrl(), $updatedUrlTransfer->getUrl(), 'Url should change after update.');
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     * @param \Generated\Shared\Transfer\UrlTransfer $updatedUrlTransfer
     *
     * @return void
     */
    protected function assertUrlRedirectCreated(UrlTransfer $urlTransfer, UrlTransfer $updatedUrlTransfer)
    {
        $urlEntity = SpyUrlQuery::create()
            ->filterByUrl($urlTransfer->getUrl())
            ->filterByFkLocale($urlTransfer->getFkLocale())
            ->findOne();

        $this->assertInstanceOf(SpyUrl::class, $urlEntity, 'New url entity should have been created with old url.');

        $redirectEntity = $urlEntity->getSpyUrlRedirect();

        $this->assertInstanceOf(SpyUrlRedirect::class, $redirectEntity, 'Url entity should have associated redirect entity.');
        $this->assertEquals($updatedUrlTransfer->getUrl(), $redirectEntity->getToUrl(), 'Redirect url should match the new url after update.');
    }
}
