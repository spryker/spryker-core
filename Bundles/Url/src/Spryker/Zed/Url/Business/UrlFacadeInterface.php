<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\RedirectTransfer;
use Generated\Shared\Transfer\UrlTransfer;

interface UrlFacadeInterface
{

    /**
     * @api
     *
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
    public function createUrl($url, LocaleTransfer $locale, $resourceType, $idResource);

    /**
     * @api
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
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrl(UrlTransfer $urlTransfer);

    /**
     * @api
     *
     * @param string $url
     *
     * @return bool
     */
    public function hasUrl($url);

    /**
     * @api
     *
     * @param int $idUrl
     *
     * @return bool
     */
    public function hasUrlId($idUrl);

    /**
     * @api
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
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasResourceUrlByCategoryNodeIdAndLocale($idCategoryNode, LocaleTransfer $locale);

    /**
     * @api
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
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getResourceUrlCollectionByCategoryNodeId($idCategoryNode);

    /**
     * @api
     *
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlActive($idUrl);

    /**
     * @api
     *
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl);

    /**
     * @api
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
     * @param string $toUrl
     * @param int $status
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function createRedirectAndTouch($toUrl, $status = 303);

    /**
     * @api
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
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirect(RedirectTransfer $redirect);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return void
     */
    public function touchRedirectActive(RedirectTransfer $redirect);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrlAndTouch(UrlTransfer $urlTransfer);

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
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirectAndTouch(RedirectTransfer $redirect);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);

}
