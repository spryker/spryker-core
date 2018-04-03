<?php

namespace SprykerTest\Zed\CompanySupplier\Business;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

class CompanySupplierFacadeTest extends Test
{
    protected const COMPANY_TYPE_SUPPLIER = 'supplier';

    /**
     * @var \SprykerTest\Zed\CompanySupplier\CompanySupplierTester
     */
    protected $tester;

    /**
     * @var CompanyTransfer
     */
    protected $companySupplier;

    /**
     * @var ProductConcreteTransfer
     */
    protected $productConcrete;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->setupCompanySupplier();
        $this->prepareProductConcreteWithSupplierCompanies();
    }

    /**
     * @return void
     */
    public function testGetAllSuppliersReturnsCompanySuppliers()
    {
        $supplierCompanies = $this->tester->getFacade()->getAllSuppliers();
        $this->assertSame(static::COMPANY_TYPE_SUPPLIER, $supplierCompanies->getSuppliers()[0]->getCompanyType()->getName());
    }

    /**
     * @return void
     */
    public function testSaveCompanySupplierStoreRelations()
    {
        $this->tester->getFacade()->saveCompanySupplierRelationsForProductConcrete(
            $this->productConcrete
        );

        $CompanySupplierProductRelations = $this->tester->getFacade()->getSuppliersByIdProduct($this->productConcrete->getIdProductConcrete());

        $this->assertGreaterThan(0, $CompanySupplierProductRelations->getSuppliers()->count());
    }

    /**
     * @return void
     */
    protected function setupCompanySupplier()
    {
        $companyType = $this->tester->getCompanyTypeTransfer(static::COMPANY_TYPE_SUPPLIER);
        $this->companySupplier = $this->tester->haveCompany([
            'fk_company_type' => $companyType->getIdCompanyType()
        ]);
    }

    /**
     * @return void
     */
    protected function prepareProductConcreteWithSupplierCompanies()
    {
        $productConcrete = $this->tester->haveProduct();
        $companySuppliers = new ArrayObject([
            $this->companySupplier
        ]);
        $productConcrete->setCompanySuppliers($companySuppliers);
        $this->productConcrete = $productConcrete;
    }
}