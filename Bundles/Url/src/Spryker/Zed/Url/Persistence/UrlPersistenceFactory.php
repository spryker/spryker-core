<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Url\Persistence;

use Orm\Zed\Url\Persistence\SpyUrl;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Orm\Zed\Url\Persistence\SpyUrlRedirectQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Url\Dependency\UrlToPropelInterface;
use Spryker\Zed\Url\Persistence\Propel\Mapper\UrlMapper;
use Spryker\Zed\Url\UrlDependencyProvider;

/**
 * @method \Spryker\Zed\Url\UrlConfig getConfig()
 * @method \Spryker\Zed\Url\Persistence\UrlQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Url\Persistence\UrlRepositoryInterface getRepository()
 * @method \Spryker\Zed\Url\Persistence\UrlEntityManagerInterface getEntityManager()
 */
class UrlPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function createUrlQuery()
    {
        return SpyUrlQuery::create();
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlRedirectQuery
     */
    public function createUrlRedirectQuery()
    {
        return SpyUrlRedirectQuery::create();
    }

    /**
     * @return \Spryker\Zed\Url\Persistence\Propel\Mapper\UrlMapper
     */
    public function createUrlMapper(): UrlMapper
    {
        return new UrlMapper();
    }

    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function createSpyUrlEntity(): SpyUrl
    {
        return new SpyUrl();
    }

    public function getPropelFacade(): UrlToPropelInterface
    {
        return $this->getProvidedDependency(UrlDependencyProvider::FACADE_PROPEL);
    }
}
