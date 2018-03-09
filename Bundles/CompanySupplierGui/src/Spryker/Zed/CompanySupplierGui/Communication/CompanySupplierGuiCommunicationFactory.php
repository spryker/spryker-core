<?php


namespace Spryker\Zed\CompanySupplier\Communication;


use Spryker\Zed\CompanySupplier\CompanySupplierGuiDependencyProvider;
use Spryker\Zed\CompanySupplierGui\Communication\CompanySupplierForm;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanySupplierGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return CompanySupplierGuiToCompanySupplierFacadeInterface
     */
    protected function getCompanySupplierFacade(): CompanySupplierGuiToCompanySupplierFacadeInterface
    {
        return $this->getProvidedDependency(CompanySupplierGuiDependencyProvider::FACADE_COMPANY_SUPPLIER);
    }

    public function createCompanySupplierForm()
    {
        return $this->getFormFactory()->create(
            CompanySupplierForm::class
        );
    }

}