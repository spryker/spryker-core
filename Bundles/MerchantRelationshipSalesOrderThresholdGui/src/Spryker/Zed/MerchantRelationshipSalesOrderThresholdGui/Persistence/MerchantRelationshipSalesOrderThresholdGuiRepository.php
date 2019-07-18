<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Persistence;

use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Persistence\MerchantRelationshipSalesOrderThresholdGuiPersistenceFactory getFactory()
 */
class MerchantRelationshipSalesOrderThresholdGuiRepository extends AbstractRepository implements MerchantRelationshipSalesOrderThresholdGuiRepositoryInterface
{
    /**
     * @uses MerchantRelationship
     * @uses CompanyBusinessUnit
     * @uses Merchant
     * @uses Company
     *
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    public function getMerchantRelationshipTableQuery(): SpyMerchantRelationshipQuery
    {
        /** @var \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery $query */
        $query = $this->getFactory()
            ->getMerchantRelationshipPropelQuery()
            ->joinWithCompanyBusinessUnit()
            ->joinWithMerchant()
            ->joinWith('CompanyBusinessUnit.Company');

        return $query;
    }

    /**
     * @uses SalesOrderThreshold
     * @uses MerchantRelationshipSalesOrderThreshold
     *
     * @param int[] $merchantRelationshipIds
     *
     * @return \Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery
     */
    public function getMerchantRelationshipSalesOrderThresholdTableQuery(array $merchantRelationshipIds): SpyMerchantRelationshipSalesOrderThresholdQuery
    {
        return $this->getFactory()
            ->getMerchantRelationshipSalesOrderThresholdPropelQuery()
            ->joinWithSalesOrderThresholdType()
            ->filterByFkMerchantRelationship_In($merchantRelationshipIds);
    }
}
