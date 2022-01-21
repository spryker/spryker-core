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
 * @method \Spryker\Zed\Url\Persistence\UrlRepositoryInterface getRepository()
 */
class UrlFacade extends AbstractFacade implements UrlFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer|string $urlTransfer Deprecated: String format is accepted for BC reasons only.
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer Deprecated: This parameter exists for BC reasons only. Use `createUrl(UrlTransfer $urlTransfer)` format instead.
     * @param string|null $resourceType Deprecated: This parameter exists for BC reasons only. Use `createUrl(UrlTransfer $urlTransfer)` format instead.
     * @param int|null $idResource Deprecated: This parameter exists for BC reasons only. Use `createUrl(UrlTransfer $urlTransfer)` format instead.
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl($urlTransfer, ?LocaleTransfer $localeTransfer = null, $resourceType = null, $idResource = null)
    {
        if (is_string($urlTransfer)) {
            return $this->legacyCreateUrl($urlTransfer, $localeTransfer, $resourceType, $idResource);
        }

        return $this->getFactory()
            ->createUrlCreator()
            ->createUrl($urlTransfer);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Url\Business\Url\UrlCreatorInterface::createUrl()} instead.
     *
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $resourceType
     * @param int|null $idResource
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function legacyCreateUrl($url, LocaleTransfer $localeTransfer, $resourceType = null, $idResource = null)
    {
        $urlManager = $this->getFactory()->createUrlManager();
        $pageUrl = $urlManager->createUrl($url, $localeTransfer, $resourceType, $idResource);

        return $urlManager->convertUrlEntityToTransfer($pageUrl);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrlCaseInsensitive(UrlTransfer $urlTransfer): ?UrlTransfer
    {
        return $this->getFactory()
            ->createUrlReader()
            ->findUrlCaseInsensitive($urlTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer|string $urlTransfer Deprecated: String format is accepted for BC reasons only.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrlCaseInsensitive(UrlTransfer $urlTransfer): bool
    {
        return $this->getFactory()
            ->createUrlReader()
            ->hasUrlCaseInsensitive($urlTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrlOrRedirectedUrl(UrlTransfer $urlTransfer)
    {
        return $this->getFactory()
            ->createUrlReader()
            ->hasUrlOrRedirectedUrl($urlTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrlOrRedirectedUrlCaseInsensitive(UrlTransfer $urlTransfer): bool
    {
        return $this->getFactory()
            ->createUrlReader()
            ->hasUrlOrRedirectedUrlCaseInsensitive($urlTransfer);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Url\Business\Url\UrlReaderInterface::hasUrl()} instead.
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer|\Generated\Shared\Transfer\RedirectTransfer $urlRedirectTransfer Deprecated: RedirectTransfer format is accepted for BC reasons only.
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
     * @deprecated Use {@link \Spryker\Zed\Url\Business\Deletion\UrlDeleterInterface::deleteUrlRedirect()} instead.
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createUrl()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createUrl()} or {@link updateUrl()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link hasUrl()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link findUrl()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link findUrl()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed with next major release. Category bundle handles logic internally.
     *
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return bool
     */
    public function hasResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $localeTransfer)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->hasResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $localeTransfer->getIdLocale());
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed with next major release. Category bundle handles logic internally.
     *
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function getResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $localeTransfer)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->getResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $localeTransfer->getIdLocale());
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed with next major release. Category bundle handles logic internally.
     *
     * @param int $idCategoryNode
     *
     * @return array<\Generated\Shared\Transfer\UrlTransfer>
     */
    public function getResourceUrlCollectionByCategoryNodeId($idCategoryNode)
    {
        $urlManager = $this->getFactory()->createUrlManager();

        return $urlManager->getResourceUrlCollectionByCategoryNodeId($idCategoryNode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link activateUrl()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link deactivateUrl()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createUrlRedirect()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createUrlRedirect()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createUrlRedirect()} instead.
     *
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idUrlRedirect
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $localeTransfer, $idUrlRedirect)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        return $redirectManager->createRedirectUrl($url, $localeTransfer, $idUrlRedirect);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createUrlRedirect()} or {@link updateUrlRedirect()} instead.
     *
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idUrlRedirect
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveRedirectUrlAndTouch($url, LocaleTransfer $localeTransfer, $idUrlRedirect)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        return $redirectManager->saveRedirectUrlAndTouch($url, $localeTransfer, $idUrlRedirect);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createUrlRedirect()} or {@link updateUrlRedirect()} instead.
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirectTransfer
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirect(RedirectTransfer $redirectTransfer)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        return $redirectManager->saveRedirect($redirectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link activateUrlRedirect()} instead.
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirectTransfer
     *
     * @return void
     */
    public function touchRedirectActive(RedirectTransfer $redirectTransfer)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        $redirectManager->touchRedirectActive($redirectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createUrl()} or {@link updateUrl()} instead.
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createUrlRedirect()} or {@link updateUrlRedirect()} instead.
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirectTransfer
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirectAndTouch(RedirectTransfer $redirectTransfer)
    {
        $redirectManager = $this->getFactory()->createRedirectManager();

        return $redirectManager->saveRedirectAndTouch($redirectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated This method will be removed with next major release because of invalid dependency direction.
     *   Use {@link \Spryker\Zed\Product\Business\ProductFacadeInterface::getProductUrl()} instead.
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
