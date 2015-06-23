<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Persistence;


use SprykerFeature\Zed\Url\Persistence\Propel\SpyRedirectQuery;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;

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
}
