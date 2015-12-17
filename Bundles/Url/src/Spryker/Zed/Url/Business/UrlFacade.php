<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
use Spryker\Zed\Url\Business\Exception\MissingUrlException;
use Spryker\Zed\Url\Business\Exception\UrlExistsException;

/**
 * @method UrlBusinessFactory getFactory()
 */
class UrlFacade extends AbstractFacade
{

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws PropelException
     * @throws UrlExistsException
     *
     * @return UrlTransfer
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $idResource)
    {
        $urlManager = $this->getFactory()->getUrlManager();
        $pageUrl = $urlManager->createUrl($url, $locale, $resourceType, $idResource);

        return $urlManager->convertUrlEntityToTransfer($pageUrl);
    }

    /**
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws PropelException
     * @throws UrlExistsException
     *
     * @return UrlTransfer
     */
    public function createUrlForCurrentLocale($url, $resourceType, $idResource)
    {
        $urlManager = $this->getFactory()->getUrlManager();
        $url = $urlManager->createUrlForCurrentLocale($url, $resourceType, $idResource);

        return $urlManager->convertUrlEntityToTransfer($url);
    }

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @return UrlTransfer
     */
    public function saveUrl(UrlTransfer $urlTransfer)
    {
        $urlManager = $this->getFactory()->getUrlManager();

        return $urlManager->saveUrl($urlTransfer);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url)
    {
        $urlManager = $this->getFactory()->getUrlManager();

        return $urlManager->hasUrl($url);
    }

    /**
     * @param int $idUrl
     *
     * @return bool
     */
    public function hasUrlId($idUrl)
    {
        $urlManager = $this->getFactory()->getUrlManager();

        return $urlManager->hasUrlId($idUrl);
    }

    /**
     * @param string $urlString
     *
     * @throws MissingUrlException
     *
     * @return UrlTransfer
     */
    public function getUrlByPath($urlString)
    {
        $urlManager = $this->getFactory()->getUrlManager();
        $urlEntity = $urlManager->getUrlByPath($urlString);

        return $urlManager->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param int $idUrl
     *
     * @throws MissingUrlException
     *
     * @return UrlTransfer
     */
    public function getUrlById($idUrl)
    {
        $urlManager = $this->getFactory()->getUrlManager();
        $urlEntity = $urlManager->getUrlById($idUrl);

        return $urlManager->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param int $idCategoryNode
     * @param LocaleTransfer $locale
     *
     * @return UrlTransfer|null
     */
    public function getResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $locale)
    {
        $urlManager = $this->getFactory()->getUrlManager();
        $urlEntity = $urlManager->getResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $locale->getIdLocale());

        if (!$urlEntity) {
            return null;
        }

        return $urlManager->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlActive($idUrl)
    {
        $this->getFactory()->getUrlManager()->touchUrlActive($idUrl);
    }

    /**
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl)
    {
        $this->getFactory()->getUrlManager()->touchUrlDeleted($idUrl);
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @throws MissingUrlException
     * @throws \Exception
     * @throws PropelException
     *
     * @return RedirectTransfer
     */
    public function createRedirect($toUrl, $status = 303)
    {
        $redirectManager = $this->getFactory()->getRedirectManager();
        $redirect = $redirectManager->createRedirect($toUrl, $status);

        return $redirectManager->convertRedirectEntityToTransfer($redirect);
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @return RedirectTransfer
     */
    public function createRedirectAndTouch($toUrl, $status = 303)
    {
        $redirectManager = $this->getFactory()->getRedirectManager();
        $redirectTransfer = $redirectManager->createRedirectAndTouch($toUrl, $status);

        return $redirectTransfer;
    }

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idRedirect
     *
     * @throws UrlExistsException
     * @throws MissingLocaleException
     *
     * @return UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idRedirect)
    {
        $redirectManager = $this->getFactory()->getRedirectManager();

        return $redirectManager->createRedirectUrl($url, $locale, $idRedirect);
    }

    /**
     * @param string $url
     * @param LocaleTransfer $locale
     * @param int $idRedirect
     *
     * @return UrlTransfer
     */
    public function saveRedirectUrlAndTouch($url, LocaleTransfer $locale, $idRedirect)
    {
        $redirectManager = $this->getFactory()->getRedirectManager();

        return $redirectManager->saveRedirectUrlAndTouch($url, $locale, $idRedirect);
    }

    /**
     * @param RedirectTransfer $redirect
     *
     * @return RedirectTransfer
     */
    public function saveRedirect(RedirectTransfer $redirect)
    {
        $redirectManager = $this->getFactory()->getRedirectManager();

        return $redirectManager->saveRedirect($redirect);
    }

    /**
     * @param RedirectTransfer $redirect
     *
     * @return void
     */
    public function touchRedirectActive(RedirectTransfer $redirect)
    {
        $redirectManager = $this->getFactory()->getRedirectManager();

        $redirectManager->touchRedirectActive($redirect);
    }

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @return UrlTransfer
     */
    public function saveUrlAndTouch(UrlTransfer $urlTransfer)
    {
        $urlManager = $this->getFactory()->getUrlManager();

        return $urlManager->saveUrlAndTouch($urlTransfer);
    }

    /**
     * @param UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deleteUrl(UrlTransfer $urlTransfer)
    {
        $urlManager = $this->getFactory()->getUrlManager();

        $urlManager->deleteUrl($urlTransfer);
    }

    /**
     * @param RedirectTransfer $redirect
     *
     * @return RedirectTransfer
     */
    public function saveRedirectAndTouch(RedirectTransfer $redirect)
    {
        $redirectManager = $this->getFactory()->getRedirectManager();

        return $redirectManager->saveRedirectAndTouch($redirect);
    }

    /**
     * @param int $idAbstractProduct
     * @param int $idLocale
     *
     * @return UrlTransfer
     */
    public function getUrlByIdAbstractProductAndIdLocale($idAbstractProduct, $idLocale)
    {
        $urlManager = $this->getFactory()->getUrlManager();

        return $urlManager->getUrlByIdAbstractProductAndIdLocale($idAbstractProduct, $idLocale);
    }

}
