<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Persistence;

use Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery;
use Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\TaxApp\Persistence\Mapper\TaxAppConfigMapper;
use Spryker\Zed\TaxApp\TaxAppDependencyProvider;

/**
 * @method \Spryker\Zed\TaxApp\Persistence\TaxAppRepositoryInterface getRepository()
 * @method \Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\TaxApp\TaxAppConfig getConfig()
 */
class TaxAppPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\TaxApp\Persistence\SpyTaxAppConfigQuery
     */
    public function createTaxAppConfigQuery(): SpyTaxAppConfigQuery
    {
        return SpyTaxAppConfigQuery::create();
    }

    /**
     * @return \Spryker\Zed\TaxApp\Persistence\Mapper\TaxAppConfigMapper
     */
    public function createTaxAppConfigMapper(): TaxAppConfigMapper
    {
        return new TaxAppConfigMapper($this->getUtilEncodingService());
    }

    /**
     * @return \Spryker\Shared\TaxApp\Dependency\Service\TaxAppToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): TaxAppToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(TaxAppDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
