<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Communication;

use Spryker\Zed\CompanyUnitAddress\Communication\Expander\CompanyUnitAddressAclEntityConfigurationExpander;
use Spryker\Zed\CompanyUnitAddress\Communication\Expander\CompanyUnitAddressAclEntityConfigurationExpanderInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\CompanyUnitAddressConfig getConfig()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressEntityManagerInterface getEntityManager()
 */
class CompanyUnitAddressCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Communication\Expander\CompanyUnitAddressAclEntityConfigurationExpanderInterface
     */
    public function createCompanyUnitAddressAclEntityConfigurationExpander(): CompanyUnitAddressAclEntityConfigurationExpanderInterface
    {
        return new CompanyUnitAddressAclEntityConfigurationExpander();
    }
}
