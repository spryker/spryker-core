<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyRolesRestApi;

use Spryker\Glue\CompanyRolesRestApi\Processor\Expander\CompanyRoleExpander;
use Spryker\Glue\CompanyRolesRestApi\Processor\Expander\CompanyRoleExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CompanyRolesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyRolesRestApi\Processor\Expander\CompanyRoleExpanderInterface
     */
    public function createCompanyRoleExpander(): CompanyRoleExpanderInterface
    {
        return new CompanyRoleExpander();
    }
}
