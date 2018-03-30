<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelGui\Communication;

use Spryker\Zed\CompanyUnitAddressLabelGui\CompanyUnitAddressLabelGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabelGui\CompanyUnitAddressLabelGuiConfig getConfig()
 */
class CompanyUnitAddressLabelGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabel\Business\CompanyUnitAddressLabelFacadeInterface
     */
    public function getCompanyUnitAddressLabelFacade()
    {
        return $this->getProvidedDependency(CompanyUnitAddressLabelGuiDependencyProvider::FACADE_COMPANY_UNIT_ADDRESS_LABEL);
    }
}
