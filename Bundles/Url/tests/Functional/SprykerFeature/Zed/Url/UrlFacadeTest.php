<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Url;

use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Persistence\Factory;
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
class UrlFacadeTest extends AbstractFunctionalTest
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

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();
        $this->urlFacade = $this->getFacade();
        $this->localeFacade = $this->getFacade('SprykerEngine', 'Locale');
        $this->urlQueryContainer = new UrlQueryContainer(new Factory('Url'), $this->locator);
        $this->touchQueryContainer = new TouchQueryContainer(new Factory('Touch'), $this->locator);
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function testSaveUrlInsertsAndReturnsSomethingOnCreate()
    {
        $urlQuery = $this->urlQueryContainer->queryUrls();
        $redirect = $this->urlFacade->createRedirect('/YetSomeOtherPageUrl2');

        $url = new UrlTransfer();
        $url
            ->setUrl('/YetSomeOtherPageUrl')
            ->setFkLocale($this->localeFacade->createLocale('QWERT')->getIdLocale())
            ->setResourceType('redirect')
            ->setResourceId($redirect->getIdRedirect());

        $urlCountBeforeCreation = $urlQuery->count();
        $url = $this->urlFacade->saveUrl($url);
        $urlCountAfterCreation = $urlQuery->count();

        $this->assertTrue($urlCountAfterCreation > $urlCountBeforeCreation);

        $this->assertNotNull($url->getIdUrl());
    }

    /**
     * @return void
     */
    public function testSaveUrlUpdatesSomething()
    {
        $url = new UrlTransfer();
        $urlQuery = $this->urlQueryContainer->queryUrl('/SoManyPageUrls');
        $redirect1 = $this->urlFacade->createRedirect('/SoManyPageUrls2');
        $redirect2 = $this->urlFacade->createRedirect('/SoManyPageUrls3');

        $url
            ->setUrl('/SoManyPageUrls')
            ->setFkLocale($this->localeFacade->createLocale('WERTZ')->getIdLocale())
            ->setResourceType('redirect')
            ->setResourceId($redirect1->getIdRedirect());

        $url = $this->urlFacade->saveUrl($url);

        $this->assertEquals($redirect1->getIdRedirect(), $urlQuery->findOne()->getResourceId());

        $url->setResourceId($redirect2->getIdRedirect());
        $this->urlFacade->saveUrl($url);

        $this->assertEquals($redirect2->getIdRedirect(), $urlQuery->findOne()->getResourceId());
    }

    /**
     * @return void
     */
    public function testHasUrlId()
    {
        $locale = $this->localeFacade->createLocale('UNIXA');
        $redirect = $this->urlFacade->createRedirect('/SoManyPageUrls4');

        $idPageUrl = $this->urlFacade->createUrl('/abcdefg', $locale, 'redirect', $redirect->getIdRedirect())->getIdUrl();

        $this->assertTrue($this->urlFacade->hasUrlId($idPageUrl));
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function testTouchUrlActive()
    {
        $locale = $this->localeFacade->createLocale('ABCDE');
        $redirect = $this->urlFacade->createRedirect('/ARedirectUrl');

        $idUrl = $this->urlFacade->createUrl('/aPageUrl', $locale, 'redirect', $redirect->getIdRedirect())->getIdUrl();

        $touchQuery = $this->touchQueryContainer->queryTouchEntry('url', $idUrl);
        $touchQuery->setQueryKey('count');
        $this->assertEquals(0, $touchQuery->count());

        $touchQuery->setQueryKey(TouchQueryContainer::TOUCH_ENTRY_QUERY_KEY);
        $this->urlFacade->touchUrlActive($idUrl);

        $touchQuery->setQueryKey('count');
        $this->assertEquals(1, $touchQuery->count());
    }

    /**
     * @return void
     */
    public function testCreateRedirectInsertsAndReturnsSomething()
    {
        $redirectQuery = $this->urlQueryContainer->queryRedirects();

        $redirectCountBeforeCreation = $redirectQuery->count();
        $newRedirect = $this->urlFacade->createRedirect('/this/other/url');
        $redirectCountAfterCreation = $redirectQuery->count();

        $this->assertTrue($redirectCountAfterCreation > $redirectCountBeforeCreation);

        $this->assertNotNull($newRedirect->getIdRedirect());
    }

    /**
     * @return void
     */
    public function testSaveRedirectInsertsAndReturnsSomethingOnCreate()
    {
        $redirect = new RedirectTransfer();
        $redirect->setToUrl('/pageToUrl');
        $redirect->setStatus(301);

        $redirectQuery = $this->urlQueryContainer->queryRedirects();

        $redirectCountBeforeCreation = $redirectQuery->count();
        $redirect = $this->urlFacade->saveRedirect($redirect);
        $redirectCountAfterCreation = $redirectQuery->count();

        $this->assertTrue($redirectCountAfterCreation > $redirectCountBeforeCreation);

        $this->assertNotNull($redirect->getIdRedirect());
    }

    /**
     * @return void
     */
    public function testSaveRedirectUpdatesSomething()
    {
        $redirect = new RedirectTransfer();
        $redirect->setToUrl('/pageToUrl2');
        $redirect->setStatus(301);

        $redirect = $this->urlFacade->saveRedirect($redirect);

        $redirectQuery = $this->urlQueryContainer->queryRedirectById($redirect->getIdRedirect());

        $this->assertEquals('/pageToUrl2', $redirectQuery->findOne()->getToUrl());

        $redirect->setToUrl('/redirectingToUrl');
        $this->urlFacade->saveRedirect($redirect);

        $this->assertEquals('/redirectingToUrl', $redirectQuery->findOne()->getToUrl());
    }

}
