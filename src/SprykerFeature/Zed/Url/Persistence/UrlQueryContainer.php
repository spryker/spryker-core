<?php


namespace SprykerFeature\Zed\Url\Persistence;


use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyRedirectQuery;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrl;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;

class UrlQueryContainer extends AbstractQueryContainer implements UrlQueryContainerInterface
{
    /**
     * @param string $url
     *
     * @return SpyUrl
     */
    public function queryUrl($url)
    {
        $query = SpyUrlQuery::create();
        $query
            ->filterByUrl($url);


        return $query;
    }

    /**
     * @param int $id
     * @return SpyUrlQuery
     */
    public function queryUrlById($id)
    {
        $query = SpyUrlQuery::create();
        $query
            ->filterByIdUrl($id)
        ;

        return $query;
    }

    /**
     * @return SpyUrlQuery
     */
    public function queryUrls()
    {
        $query = SpyUrlQuery::create();

        return $query;
    }

    /**
     * @return SpyRedirectQuery
     */
    public function queryRedirects()
    {
        $query = SpyRedirectQuery::create();

        return $query;
    }

    /**
     * @param int $idRedirect
     *
     * @return SpyRedirectQuery
     */
    public function queryRedirectById($idRedirect)
    {
        $query = SpyRedirectQuery::create();
        $query
            ->filterByIdRedirect($idRedirect)
        ;

        return $query;
    }
}
