<?php


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
     * @return CompanyBusinessUnitTable
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
    public function createCompanyBusinessUnitForm($data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(CompanyBusinessUnitForm::class, $data, $options);
    }

    public function createCompanyBusinessUnitFormDataProvider()
    {
        return new CompanyBusinessUnitFormDataProvider($this->getCompanyBusinessUnitFacde());
    }

    /**
     * @return CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacde(): CompanyBusinessUnitGuiToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(CompanyBusinessUnitGuiDependencyProvider::COMPANY_BUSINESS_UNIT_FACADE);
    }

    /**
     * @return SpyCompanyBusinessUnitQuery
     */
    protected function getCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getProvidedDependency(CompanyBusinessUnitGuiDependencyProvider::COMPANY_BUSINESS_UNIT_QUERY);
    }
}