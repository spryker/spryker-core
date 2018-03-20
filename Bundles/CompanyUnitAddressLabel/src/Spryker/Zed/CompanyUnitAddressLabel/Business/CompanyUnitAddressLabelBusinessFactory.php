<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabel\Business;

use Spryker\Zed\CompanyUnitAddressLabel\Business\Model\CompanyUnitAddressHydrator;
use Spryker\Zed\CompanyUnitAddressLabel\Business\Model\CompanyUnitAddressHydratorInterface;
use Spryker\Zed\CompanyUnitAddressLabel\Business\Model\CompanyUnitAddressLabelRelationSaver;
use Spryker\Zed\CompanyUnitAddressLabel\Business\Model\CompanyUnitAddressLabelRelationSaverInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabel\CompanyUnitAddressLabelConfig getConfig()
 * @method \Spryker\Zed\CompanyUnitAddressLabel\Persistence\CompanyUnitAddressLabelEntityManagerInterface getEntityManager()
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

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Business\Model\CompanyUnitAddressLabelRelationSaverInterface
     */
    public function createCompanyUnitAddressLabelRelationSaver(): CompanyUnitAddressLabelRelationSaverInterface
    {
        return new CompanyUnitAddressLabelRelationSaver(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }
}
