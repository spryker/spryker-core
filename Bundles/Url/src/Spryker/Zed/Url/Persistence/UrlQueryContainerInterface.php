<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence;

interface UrlQueryContainerInterface
{

    /**
     * @api
     *
     * @param string $url
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrl($url);

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlById($id);

    /**
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrls();

    /**
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirects();

    /**
     * @api
     *
     * @param int $idUrlRedirect
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirectById($idUrlRedirect);

    /**
     * @api
     *
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale);

}
