<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Persistence;

use Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery;
use Spryker\Zed\DataImportMerchant\DataImportMerchantDependencyProvider;
use Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToUtilEncodingServiceInterface;
use Spryker\Zed\DataImportMerchant\Persistence\Mapper\DataImportMerchantFileMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\DataImportMerchant\DataImportMerchantConfig getConfig()
 * @method \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\DataImportMerchant\Persistence\DataImportMerchantRepositoryInterface getRepository()
 */
class DataImportMerchantPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\DataImportMerchant\Persistence\Mapper\DataImportMerchantFileMapper
     */
    public function createDataImportMerchantFileMapper(): DataImportMerchantFileMapper
    {
        return new DataImportMerchantFileMapper(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Orm\Zed\DataImportMerchant\Persistence\SpyDataImportMerchantFileQuery
     */
    public function createDataImportMerchantFileQuery(): SpyDataImportMerchantFileQuery
    {
        return SpyDataImportMerchantFileQuery::create();
    }

    /**
     * @return \Spryker\Zed\DataImportMerchant\Dependency\Service\DataImportMerchantToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): DataImportMerchantToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(DataImportMerchantDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
