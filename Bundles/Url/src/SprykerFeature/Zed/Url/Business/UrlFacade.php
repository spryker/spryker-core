<?php

namespace SprykerFeature\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlUrlTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Locale\Business\Exception\MissingLocaleException;
use SprykerFeature\Zed\Url\Business\Exception\MissingUrlException;
use SprykerFeature\Zed\Url\Business\Exception\UrlExistsException;

/**
 * @method UrlDependencyContainer getDependencyContainer()
 */
class UrlFacade extends AbstractFacade
{
    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param string $resourceType
     * @param int $idResource
     *
     * @return UrlUrlTransfer
     * @throws PropelException
     * @throws UrlExistsException
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $idResource)
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
     * @return UrlUrlTransfer
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
     * @param UrlUrlTransfer $url
     *
     * @return UrlUrlTransfer
     */
    public function saveUrl(UrlUrlTransfer $url)
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
     * @return UrlUrlTransfer
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
     * @return UrlUrlTransfer
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
     * @return UrlRedirectTransfer
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
     * @param LocaleTransfer $locale
     * @param int $idRedirect
     *
     * @return UrlUrlTransfer
     * @throws UrlExistsException
     * @throws MissingLocaleException
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idRedirect)
    {
        $redirectManager = $this->getDependencyContainer()->getRedirectManager();

        return $redirectManager->createRedirectUrl($url, $locale, $idRedirect);
    }

    /**
     * @param UrlRedirectTransfer $redirect
     *
     * @return UrlRedirectTransfer
     */
    public function saveRedirect(UrlRedirectTransfer $redirect)
    {
        $redirectManager = $this->getDependencyContainer()->getRedirectManager();

        return $redirectManager->saveRedirect($redirect);
    }

    /**
     * @param UrlRedirectTransfer $redirect
     */
    public function touchRedirectActive(UrlRedirectTransfer $redirect)
    {
        $redirectManager = $this->getDependencyContainer()->getRedirectManager();

        $redirectManager->touchRedirectActive($redirect);
    }
}
