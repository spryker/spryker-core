<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence;

use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\Propel\Mapper\MerchantRelationshipSalesOrderThresholdMapper;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\Propel\Mapper\MerchantRelationshipSalesOrderThresholdMapperInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\MerchantRelationshipSalesOrderThresholdConfig getConfig()
 */
class MerchantRelationshipSalesOrderThresholdPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery
     */
    public function createMerchantRelationshipSalesOrderThresholdQuery(): SpyMerchantRelationshipSalesOrderThresholdQuery
    {
        return SpyMerchantRelationshipSalesOrderThresholdQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\Propel\Mapper\MerchantRelationshipSalesOrderThresholdMapperInterface
     */
    public function createMerchantRelationshipSalesOrderThresholdMapper(): MerchantRelationshipSalesOrderThresholdMapperInterface
    {
        return new MerchantRelationshipSalesOrderThresholdMapper();
    }
}
