<?php


namespace Spryker\Zed\CompanyBusinessUnitGui\Communication;


use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnitGui\Communication\Table\CompanyBusinessUnitTable;
use Spryker\Zed\CompanyBusinessUnitGui\CompanyBusinessUnitGuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanyBusinessUnitGuiCommunicationFactory extends AbstractCommunicationFactory
{
    public function createCompanyBusinessUnitTable()
    {
        return new CompanyBusinessUnitTable(
            $this->getCompanyBusinessUnitQuery()
        );
    }

    /**
     * @return SpyCompanyBusinessUnitQuery
     */
    protected function getCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getProvidedDependency(CompanyBusinessUnitGuiDependencyProvider::COMPANY_BUSINESS_UNIT_QUERY);
    }
}