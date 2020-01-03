<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserStorage;

use Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Mapper\CompanyUserStorageMapper;
use Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Mapper\CompanyUserStorageMapperInterface;
use Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Provider\CompanyUserStorageProvider;
use Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Provider\CompanyUserStorageProviderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CompanyUserStorage\CompanyUserStorageClientInterface getClient()
 */
class CompanyUserStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Provider\CompanyUserStorageProviderInterface
     */
    public function createStorageCompanyUserProvider(): CompanyUserStorageProviderInterface
    {
        return new CompanyUserStorageProvider(
            $this->getClient(),
            $this->createCompanyUserMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Mapper\CompanyUserStorageMapperInterface
     */
    public function createCompanyUserMapper(): CompanyUserStorageMapperInterface
    {
        return new CompanyUserStorageMapper();
    }
}
