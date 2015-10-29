<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Url\Persistence\Map\SpyRedirectTableMap;
use Orm\Zed\Url\Persistence\SpyRedirectQuery;
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
     * @return SpyUrl
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
     * @return SpyUrlQuery
     */
    public function queryUrlById($id)
    {
        $query = SpyUrlQuery::create();
        $query->filterByIdUrl($id);

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
     * @return SpyUrlQuery
     */
    public function queryUrlsWithRedirect()
    {
        $query = SpyUrlQuery::create();
        $query->innerJoinSpyRedirect()
            ->withColumn(SpyRedirectTableMap::COL_TO_URL, self::TO_URL)
            ->withColumn(SpyRedirectTableMap::COL_STATUS, self::STATUS)
        ;

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
        $query->filterByIdRedirect($idRedirect);

        return $query;
    }

    /**
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinLocales()
    {
        return $this->queryUrls()
            ->leftJoinSpyLocale(null, Criteria::LEFT_JOIN)
            ->withColumn('locale_name')
            ;
    }

    /**
     * @param int $id
     *
     * @return SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect($id)
    {
        $query = SpyUrlQuery::create();
        $query->leftJoinSpyRedirect()
            ->withColumn(SpyRedirectTableMap::COL_TO_URL, self::TO_URL)
            ->withColumn(SpyRedirectTableMap::COL_STATUS, self::STATUS)
            ->filterByIdUrl($id)
        ;

        return $query;
    }

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
    {
        $query = SpyUrlQuery::create();
        $query->filterByFkResourceCategorynode($idCategoryNode);
        $query->filterByFkLocale($idLocale);

        return $query;
    }

}
