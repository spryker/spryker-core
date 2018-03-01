<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication;

use Spryker\Zed\CompanyUnitAddressGui\Communication\Table\CompanyUnitAddressTable;
use Spryker\Zed\CompanyUnitAddressGui\CompanyUnitAddressGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanyUnitAddressGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Communication\Table\CompanyUnitAddressTable
     */
    public function createAddressTable()
    {
        $cmsBlockQuery = $this->getCompanyUnitAddressGuiQueryContainer()
            ->queryCompanyUnitAddress();

        return new CompanyUnitAddressTable(
            $cmsBlockQuery,
            $this->getCompanyUnitAddressGuiQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressGui\Dependency\QueryContainer\CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerInterface
     */
    public function getCompanyUnitAddressGuiQueryContainer()
    {
        return $this->getProvidedDependency(CompanyUnitAddressGuiDependencyProvider::QUERY_CONTAINER_COMPANY_UNIT_ADDRESS);
    }
}
