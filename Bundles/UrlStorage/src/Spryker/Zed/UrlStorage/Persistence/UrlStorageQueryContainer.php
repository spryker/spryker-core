<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Persistence;

use Generated\Shared\Transfer\UrlStorageTransfer;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStoragePersistenceFactory getFactory()
 */
class UrlStorageQueryContainer extends AbstractQueryContainer implements UrlStorageQueryContainerInterface
{
    /**
     * @api
     *
     * @param array $urlIds
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrls(array $urlIds)
    {
        $queryUrl = $this->getFactory()
            ->getUrlQueryContainer()
            ->queryUrls();

        if ($urlIds !== []) {
            $queryUrl->filterByIdUrl_In($urlIds);
        }

        $queryUrl->setFormatter(ModelCriteria::FORMAT_ARRAY);

        return $queryUrl;
    }

    /**
     * @api
     *
     * @param array $urlIds
     *
     * @return \Orm\Zed\UrlStorage\Persistence\SpyUrlStorageQuery
     */
    public function queryUrlStorageByIds(array $urlIds)
    {
        $queryUrl = $this
            ->getFactory()
            ->createSpyStorageUrlQuery()
            ->filterByFkUrl_In($urlIds);

        return $queryUrl;
    }

    /**
     * @api
     *
     * @param string $resourceType
     * @param array $resourceIds
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlsByResourceTypeAndIds($resourceType, array $resourceIds)
    {
        return $this->getFactory()
            ->getUrlQueryContainer()
            ->queryUrlsByResourceTypeAndIds($resourceType, $resourceIds)
            ->useSpyLocaleQuery()
                ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, UrlStorageTransfer::LOCALE_NAME)
            ->endUse()
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);
    }

    /**
     * @api
     *
     * @param array $redirectIds
     *
     * @return \Orm\Zed\UrlStorage\Persistence\SpyUrlRedirectStorageQuery
     */
    public function queryRedirectStorageByIds(array $redirectIds)
    {
        $queryUrlRedirect = $this
            ->getFactory()
            ->createSpyStorageUrlRedirectQuery()
            ->filterByFkUrlRedirect_In($redirectIds);

        return $queryUrlRedirect;
    }

    /**
     * @api
     *
     * @param array $redirectIds
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function queryRedirects(array $redirectIds)
    {
        $queryUrlRedirect = $this
            ->getFactory()
            ->getUrlQueryContainer()
            ->queryRedirects()
            ->filterByIdUrlRedirect_In($redirectIds)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        return $queryUrlRedirect;
    }
}
