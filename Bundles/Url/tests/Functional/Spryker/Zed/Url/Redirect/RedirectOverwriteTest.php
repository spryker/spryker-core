<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Url\Redirect;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Url\Persistence\Base\SpyUrlQuery;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Url\Business\UrlFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Url
 * @group Redirect
 * @group RedirectOverwriteTest
 */
class RedirectOverwriteTest extends Test
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
    public function testOverwritingRedirectedUrlsWithNewNonRedirectUrlsShouldBePossible()
    {
        $localeTransfer = $this->createLocaleEntity();
        $redirectedUrlEntity = $this->createUrlRedirectEntity('/test/source/url', '/test/redirected/url', $localeTransfer->getIdLocale());
        $urlTransfer = $this->urlFacade->createUrl($this->createUrlTransfer('/test/source/url', $localeTransfer->getIdLocale()));

        $this->assertFalse($this->hasUrlRedirect($redirectedUrlEntity), 'URL redirect entity should not exist after new entity with same URL got created.');
        $this->assertNotNull($urlTransfer->getIdUrl(), 'New URL entity with previously redirected URL should have been created.');
    }

    /**
     * @expectedException \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return void
     */
    public function testOverwritingRedirectedUrlsWithNewRedirectUrlsShouldNotBePossible()
    {
        $localeTransfer = $this->createLocaleEntity();
        $this->createUrlRedirectEntity('/test/source/url', '/test/redirected/url', $localeTransfer->getIdLocale());
        $this->urlFacade->createUrlRedirect($this->createUrlRedirectTransfer('/test/source/url', '/test/redirected/url2', $localeTransfer->getIdLocale()));
    }

    /**
     * @return void
     */
    public function testOverwritingRedirectedUrlByUpdatingExistingNonRedirectUrlsShouldBePossible()
    {
        $localeTransfer = $this->createLocaleEntity();
        $redirectedUrlEntity = $this->createUrlRedirectEntity('/test/source/url', '/test/redirected/url', $localeTransfer->getIdLocale());
        $urlEntity = $this->createUrlEntity('/test/redirected/url', $localeTransfer->getIdLocale());

        $updateUrlTransfer = $this->createUrlTransfer('/test/source/url', $localeTransfer->getIdLocale());
        $updateUrlTransfer->setIdUrl($urlEntity->getIdUrl());

        $urlTransfer = $this->urlFacade->updateUrl($updateUrlTransfer);

        $this->assertFalse($this->hasUrlRedirect($redirectedUrlEntity), 'URL redirect entity should not exist after entity with same URL got updated.');
        $this->assertSame($updateUrlTransfer->getUrl(), $urlTransfer->getUrl(), 'Updated URL entity with previously redirected URL should have been saved.');
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function createLocaleEntity()
    {
        $localeEntity = new SpyLocale();
        $localeEntity
            ->setLocaleName('ab_CD')
            ->save();

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($localeEntity->toArray(), true);

        return $localeTransfer;
    }

    /**
     * @param string $url
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer($url, $idLocale)
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer
            ->setUrl($url)
            ->setFkLocale($idLocale);

        return $urlTransfer;
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
     * @param string $source
     * @param string $target
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    protected function createUrlRedirectEntity($source, $target, $idLocale)
    {
        $redirectEntity = new SpyUrlRedirect();
        $redirectEntity
            ->setToUrl($target)
            ->save();

        $urlEntity = new SpyUrl();
        $urlEntity
            ->setUrl($source)
            ->setFkResourceRedirect($redirectEntity->getIdUrlRedirect())
            ->setFkLocale($idLocale)
            ->save();

        return $urlEntity;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return bool
     */
    protected function hasUrlRedirect(SpyUrl $urlEntity)
    {
        $count = SpyUrlQuery::create()
            ->useSpyUrlRedirectQuery()
                ->filterByIdUrlRedirect(null, Criteria::ISNOTNULL)
            ->endUse()
            ->filterByUrl($urlEntity->getUrl())
            ->count();

        return $count > 0;
    }

    /**
     * @param string $sourceUrl
     * @param string $targetUrl
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    protected function createUrlRedirectTransfer($sourceUrl, $targetUrl, $idLocale)
    {
        $sourceUrlTransfer = new UrlTransfer();
        $sourceUrlTransfer
            ->setUrl($sourceUrl)
            ->setFkLocale($idLocale);

        $urlRedirectTransfer = new UrlRedirectTransfer();
        $urlRedirectTransfer
            ->setSource($sourceUrlTransfer)
            ->setToUrl($targetUrl)
            ->setStatus(123);

        return $urlRedirectTransfer;
    }

}
