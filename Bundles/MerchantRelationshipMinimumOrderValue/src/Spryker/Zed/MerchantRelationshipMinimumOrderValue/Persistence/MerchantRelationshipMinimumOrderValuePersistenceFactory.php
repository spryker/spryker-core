<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence;

use Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValueQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\Propel\Mapper\MerchantRelationshipMinimumOrderValueMapper;
use Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\Propel\Mapper\MerchantRelationshipMinimumOrderValueMapperInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValue\MerchantRelationshipMinimumOrderValueConfig getConfig()
 */
class MerchantRelationshipMinimumOrderValuePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantRelationshipMinimumOrderValue\Persistence\SpyMerchantRelationshipMinimumOrderValueQuery
     */
    public function createMerchantRelationshipMinimumOrderValueQuery(): SpyMerchantRelationshipMinimumOrderValueQuery
    {
        return SpyMerchantRelationshipMinimumOrderValueQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValue\Persistence\Propel\Mapper\MerchantRelationshipMinimumOrderValueMapperInterface
     */
    public function createMerchantRelationshipMinimumOrderValueMapper(): MerchantRelationshipMinimumOrderValueMapperInterface
    {
        return new MerchantRelationshipMinimumOrderValueMapper();
    }
}
