<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface UrlQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param string $url
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrl($url);

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlById($id);

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrls();

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirects();

    /**
     * @param int $idUrlRedirect
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirectById($idUrlRedirect);

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale);

}
