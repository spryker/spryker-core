<?php


namespace Spryker\Zed\CompanySupplierGui\Communication\Form\DataProvider;


use Generated\Shared\Transfer\SpyCompanyEntityTransfer;
use Spryker\Zed\CompanySupplierGui\Dependency\Facade\CompanySupplierGuiToCompanySupplierFacadeInterface;
use Spryker\Zed\CompanySupplierGui\Communication\Form\CompanySupplierForm;

class CompanySupplierFormDataProvider
{
    /**
     * @var CompanySupplierGuiToCompanySupplierFacadeInterface
     */
    protected $companySupplierFacade;

    /**
     * @param CompanySupplierGuiToCompanySupplierFacadeInterface $companySupplierFacade
     */
    public function __construct(CompanySupplierGuiToCompanySupplierFacadeInterface $companySupplierFacade)
    {
        $this->companySupplierFacade = $companySupplierFacade;
    }

    public function getOptions()
    {
        return [
            CompanySupplierForm::OPTION_VALUES_COMPANY_SUPPLIER => $this->getSuppliersForSelect(),
        ];
    }

    protected function getSuppliersForSelect()
    {
        $result = [];
        /** @var SpyCompanyEntityTransfer $supplier */
        foreach ($this->companySupplierFacade->getAllSuppliers() as $supplier) {
            $result[$supplier->getName()] = $supplier->getIdCompany();
        }

        return $result;
    }
}