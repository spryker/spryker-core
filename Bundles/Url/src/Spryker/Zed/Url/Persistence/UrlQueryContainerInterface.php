<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Persistence;

use Orm\Zed\Url\Persistence\SpyUrlRedirectQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;

interface UrlQueryContainerInterface
{

    /**
     * @param string $url
     *
     * @return SpyUrlQuery
     */
    public function queryUrl($url);

    /**
     * @param int $id
     *
     * @return SpyUrlQuery
     */
    public function queryUrlById($id);

    /**
     * @return SpyUrlQuery
     */
    public function queryUrls();

    /**
     * @return SpyUrlRedirectQuery
     */
    public function queryRedirects();

    /**
     * @param int $idUrlRedirect
     *
     * @return SpyUrlRedirectQuery
     */
    public function queryRedirectById($idUrlRedirect);

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale);

}
