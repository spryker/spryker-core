<?php

namespace Functional\SprykerFeature\Zed\Url;

use Codeception\TestCase\Test;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Locale\Business\LocaleFacade;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainer;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use SprykerFeature\Zed\Url\Persistence\UrlQueryContainer;
use SprykerFeature\Zed\Url\Persistence\UrlQueryContainerInterface;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Url
 * @group UrlFacadeTest
 */
class UrlFacadeTest extends Test
{
    /**
     * @var UrlFacade
     */
    protected $urlFacade;

    /**
     * @var UrlQueryContainerInterface
     */
    protected $urlQueryContainer;

    /**
     * @var TouchQueryContainer
     */
    protected $touchQueryContainer;

    /**
     * @var LocaleFacade
     */
    protected $localeFacade;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();
        $this->urlFacade = new UrlFacade(new Factory('Url'), $this->locator);
        $this->localeFacade = new LocaleFacade(new Factory('Locale'), $this->locator);
        $this->urlQueryContainer = new UrlQueryContainer(new \SprykerEngine\Zed\Kernel\Persistence\Factory('Url'), $this->locator);
        $this->touchQueryContainer = new TouchQueryContainer(new \SprykerEngine\Zed\Kernel\Persistence\Factory('Touch'), $this->locator);
    }

    public function testCreateUrlInsertsAndReturnsSomething()
    {
        $urlQuery = $this->urlQueryContainer->queryUrls();
        $locale = $this->localeFacade->createLocale('CBCDE');
        $redirect = $this->urlFacade->createRedirect('/some/url/like/string2');

        $urlCountBeforeCreation = $urlQuery->count();
        $newUrl = $this->urlFacade->createUrl('/some/url/like/string', $locale, 'redirect', $redirect->getIdRedirect());
        $urlCountAfterCreation = $urlQuery->count();

        $this->assertTrue($urlCountAfterCreation > $urlCountBeforeCreation);

        $this->assertNotNull($newUrl->getIdUrl());
    }

    public function testSaveUrlInsertsAndReturnsSomethingOnCreate()
    {
        $urlQuery = $this->urlQueryContainer->queryUrls();
        $redirect = $this->urlFacade->createRedirect('/YetSomeOtherPageUrl2');

        $url = new \Generated\Shared\Transfer\UrlUrlTransfer();
        $url->setUrl('/YetSomeOtherPageUrl');
        $url->setFkLocale($this->localeFacade->createLocale('QWERT')->getIdLocale());
        $url->setResource('redirect', $redirect->getIdRedirect());

        $urlCountBeforeCreation = $urlQuery->count();
        $url = $this->urlFacade->saveUrl($url);
        $urlCountAfterCreation = $urlQuery->count();

        $this->assertTrue($urlCountAfterCreation > $urlCountBeforeCreation);

        $this->assertNotNull($url->getIdUrl());
    }

    public function testSaveUrlUpdatesSomething()
    {
        $url = new \Generated\Shared\Transfer\UrlUrlTransfer();
        $urlQuery = $this->urlQueryContainer->queryUrl('/SoManyPageUrls');
        $redirect1 = $this->urlFacade->createRedirect('/SoManyPageUrls2');
        $redirect2 = $this->urlFacade->createRedirect('/SoManyPageUrls3');

        $url->setUrl('/SoManyPageUrls');
        $url->setFkLocale($this->localeFacade->createLocale('WERTZ')->getIdLocale());
        $url->setResource('redirect', $redirect1->getIdRedirect());

        $url = $this->urlFacade->saveUrl($url);

        $this->assertEquals($redirect1->getIdRedirect(), $urlQuery->findOne()->getResourceId());

        $url->setResource('redirect', $redirect2->getIdRedirect());
        $this->urlFacade->saveUrl($url);

        $this->assertEquals($redirect2->getIdRedirect(), $urlQuery->findOne()->getResourceId());
    }

    public function testHasUrlId()
    {
        $locale = $this->localeFacade->createLocale('UNIXA');
        $redirect = $this->urlFacade->createRedirect('/SoManyPageUrls4');

        $idPageUrl = $this->urlFacade->createUrl('/abcdefg', $locale, 'redirect', $redirect->getIdRedirect())->getIdUrl();

        $this->assertTrue($this->urlFacade->hasUrlId($idPageUrl));
    }

    public function testGetUrlByPath()
    {
        $locale = $this->localeFacade->createLocale('DFGHE');
        $redirect = $this->urlFacade->createRedirect('/SoManyPageUrls5');

        $this->urlFacade->createUrl('/someOtherPageUrl', $locale, 'redirect', $redirect->getIdRedirect());

        $url = $this->urlFacade->getUrlByPath('/someOtherPageUrl');
        $this->assertNotNull($url);

        $this->assertEquals('/someOtherPageUrl', $url->getUrl());
        $this->assertEquals($locale->getIdLocale(), $url->getFkLocale());
    }

    public function testGetUrlById()
    {
        $locale = $this->localeFacade->createLocale('DFGHX');
        $redirect = $this->urlFacade->createRedirect('/SoManyPageUrls5');

        $id = $this->urlFacade->createUrl('/someOtherPageUrl2', $locale, 'redirect', $redirect->getIdRedirect())->getIdUrl();

        $url = $this->urlFacade->getUrlById($id);
        $this->assertNotNull($url);

        $this->assertEquals('/someOtherPageUrl2', $url->getUrl());
        $this->assertEquals($locale->getIdLocale(), $url->getFkLocale());
    }

    public function testTouchUrlActive()
    {
        $locale = $this->localeFacade->createLocale('ABCDE');
        $redirect = $this->urlFacade->createRedirect('/ARedirectUrl');

        $idUrl = $this->urlFacade->createUrl('/aPageUrl', $locale, 'redirect', $redirect->getIdRedirect())->getIdUrl();

        $touchQuery = $this->touchQueryContainer->queryTouchEntry('url', $idUrl);
        $this->assertEquals(0, $touchQuery->count());

        $this->urlFacade->touchUrlActive($idUrl);

        $this->assertEquals(1, $touchQuery->count());
    }

    public function testCreateRedirectInsertsAndReturnsSomething()
    {
        $redirectQuery = $this->urlQueryContainer->queryRedirects();

        $redirectCountBeforeCreation = $redirectQuery->count();
        $newRedirect = $this->urlFacade->createRedirect('/this/other/url');
        $redirectCountAfterCreation = $redirectQuery->count();

        $this->assertTrue($redirectCountAfterCreation > $redirectCountBeforeCreation);

        $this->assertNotNull($newRedirect->getIdRedirect());
    }

    public function testSaveRedirectInsertsAndReturnsSomethingOnCreate()
    {
        $redirect = new \Generated\Shared\Transfer\UrlRedirectTransfer();
        $redirect->setToUrl('/pageToUrl');

        $redirectQuery = $this->urlQueryContainer->queryRedirects();

        $redirectCountBeforeCreation = $redirectQuery->count();
        $redirect = $this->urlFacade->saveRedirect($redirect);
        $redirectCountAfterCreation = $redirectQuery->count();

        $this->assertTrue($redirectCountAfterCreation > $redirectCountBeforeCreation);

        $this->assertNotNull($redirect->getIdRedirect());
    }

    public function testSaveRedirectUpdatesSomething()
    {
        $redirect = new \Generated\Shared\Transfer\UrlRedirectTransfer();
        $redirect->setToUrl('/pageToUrl2');

        $redirect = $this->urlFacade->saveRedirect($redirect);

        $redirectQuery = $this->urlQueryContainer->queryRedirectById($redirect->getIdRedirect());

        $this->assertEquals('/pageToUrl2', $redirectQuery->findOne()->getToUrl());

        $redirect->setToUrl('/redirectingToUrl');
        $this->urlFacade->saveRedirect($redirect);

        $this->assertEquals('/redirectingToUrl', $redirectQuery->findOne()->getToUrl());
    }
}
