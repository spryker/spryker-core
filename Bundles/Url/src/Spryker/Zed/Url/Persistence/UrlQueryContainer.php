<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Url\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Url\Persistence\Map\SpyUrlRedirectTableMap;
use Orm\Zed\Url\Persistence\SpyUrlRedirectQuery;
use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\Criteria;

class UrlQueryContainer extends AbstractQueryContainer implements UrlQueryContainerInterface
{

    const TO_URL = 'toUrl';
    const STATUS = 'status';

    /**
     * @param string $url
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function queryUrl($url)
    {
        $query = SpyUrlQuery::create();
        $query->filterByUrl($url);

        return $query;
    }

    public function queryTranslationById($id)
    {
        $query = SpyUrlQuery::create();
        $query->filterByIdUrl($id);

        return $query;
    }

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlById($id)
    {
        $query = SpyUrlQuery::create();
        $query->filterByIdUrl($id);

        return $query;
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrls()
    {
        $query = SpyUrlQuery::create();

        return $query;
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsWithRedirect()
    {
        $query = SpyUrlQuery::create();
        $query->innerJoinSpyUrlRedirect()
            ->withColumn(SpyUrlRedirectTableMap::COL_TO_URL, self::TO_URL)
            ->withColumn(SpyUrlRedirectTableMap::COL_STATUS, self::STATUS);

        return $query;
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirects()
    {
        $query = SpyUrlRedirectQuery::create();

        return $query;
    }

    /**
     * @param int $idUrlRedirect
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirectById($idUrlRedirect)
    {
        $query = SpyUrlRedirectQuery::create();
        $query->filterByIdUrlRedirect($idUrlRedirect);

        return $query;
    }

    /**
     * @return self|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinLocales()
    {
        return $this->queryUrls()
            ->leftJoinSpyLocale(null, Criteria::LEFT_JOIN)
            ->withColumn('locale_name');
    }

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect($id)
    {
        $query = SpyUrlQuery::create();
        $query->leftJoinSpyUrlRedirect()
            ->withColumn(SpyUrlRedirectTableMap::COL_TO_URL, self::TO_URL)
            ->withColumn(SpyUrlRedirectTableMap::COL_STATUS, self::STATUS)
            ->filterByIdUrl($id);

        return $query;
    }

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
    {
        $query = SpyUrlQuery::create();
        $query->filterByFkResourceCategorynode($idCategoryNode);
        $query->filterByFkLocale($idLocale);

        return $query;
    }

}
