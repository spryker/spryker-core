<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlRedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\Url\Business\UrlBusinessFactory getFactory()
 */
class UrlFacade extends AbstractFacade implements UrlFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer|string $urlTransfer String format is accepted for BC reasons.
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer @deprecated This parameter exists for BC reasons. Use `createUrl(UrlTransfer $urlTransfer)` format instead.
     * @param string|null $resourceType @deprecated This parameter exists for BC reasons. Use `createUrl(UrlTransfer $urlTransfer)` format instead.
     * @param int|null $idResource @deprecated This parameter exists for BC reasons. Use `createUrl(UrlTransfer $urlTransfer)` format instead.
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl($urlTransfer, LocaleTransfer $localeTransfer = null, $resourceType = null, $idResource = null)
    {
        if (func_num_args() === 4) {
            return $this->legacyCreateUrl($urlTransfer, $localeTransfer, $resourceType, $idResource);
        }

        return $this->getFactory()
            ->createUrlCreator()
            ->createUrl($urlTransfer);
    }

    /**
     * @deprecated
     *
     * @param \Generated\Shared\Transfer\UrlTransfer|string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string|null $resourceType
     * @param int|null $idResource
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function legacyCreateUrl($url, LocaleTransfer $locale, $resourceType = null, $idResource = null)
    {
        $urlManager = $this->getFactory()->createUrlManager();
        $pageUrl = $urlManager->createUrl($url, $locale, $resourceType, $idResource);

        return $urlManager->convertUrlEntityToTransfer($pageUrl);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrl(UrlTransfer $urlTransfer)
    {
        return $this->getFactory()
            ->createUrlReader()
            ->findUrl($urlTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer|string $urlTransfer String format is only for BC reasons.
     *
     * @return bool
     */
    public function hasUrl($urlTransfer)
    {
        if (is_string($urlTransfer)) {
            return $this->legacyHasUrl($urlTransfer);
        }

        return $this->getFactory()
            ->createUrlReader()
            ->hasUrl($urlTransfer);
    }

    /**
     * @deprecated
     *
     * @param string $url
     *
     * @return bool
     */
    protected function legacyHasUrl($url)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->hasUrl($url);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function updateUrl(UrlTransfer $urlTransfer)
    {
        return $this->getFactory()
            ->createUrlUpdater()
            ->updateUrl($urlTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deleteUrl(UrlTransfer $urlTransfer)
    {
        $this->getFactory()
            ->createUrlDeleter()
            ->deleteUrl($urlTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function activateUrl(UrlTransfer $urlTransfer)
    {
        $this->getFactory()
            ->createUrlActivator()
            ->activateUrl($urlTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deactivateUrl(UrlTransfer $urlTransfer)
    {
        $this->getFactory()
            ->createUrlActivator()
            ->deactivateUrl($urlTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function createUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        return $this->getFactory()
            ->createUrlRedirectCreator()
            ->createUrlRedirect($urlRedirectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer|null
     */
    public function findUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        return $this->getFactory()
            ->createUrlRedirectReader()
            ->findUrlRedirect($urlRedirectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return bool
     */
    public function hasUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        return $this->getFactory()
            ->createUrlRedirectReader()
            ->hasUrlRedirect($urlRedirectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function updateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        return $this->getFactory()
            ->createUrlRedirectUpdater()
            ->updateUrlRedirect($urlRedirectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer|\Generated\Shared\Transfer\RedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    public function deleteUrlRedirect($urlRedirectTransfer)
    {
        if ($urlRedirectTransfer instanceof RedirectTransfer) {
            $this->legacyDeleteUrlRedirect($urlRedirectTransfer);
            return;
        }

        $this->getFactory()
            ->createUrlDeleter()
            ->deleteUrlRedirect($urlRedirectTransfer);
    }

    /**
     * @deprecated
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    protected function legacyDeleteUrlRedirect(RedirectTransfer $urlRedirectTransfer)
    {
        $this->getFactory()->createRedirectManager()->deleteUrlRedirect($urlRedirectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    public function activateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $this->getFactory()
            ->createUrlRedirectActivator()
            ->activateUrlRedirect($urlRedirectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    public function deactivateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        $this->getFactory()
            ->createUrlRedirectActivator()
            ->deactivateUrlRedirect($urlRedirectTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectValidationResponseTransfer
     */
    public function validateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer)
    {
        return $this->getFactory()
            ->createUrlRedirectValidator()
            ->validateUrlRedirect($urlRedirectTransfer);
    }

    /**
     * @api
     *
     * @deprecated Use createUrl() instead.
     *
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
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
     * @api
     *
     * @deprecated Use createUrl/updateUrl instead.
     *
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
     * @api
     *
     * @deprecated Use hasUrl() instead.
     *
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
     * @api
     *
     * @deprecated Use findUrl() instead.
     *
     * @param string $urlString
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
     * @api
     *
     * @deprecated use findUrl() instead.
     *
     * @param int $idUrl
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
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Will be removed with next major release. Category bundle handles logic internally.
     *
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $locale)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->hasResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $locale->getIdLocale());
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release. Category bundle handles logic internally.
     *
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function getResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $locale)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->getResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $locale->getIdLocale());
    }

    /**
     * @api
     *
     * @deprecated Will be removed with next major release. Category bundle handles logic internally.
     *
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getResourceUrlCollectionByCategoryNodeId($idCategoryNode)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->getResourceUrlCollectionByCategoryNodeId($idCategoryNode);
    }

    /**
     * @api
     *
     * @deprecated Use activateUrl() instead.
     *
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlActive($idUrl)
    {
        $this->getFactory()->createUrlManager()->touchUrlActive($idUrl);
    }

    /**
     * @api
     *
     * @deprecated Use deactivateUrl() instead.
     *
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl)
    {
        $this->getFactory()->createUrlManager()->touchUrlDeleted($idUrl);
    }

    /**
     * @api
     *
     * @deprecated Use createUrlRedirect() instead.
     *
     * @param string $toUrl
     * @param int $status
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function createRedirect($toUrl, $status = Response::HTTP_SEE_OTHER)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();
        $redirect = $redirectManager->createRedirect($toUrl, $status);

        return $redirectManager->convertRedirectEntityToTransfer($redirect);
    }

    /**
     * @api
     *
     * @deprecated Use createUrlRedirect() instead.
     *
     * @param string $toUrl
     * @param int $status
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function createRedirectAndTouch($toUrl, $status = Response::HTTP_SEE_OTHER)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();
        $redirectTransfer = $redirectManager->createRedirectAndTouch($toUrl, $status);

        return $redirectTransfer;
    }

    /**
     * @api
     *
     * @deprecated Use createUrlRedirect() instead.
     *
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idUrlRedirect)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        return $redirectManager->createRedirectUrl($url, $locale, $idUrlRedirect);
    }

    /**
     * @api
     *
     * @deprecated Use createUrlRedirect()/updateUrlRedirect() instead.
     *
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
     * @api
     *
     * @deprecated Use createUrlRedirect()/updateUrlRedirect() instead.
     *
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
     * @api
     *
     * @deprecated Use activateUrlRedirect() instead.
     *
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
     * @api
     *
     * @deprecated Use createUrl()/updateUrl() instead.
     *
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
     * @api
     *
     * @deprecated Use createUrlRedirect()/updateUrlRedirect() instead.
     *
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
     * @api
     *
     * @deprecated This method will be removed with next major release because of invalid dependency direction.
     * Use ProductFacade::getProductUrl() instead.
     *
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
