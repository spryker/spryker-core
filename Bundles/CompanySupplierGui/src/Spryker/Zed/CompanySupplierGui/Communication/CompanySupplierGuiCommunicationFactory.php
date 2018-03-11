<?php


namespace Spryker\Zed\CompanySupplierGui\Communication;


use Spryker\Zed\CompanySupplierGui\Communication\Form\CompanySupplierForm;
use Spryker\Zed\CompanySupplierGui\Communication\Form\DataProvider\CompanySupplierFormDataProvider;
use Spryker\Zed\CompanySupplierGui\CompanySupplierGuiDependencyProvider;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class CompanySupplierGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return CompanySupplierGuiToCompanySupplierFacadeInterface
     */
    public function getCompanySupplierFacade(): CompanySupplierGuiToCompanySupplierFacadeInterface
    {
        return $this->getProvidedDependency(CompanySupplierGuiDependencyProvider::FACADE_COMPANY_SUPPLIER);
    }

    public function createCompanySupplierForm()
    {
        return new CompanySupplierForm();
    }

    public function createCompanySupplierFormDataProvider()
    {
        return new CompanySupplierFormDataProvider(
            $this->getCompanySupplierFacade()
        );
    }

}