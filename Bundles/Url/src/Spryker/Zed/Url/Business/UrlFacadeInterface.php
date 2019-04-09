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
use Symfony\Component\HttpFoundation\Response;

interface UrlFacadeInterface
{
    /**
     * Specification:
     * - Persists a new URL entity in database.
     * - Touches active "url" entry.
     * - Existing redirect from the URL of the new entity will be deleted.
     * - Existing related URL redirects will be updated to avoid redirect chains.
     * - Existing related URL redirects can affect the value of the persisted URL to avoid redirect chains.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer|string $urlTransfer Deprecated: String format is accepted for BC reasons only.
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer Deprecated: This parameter exists for BC reasons. Use `createUrl(UrlTransfer $urlTransfer)` format instead.
     * @param string|null $resourceType Deprecated: This parameter exists for BC reasons. Use `createUrl(UrlTransfer $urlTransfer)` format instead.
     * @param int|null $idResource Deprecated: This parameter exists for BC reasons. Use `createUrl(UrlTransfer $urlTransfer)` format instead.
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function createUrl($urlTransfer, ?LocaleTransfer $localeTransfer = null, $resourceType = null, $idResource = null);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::findUrlCaseInsensitive() instead, since URLs should be case insensitive.
     *
     * Specification:
     * - Finds existing URL entity in database by the provided `id_url` or `url`.
     * - Returns UrlTransfer with the appropriate data from database if the entity is found, NULL otherwise.
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrl(UrlTransfer $urlTransfer);

    /**
     * Specification:
     * - Finds existing URL entity by case insensitive URL value.
     * - Returns UrlTransfer with the appropriate data from database if the entity is found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrlCaseInsensitive(UrlTransfer $urlTransfer): ?UrlTransfer;

    /**
     * @api
     *
     * @deprecated Use UrlFacade::hasUrlCaseInsensitive() instead, since URLs should be case insensitive.
     *
     * Specification:
     * - Checks if URL entity exists in database by the provided `id_url` or `url`.
     * - URL redirects are ignored unless the URL to be checked is a redirect as well (`fkResourceRedirect` is set).
     *
     * @param \Generated\Shared\Transfer\UrlTransfer|string $urlTransfer Deprecated: String format is accepted for BC reasons only.
     *
     * @return bool
     */
    public function hasUrl($urlTransfer);

    /**
     * Specification:
     * - Checks if URL entity exists in database by the provided `id_url` or case insensitive `url`.
     * - URL redirects are ignored unless the URL to be checked is a redirect as well (`fkResourceRedirect` is set).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrlCaseInsensitive(UrlTransfer $urlTransfer): bool;

    /**
     * @api
     *
     * @deprecated Use UrlFacade::hasUrlOrRedirectedUrlCaseInsensitive() instead, since URLs should be case insensitive.
     *
     * Specification:
     * - Checks if URL entity exists in database by the provided `id_url` or `url`.
     * - Redirected URLs are also considered.
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrlOrRedirectedUrl(UrlTransfer $urlTransfer);

    /**
     * Specification:
     * - Checks if URL entity exists in database by the provided `id_url` or case insensitive `url`.
     * - Redirected URLs are also considered.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return bool
     */
    public function hasUrlOrRedirectedUrlCaseInsensitive(UrlTransfer $urlTransfer): bool;

