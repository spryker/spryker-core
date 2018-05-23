<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Persistence;

use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantRelationship\Persistence\Mapper\MerchantRelationshipMapper;
use Spryker\Zed\MerchantRelationship\Persistence\Mapper\MerchantRelationshipMapperInterface;

/**
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    public function createMerchantRelationshipQuery()
    {
        return SpyMerchantRelationshipQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery
     */
    public function createMerchantRelationshipToCompanyBusinessUnitQuery()
    {
        return SpyMerchantRelationshipToCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Persistence\Mapper\MerchantRelationshipMapperInterface
     */
    public function createMerchantRelationshipMapper(): MerchantRelationshipMapperInterface
    {
        return new MerchantRelationshipMapper();
    }
}
