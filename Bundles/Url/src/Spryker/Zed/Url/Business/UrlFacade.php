<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Url\Business\UrlBusinessFactory getFactory()
 */
class UrlFacade extends AbstractFacade implements UrlFacadeInterface
{

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $idResource)
    {
        $urlManager = $this->getFactory()->createUrlManager();
        $pageUrl = $urlManager->createUrl($url, $locale, $resourceType, $idResource);

        return $urlManager->convertUrlEntityToTransfer($pageUrl);
    }

    /**
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrlForCurrentLocale($url, $resourceType, $idResource)
    {
        $urlManager = $this->getFactory()->createUrlManager();
        $url = $urlManager->createUrlForCurrentLocale($url, $resourceType, $idResource);

        return $urlManager->convertUrlEntityToTransfer($url);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrl(UrlTransfer $urlTransfer)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->saveUrl($urlTransfer);
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->hasUrl($url);
    }

    /**
     * @param int $idUrl
     *
     * @return bool
     */
    public function hasUrlId($idUrl)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->hasUrlId($idUrl);
    }

    /**
     * @param string $urlString
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlByPath($urlString)
    {
        $urlManager = $this->getFactory()->createUrlManager();
        $urlEntity = $urlManager->getUrlByPath($urlString);

        return $urlManager->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param int $idUrl
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlById($idUrl)
    {
        $urlManager = $this->getFactory()->createUrlManager();
        $urlEntity = $urlManager->getUrlById($idUrl);

        return $urlManager->convertUrlEntityToTransfer($urlEntity);
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function getResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $locale)
    {
        $urlManager = $this->getFactory()->createUrlManager();
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
        $this->getFactory()->createUrlManager()->touchUrlActive($idUrl);
    }

    /**
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl)
    {
        $this->getFactory()->createUrlManager()->touchUrlDeleted($idUrl);
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function createRedirect($toUrl, $status = 303)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();
        $redirect = $redirectManager->createRedirect($toUrl, $status);

        return $redirectManager->convertRedirectEntityToTransfer($redirect);
    }

    /**
     * @param string $toUrl
     * @param int $status
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function createRedirectAndTouch($toUrl, $status = 303)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();
        $redirectTransfer = $redirectManager->createRedirectAndTouch($toUrl, $status);

        return $redirectTransfer;
    }

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idUrlRedirect)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        return $redirectManager->createRedirectUrl($url, $locale, $idUrlRedirect);
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirectTransfer
     *
     * @return void
     */
    public function deleteUrlRedirect(RedirectTransfer $redirectTransfer)
    {
        $this->getFactory()->createRedirectManager()->deleteUrlRedirect($redirectTransfer);
    }

    /**
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveRedirectUrlAndTouch($url, LocaleTransfer $locale, $idUrlRedirect)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        return $redirectManager->saveRedirectUrlAndTouch($url, $locale, $idUrlRedirect);
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirect(RedirectTransfer $redirect)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        return $redirectManager->saveRedirect($redirect);
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return void
     */
    public function touchRedirectActive(RedirectTransfer $redirect)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        $redirectManager->touchRedirectActive($redirect);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrlAndTouch(UrlTransfer $urlTransfer)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->saveUrlAndTouch($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deleteUrl(UrlTransfer $urlTransfer)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        $urlManager->deleteUrl($urlTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirectAndTouch(RedirectTransfer $redirect)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        return $redirectManager->saveRedirectAndTouch($redirect);
    }

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);
    }

}