    /**
     * Specification:
     * - Persists changes of existing URL entity in database.
     * - Touches active "url" entry.
     * - Existing redirect from the URL of the updated entity will be deleted.
     * - Existing related URL redirects will be updated to avoid redirect chains.
     * - Existing related URL redirects can affect the value of the persisted URL to avoid redirect chains.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function updateUrl(UrlTransfer $urlTransfer);

    /**
     * Specification:
     * - Removes URL entity from database.
     * - Touch deletes "url" entry.
     * - Removes URL redirects that points to the deleted URL.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deleteUrl(UrlTransfer $urlTransfer);

    /**
     * Secification:
     * - Touches active "url" entry.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function activateUrl(UrlTransfer $urlTransfer);

    /**
     * Specification:
     * - Touches inactive "url" entry.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function deactivateUrl(UrlTransfer $urlTransfer);

    /**
     * Specification:
     * - Persists new URL redirect entity to database.
     * - Touches active "redirect" entry.
     * - Existing related URL redirects will be updated to avoid redirect chains.
     * - Existing related URL redirects can affect the value of the persisted URL to avoid redirect chains.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function createUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * Specification:
     * - Finds existing URL redirect entity in database by the provided `id_url_redirect`.
     * - Returns UrlRedirectTransfer with the appropriate data from database if the entity is found, NULL otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer|null
     */
    public function findUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * Specification:
     * - Checks if URL redirect entity exists in database by the provided `id_url_redirect`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return bool
     */
    public function hasUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * Specification:
     * - Persists changes of existing URL redirect entity in database.
     * - Touches active "redirect" entry.
     * - If "source" data is also provided, related URL entity will be also updated.
     * - Existing related URL redirects will be updated to avoid redirect chains.
     * - Existing related URL redirects can affect the value of the persisted URL to avoid redirect chains.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectTransfer
     */
    public function updateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * Specification:
     * - Removes URL redirect entity from database.
     * - Touch deletes "redirect" entry.
     * - Removes URL redirects that points to the deleted URL.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer|\Generated\Shared\Transfer\RedirectTransfer $urlRedirectTransfer Deprecated: RedirectTransfer format is accepted for BC reasons only.
     *
     * @return void
     */
    public function deleteUrlRedirect($urlRedirectTransfer);

    /**
     * Specification:
     * - Touches active "redirect" entry.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    public function activateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * Specification:
     * - Touches inactive "url" entry.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return void
     */
    public function deactivateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * Specification:
     * - Checks if the provided URL redirect is valid or not.
     * - URL redirect is invalid if it results in a redirect loop.
     *
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlRedirectTransfer $urlRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\UrlRedirectValidationResponseTransfer
     */
    public function validateUrlRedirect(UrlRedirectTransfer $urlRedirectTransfer);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::createUrl() instead.
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
     * @deprecated Use UrlFacade::createUrl() or UrlFacade::updateUrl() instead.
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrl(UrlTransfer $urlTransfer);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::hasUrl() instead.
     *
     * @param int $idUrl
     *
     * @return bool
     */
    public function hasUrlId($idUrl);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::findUrl() instead.
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
     * @deprecated Use UrlFacade::findUrl() instead.
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
     * @deprecated Will be removed with next major release. Category bundle handles logic internally.
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
     * @deprecated Will be removed with next major release. Category bundle handles logic internally.
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
     * @deprecated Will be removed with next major release. Category bundle handles logic internally.
     *
     * @param int $idCategoryNode
     *
     * @return \Generated\Shared\Transfer\UrlTransfer[]
     */
    public function getResourceUrlCollectionByCategoryNodeId($idCategoryNode);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::activateUrl() instead.
     *
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlActive($idUrl);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::deactivateUrl() instead.
     *
     * @param int $idUrl
     *
     * @return void
     */
    public function touchUrlDeleted($idUrl);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::createUrlRedirect() instead.
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
    public function createRedirect($toUrl, $status = Response::HTTP_SEE_OTHER);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::createUrlRedirect() instead.
     *
     * @param string $toUrl
     * @param int $status
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function createRedirectAndTouch($toUrl, $status = Response::HTTP_SEE_OTHER);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::createUrlRedirect() instead.
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
     * @deprecated Use UrlFacade::createUrlRedirect() or UrlFacade::updateUrlRedirect() instead.
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
     * @deprecated Use UrlFacade::createUrlRedirect() or UrlFacade::updateUrlRedirect() instead.
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirect(RedirectTransfer $redirect);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::activateUrlRedirect() instead.
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return void
     */
    public function touchRedirectActive(RedirectTransfer $redirect);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::createUrl() or UrlFacade::updateUrl() instead.
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function saveUrlAndTouch(UrlTransfer $urlTransfer);

    /**
     * @api
     *
     * @deprecated Use UrlFacade::createUrlRedirect() or UrlFacade::updateUrlRedirect() instead.
     *
     * @param \Generated\Shared\Transfer\RedirectTransfer $redirect
     *
     * @return \Generated\Shared\Transfer\RedirectTransfer
     */
    public function saveRedirectAndTouch(RedirectTransfer $redirect);

    /**
     * @api
     *
     * @deprecated This method will be removed with next major release because of invalid dependency direction. Use ProductFacade::getProductUrl() instead.
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    public function getUrlByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);
}
