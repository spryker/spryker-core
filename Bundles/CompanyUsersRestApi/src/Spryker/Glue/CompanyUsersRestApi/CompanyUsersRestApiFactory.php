<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi;

use Spryker\Glue\CompanyUsersRestApi\Processor\Header\CompanyUserHeaderValidator;
use Spryker\Glue\CompanyUsersRestApi\Processor\Header\CompanyUserHeaderValidatorInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig getConfig()
 */
class CompanyUsersRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUsersRestApi\Processor\Header\CompanyUserHeaderValidatorInterface
     */
    public function createCompanyUserHeaderValidator(): CompanyUserHeaderValidatorInterface
    {
        return new CompanyUserHeaderValidator(
            $this->getConfig()
        );
    }
}
