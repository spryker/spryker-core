<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Business;

use Spryker\Zed\CompanyUnitAddressLabel\Business\Model\CompanyUnitAddressHydrator;
use Spryker\Zed\CompanyUnitAddressLabel\Business\Model\CompanyUnitAddressHydratorInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\CompanyUnitAddressLabelConfig getConfig()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelRepositoryInterface getRepository()
 */
class CompanyUnitAddressLabelBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Business\Model\CompanyUnitAddressHydratorInterface
     */
    public function createCompanyUnitAddressHydrator(): CompanyUnitAddressHydratorInterface
    {
        return new CompanyUnitAddressHydrator(
            $this->getRepository()
        );
    }
}
