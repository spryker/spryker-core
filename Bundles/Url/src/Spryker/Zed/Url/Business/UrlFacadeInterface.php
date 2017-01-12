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

interface UrlFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer|string $urlTransfer String format is accepted for BC reasons.
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $resourceType @deprecated This parameter exists for BC reasons. Use `createUrl(UrlTransfer $urlTransfer, LocaleTransfer $localeTransfer)` format instead.
     * @param int|null $idResource @deprecated This parameter exists for BC reasons. Use `createUrl(UrlTransfer $urlTransfer, LocaleTransfer $localeTransfer)` format instead.
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl($urlTransfer, LocaleTransfer $localeTransfer, $resourceType = null, $idResource = null);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function findUrl(UrlTransfer $urlTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer|string $url String format is only for BC reasons.
     *
     * @return bool
     */
    public function hasUrl($url);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function updateUrl(UrlTransfer $urlTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deleteUrl(UrlTransfer $urlTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function activateUrl(UrlTransfer $urlTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function deactivateUrl(UrlTransfer $urlTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function createUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function findUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function hasUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function updateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer|\Generated\Shared\Transfer\RedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    public function deleteUrlRedirect($urlRedirectTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function activateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function deactivateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * @api
     *
     * @deprecated
     *
     * @param string $url
     * @param string $resourceType
     * @param int $idResource
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrlForCurrentLocale($url, $resourceType, $idResource);

    /**
     * @api
     *
     * @deprecated
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrl(UrlTransfer $urlTransfer);

    /**
     * @api
     *
     * @deprecated
     *
     * @param int $idUrl
     *
     * @return bool
     */
    public function hasUrlId($idUrl);

    /**
     * @api
     *
     * @deprecated
     *
     * @param string $urlString
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlByPath($urlString);

    /**
     * @api
     *
     * @deprecated
     *
     * @param int $idUrl
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlById($idUrl);

    /**
     * Specification:
     * - check if a ResourceUrl by CategoryNode and Locale exist
     *
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $locale);

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function getResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $locale);

    /**
     * @api
     *
     * @deprecated Will be removed with next major release
     *
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getResourceUrlCollectionByCategoryNodeId($idCategoryNode);

    /**
     * @api
     *
     * @deprecated
     *
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlActive($idUrl);

    /**
     * @api
     *
     * @deprecated
     *
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl);

    /**
     * @api
     *
     * @deprecated
     *
     * @param string $toUrl
     * @param int $status
     *
     * @throws \Spryker\Zed\Url\Business\Exception\MissingUrlException
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function createRedirect($toUrl, $status = 303);

    /**
     * @api
     *
     * @deprecated
     *
     * @param string $toUrl
     * @param int $status
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function createRedirectAndTouch($toUrl, $status = 303);

    /**
     * @api
     *
     * @deprecated
     *
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @throws \Spryker\Zed\Url\Business\Exception\UrlExistsException
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createRedirectUrl($url, LocaleTransfer $locale, $idUrlRedirect);

    /**
     * @api
     *
     * @deprecated
     *
     * @param string $url
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param int $idUrlRedirect
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveRedirectUrlAndTouch($url, LocaleTransfer $locale, $idUrlRedirect);

    /**
     * @api
     *
     * @deprecated
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirect(RedirectTransfer $redirect);

    /**
     * @api
     *
     * @deprecated
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return void
     */
    public function touchRedirectActive(RedirectTransfer $redirect);

    /**
     * @api
     *
     * @deprecated
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrlAndTouch(UrlTransfer $urlTransfer);

    /**
     * @api
     *
     * @deprecated
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirectAndTouch(RedirectTransfer $redirect);

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
    public function getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);

}
