<?php

namespace SprykerFeature\Zed\Url\Business;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Shared\Url\Transfer\Redirect;
use SprykerFeature\Zed\Url\Business\Exception\MissingUrlException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;
use SprykerFeature\Shared\Url\Transfer\Url;

/**
 * @method UrlDependencyContainer getDependencyContainer()
 */
class UrlFacade extends AbstractFacade
{
    /**
     * @param string $url
     * @param LocaleDto $locale
     * @param string $resourceType
     * @param int $idResource
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     */
    public function createUrl($url, LocaleDto $locale, $resourceType, $idResource)
    {
        $urlManager = $this->getDependencyContainer()->getUrlManager();
        $pageUrl = $urlManager->createUrl($url, $locale, $resourceType, $idResource);

        return $urlManager->convertUrlEntityToTransfer($pageUrl);
    }

    /**
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @return Url
     * @throws PropelException
     * @throws UrlExistsException
     */
    public function createUrlForCurrentLocale($url, $resourceType, $idResource)
    {
        $urlManager = $this->getDependencyContainer()->getUrlManager();
        $url = $urlManager->createUrlForCurrentLocale($url, $resourceType, $idResource);

        return $urlManager->convertUrlEntityToTransfer($url);
    }

    /**
     * @param Url $url
     *
     * @return Url
     */
    public function saveUrl(Url $url)
    {
        $urlManager = $this->getDependencyContainer()->getUrlManager();

        return $urlManager->saveUrl($url);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url)
    {
        $urlManager = $this->getDependencyContainer()->getUrlManager();

        return $urlManager->hasUrl($url);
    }

    /**
     * @param int $idUrl
     *
     * @return bool
     */
    public function hasUrlId($idUrl)
    {
        $urlManager = $this->getDependencyContainer()->getUrlManager();

        return $urlManager->hasUrlId($idUrl);
    }

    /**
     * @param string $urlString
     *
     * @return Url
     * @throws MissingUrlException
     */
    public function getUrlByPath($urlString)
    {
        $urlManager = $this->getDependencyContainer()->getUrlManager();
        $urlEntity = $urlManager->getUrlByPath($urlString);

        return $urlManager->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param int $idUrl
     *
     * @return Url
     * @throws MissingUrlException
     */
    public function getUrlById($idUrl)
    {
        $urlManager = $this->getDependencyContainer()->getUrlManager();
        $urlEntity = $urlManager->getUrlById($idUrl);

        return $urlManager->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param int $idUrl
     */
    public function touchUrlActive($idUrl)
    {
        $this->getDependencyContainer()->getUrlManager()->touchUrlActive($idUrl);
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @return Redirect
     * @throws MissingUrlException
     * @throws \Exception
     * @throws PropelException
     */
    public function createRedirect($toUrl, $status = 303)
    {
        $redirectManager = $this->getDependencyContainer()->getRedirectManager();
        $redirect = $redirectManager->createRedirect($toUrl, $status);

        return $redirectManager->convertRedirectEntityToTransfer($redirect);
    }

    /**
     * @param string $url
     * @param LocaleDto $locale
     * @param int $idRedirect
     *
     * @return Url
     * @throws UrlExistsException
     * @throws MissingLocaleException
     */
    public function createRedirectUrl($url, LocaleDto $locale, $idRedirect)
    {
        $redirectManager = $this->getDependencyContainer()->getRedirectManager();

        return $redirectManager->createRedirectUrl($url, $locale, $idRedirect);
    }

    /**
     * @param Redirect $redirect
     *
     * @return Redirect
     */
    public function saveRedirect(Redirect $redirect)
    {
        $redirectManager = $this->getDependencyContainer()->getRedirectManager();

        return $redirectManager->saveRedirect($redirect);
    }

    /**
     * @param Redirect $redirect
     */
    public function touchRedirectActive(Redirect $redirect)
    {
        $redirectManager = $this->getDependencyContainer()->getRedirectManager();

        $redirectManager->touchRedirectActive($redirect);
    }
}
