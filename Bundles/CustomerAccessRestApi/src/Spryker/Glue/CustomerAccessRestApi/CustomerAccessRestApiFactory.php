<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomerAccessRestApi;

use Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess\CustomerAccessRequestValidator;
use Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess\CustomerAccessRequestValidatorInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiConfig getConfig()
 */
class CustomerAccessRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CustomerAccessRestApi\Processor\CustomerAccess\CustomerAccessRequestValidatorInterface
     */
    public function createCustomerAccessRequestValidator(): CustomerAccessRequestValidatorInterface
    {
        return new CustomerAccessRequestValidator($this->getConfig());
    }
}
