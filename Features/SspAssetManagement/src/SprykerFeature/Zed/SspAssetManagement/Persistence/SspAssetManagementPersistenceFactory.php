<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Persistence;

use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use SprykerFeature\Zed\SspAssetManagement\Persistence\Mapper\SspAssetMapper;
use SprykerFeature\Zed\SspAssetManagement\Persistence\Mapper\SspAssetMapperInterface;
use SprykerFeature\Zed\SspAssetManagement\SspAssetManagementDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementEntityManagerInterface getEntityManager()
 */
class SspAssetManagementPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Persistence\Mapper\SspAssetMapper
     */
    public function createAssetMapper(): SspAssetMapperInterface
    {
        return new SspAssetMapper(
            $this->getUtilDateTimeService(),
        );
    }

    /**
     * @return \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery
     */
    public function createSspAssetQuery(): SpySspAssetQuery
    {
        return SpySspAssetQuery::create();
    }

    /**
     * @return \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetToCompanyBusinessUnitQuery
     */
    public function createSspAssetToCompanyBusinessUnitQuery(): SpySspAssetToCompanyBusinessUnitQuery
    {
        return SpySspAssetToCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::UTIL_DATE_TIME_SERVICE);
    }
}
