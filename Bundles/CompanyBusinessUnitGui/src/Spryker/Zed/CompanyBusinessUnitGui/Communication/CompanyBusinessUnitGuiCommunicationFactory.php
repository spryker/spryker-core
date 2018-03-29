<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitGui\Communication;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\CompanyBusinessUnitForm;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitFormDataProvider;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Table\CompanyBusinessUnitTable;
use Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiDependencyProvider;
use Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\Form\FormInterface;

class CompanyBusinessUnitGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Table\CompanyBusinessUnitTable
     */
    public function createCompanyBusinessUnitTable(): CompanyBusinessUnitTable
    {
        return new CompanyBusinessUnitTable(
            $this->getCompanyBusinessUnitQuery()
        );
    }

    /**
     * @param array|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCompanyBusinessUnitForm($data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CompanyBusinessUnitForm::class, $data, $options);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Communication\Form\DataProvider\CompanyBusinessUnitFormDataProvider
     */
    public function createCompanyBusinessUnitFormDataProvider(): CompanyBusinessUnitFormDataProvider
    {
        return new CompanyBusinessUnitFormDataProvider($this->getCompanyBusinessUnitFacade());
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitGui\Dependency\Facade\CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitGuiDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected function getCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getProvidedDependency(CompanyBusinessUnitGuiDependencyProvider::PROPEL_QUERY_COMPANY_BUSINESS_UNIT);
    }
}
