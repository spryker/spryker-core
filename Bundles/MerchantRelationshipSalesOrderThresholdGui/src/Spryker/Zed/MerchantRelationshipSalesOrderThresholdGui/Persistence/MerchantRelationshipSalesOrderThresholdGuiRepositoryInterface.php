<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdGui\Persistence;

use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery;

interface MerchantRelationshipSalesOrderThresholdGuiRepositoryInterface
{
    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    public function getMerchantRelationshipTableQuery(): SpyMerchantRelationshipQuery;

    /**
     * @param int[] $merchantRelationshipIds
     *
     * @return \Orm\Zed\MerchantRelationshipSalesOrderThreshold\Persistence\SpyMerchantRelationshipSalesOrderThresholdQuery
     */
    public function getMerchantRelationshipSalesOrderThresholdTableQuery(array $merchantRelationshipIds): SpyMerchantRelationshipSalesOrderThresholdQuery;
}
