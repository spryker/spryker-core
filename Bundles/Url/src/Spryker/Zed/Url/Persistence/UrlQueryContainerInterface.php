<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface UrlQueryContainerInterface extends QueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $url
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrl($url);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlById($id);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrls();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $resourceType
     * @param array $resourceIds
     *
     * @throws \Spryker\Zed\Url\Persistence\Exception\UnknownResourceTypeException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsByResourceTypeAndIds($resourceType, array $resourceIds);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirects();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idUrlRedirect
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirectById($idUrlRedirect);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect($id);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsWithRedirect();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Will be removed with next major release.
     *
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Category\Persistence\CategoryQueryContainer::queryResourceUrlByCategoryNodeId()} instead.
     *
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeId($idCategoryNode);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $sourceUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryUrlRedirectBySourceUrl($sourceUrl);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryUrlRedirectByIdUrl($idUrl);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIgnoringRedirects();
}
