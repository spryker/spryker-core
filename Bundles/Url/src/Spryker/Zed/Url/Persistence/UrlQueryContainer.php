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
    public const TO_URL = 'toUrl';
    public const STATUS = 'status';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $url
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrl($url)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByUrl($url);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $resourceType
     * @param array $resourceIds
     *
     * @throws Exception\UnknownResourceTypeException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsByResourceTypeAndIds($resourceType, array $resourceIds)
    {
        return $this->getFactory()
            ->createUrlQuery()
            ->filterByResourceTypeAndIds($resourceType, $resourceIds);
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function joinLocales()
    {
        return $this->queryUrls()
            ->leftJoinSpyLocale()
            ->withColumn('locale_name');
    }

    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Category\Persistence\CategoryQueryContainer::queryResourceUrlByCategoryNodeAndLocaleId()} instead.
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Category\Persistence\CategoryQueryContainer::queryResourceUrlByCategoryNodeId()} instead.
     *
     * @param int $idCategoryNode
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCategoryNodeId($idCategoryNode)
    {
        $query = $this->getFactory()->createUrlQuery();
        $query->filterByFkResourceCategorynode($idCategoryNode);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sourceUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryUrlRedirectBySourceUrl($sourceUrl)
    {
        return $this->getFactory()
            ->createUrlRedirectQuery()
            ->useSpyUrlQuery()
                ->filterByUrl($sourceUrl)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idUrl
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryUrlRedirectByIdUrl($idUrl)
    {
        return $this->getFactory()
            ->createUrlRedirectQuery()
            ->useSpyUrlQuery()
                ->filterByIdUrl($idUrl)
            ->endUse();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIgnoringRedirects()
    {
        return $this->getFactory()
            ->createUrlQuery()
            ->filterByFkResourceRedirect(null, Criteria::ISNULL);
    }
}
