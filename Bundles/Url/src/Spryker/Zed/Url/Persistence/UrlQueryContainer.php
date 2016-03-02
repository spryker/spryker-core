<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence;

use Orm\Zed\Url\Persistence\Map\SpyUrlRedirectTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Url\Persistence\UrlPersistenceFactory getFactory()
 */
class UrlQueryContainer extends AbstractQueryContainer implements UrlQueryContainerInterface
{

    const TO_URL = 'toUrl';
    const STATUS = 'status';

    /**
     * @api
     *
     * @param string $url
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function queryUrl($url)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByUrl($url);

        return $query;
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryTranslationById($id)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByIdUrl($id);

        return $query;
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlById($id)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByIdUrl($id);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrls()
    {
        $query = $this->getFactory()->createUrlQuery();

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsWithRedirect()
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->innerJoinSpyUrlRedirect()
            ->withColumn(SpyUrlRedirectTableMap::COL_TO_URL, self::TO_URL)
            ->withColumn(SpyUrlRedirectTableMap::COL_STATUS, self::STATUS);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirects()
    {
        $query = $this->getFactory()->createUrlRedirectQuery();

        return $query;
    }

    /**
     * @api
     *
     * @param int $idUrlRedirect
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirectById($idUrlRedirect)
    {
        $query = $this->getFactory()->createUrlRedirectQuery();
        $query->filterByIdUrlRedirect($idUrlRedirect);

        return $query;
    }

    /**
     * @api
     *
     * @return $this|\Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinLocales()
    {
        return $this->queryUrls()
            ->leftJoinSpyLocale(null, Criteria::LEFT_JOIN)
            ->withColumn('locale_name');
    }

    /**
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdWithRedirect($id)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->leftJoinSpyUrlRedirect()
            ->withColumn(SpyUrlRedirectTableMap::COL_TO_URL, self::TO_URL)
            ->withColumn(SpyUrlRedirectTableMap::COL_STATUS, self::STATUS)
            ->filterByIdUrl($id);

        return $query;
    }

    /**
     * @api
     *
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeAndLocaleId($idCategoryNode, $idLocale)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByFkResourceCategorynode($idCategoryNode);
        $query->filterByFkLocale($idLocale);

        return $query;
    }

}
