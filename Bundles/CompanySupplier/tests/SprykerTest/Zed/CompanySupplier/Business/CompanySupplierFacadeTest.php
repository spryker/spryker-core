<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySupplier\Business;

use ArrayObject;
use Codeception\TestCase\Test;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanySupplier
 * @group Business
 * @group Facade
 * @group CompanySupplierFacadeTest
 * Add your own group annotations below this line
 */
class CompanySupplierFacadeTest extends Test
{
    protected const COMPANY_TYPE_SUPPLIER = 'supplier';

    /**
     * @var \SprykerTest\Zed\CompanySupplier\CompanySupplierTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CompanyTransfer
     */
    protected $companySupplier;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $productConcrete;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setupCompanySupplier();
        $this->prepareProductConcreteWithSupplierCompanies();
    }

    /**
     * @return void
     */
    public function testGetCompanyTypesReturnsNotEmptyCollection(): void
    {
        $companyTypesCollection = $this->tester->getFacade()->getCompanyTypes();
        $this->assertGreaterThan(0, $companyTypesCollection->getCompanyTypes()->count());
    }

    /**
     * @return void
     */
    public function testGetAllSuppliersReturnsCompanySuppliers(): void
    {
        $supplierCompanies = $this->tester->getFacade()->getAllSuppliers();
        $this->assertSame(static::COMPANY_TYPE_SUPPLIER, $supplierCompanies->getSuppliers()[0]->getCompanyType()->getName());
    }

    /**
     * @return void
     */
    public function testSaveCompanySupplierStoreRelations(): void
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
    protected function setupCompanySupplier(): void
    {
        $companyType = $this->tester->haveCompanyType(['name' => static::COMPANY_TYPE_SUPPLIER]);
        $this->companySupplier = $this->tester->haveCompany([
            'fk_company_type' => $companyType->getIdCompanyType(),
        ]);
    }

    /**
     * @return void
     */
    protected function prepareProductConcreteWithSupplierCompanies(): void
    {
        $productConcrete = $this->tester->haveProduct();
        $companySuppliers = new ArrayObject([
            $this->companySupplier,
        ]);
        $productConcrete->setCompanySuppliers($companySuppliers);
        $this->productConcrete = $productConcrete;
    }
}
