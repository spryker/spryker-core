<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Persistence;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\MerchantRegistrationRequest\Persistence\SpyMerchantRegistrationRequestQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestDependencyProvider;
use Spryker\Zed\MerchantRegistrationRequest\Persistence\Mapper\MerchantRegistrationRequestMapper;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig getConfig()
 */
class MerchantRegistrationRequestPersistenceFactory extends AbstractPersistenceFactory
{
    public function createSpyMerchantRegistrationRequestQuery(): SpyMerchantRegistrationRequestQuery
    {
        return SpyMerchantRegistrationRequestQuery::create();
    }

    public function createMerchantRegistrationRequestMapper(): MerchantRegistrationRequestMapper
    {
        return new MerchantRegistrationRequestMapper();
    }

    public function getMerchantPropelQuery(): SpyMerchantQuery
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestDependencyProvider::PROPEL_QUERY_MERCHANT);
    }
}
