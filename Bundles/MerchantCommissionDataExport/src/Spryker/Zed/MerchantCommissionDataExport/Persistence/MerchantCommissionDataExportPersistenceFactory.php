<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport\Persistence;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportDependencyProvider;
use Spryker\Zed\MerchantCommissionDataExport\Persistence\Propel\Mapper\MerchantCommissionMapper;

/**
 * @method \Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportConfig getConfig()
 * @method \Spryker\Zed\MerchantCommissionDataExport\Persistence\MerchantCommissionDataExportRepositoryInterface getRepository()
 */
class MerchantCommissionDataExportPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Spryker\Zed\MerchantCommissionDataExport\Persistence\Propel\Mapper\MerchantCommissionMapper
     */
    public function createMerchantCommissionMapper(): MerchantCommissionMapper
    {
        return new MerchantCommissionMapper();
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery
     */
    public function getMerchantCommissionPropelQuery(): SpyMerchantCommissionQuery
    {
        return $this->getProvidedDependency(MerchantCommissionDataExportDependencyProvider::PROPEL_QUERY_MERCHANT_COMMISSION);
    }

    /**
     * @return \Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery
     */
    public function getMerchantCommissionAmountPropelQuery(): SpyMerchantCommissionAmountQuery
    {
        return $this->getProvidedDependency(MerchantCommissionDataExportDependencyProvider::PROPEL_QUERY_MERCHANT_COMMISSION_AMOUNT);
    }
}
