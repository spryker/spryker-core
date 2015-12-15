<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Persistence;

use Orm\Zed\Url\Persistence\SpyRedirectQuery;
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
     * @return SpyRedirectQuery
     */
    public function queryRedirects();

    /**
     * @param int $idRedirect
     *
     * @return SpyRedirectQuery
     */
    public function queryRedirectById($idRedirect);

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale);

}
