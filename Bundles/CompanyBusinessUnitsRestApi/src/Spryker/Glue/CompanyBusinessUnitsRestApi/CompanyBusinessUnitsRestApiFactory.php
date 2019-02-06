<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitsRestApi;

use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Customer\CustomerSessionExpander;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Customer\CustomerSessionExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyBusinessUnitsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyBusinessUnitsRestApi\Processor\Customer\CustomerSessionExpanderInterface
     */
    public function createCustomerSessionExpander(): CustomerSessionExpanderInterface
    {
        return new CustomerSessionExpander();
    }
}
