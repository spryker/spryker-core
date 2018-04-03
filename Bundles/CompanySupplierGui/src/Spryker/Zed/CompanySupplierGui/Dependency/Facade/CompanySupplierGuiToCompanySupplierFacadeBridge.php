<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Dependency\Facade;

use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Generated\Shared\Transfer\CompanyTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyCompanyTypeEntityTransfer;

class CompanySupplierGuiToCompanySupplierFacadeBridge implements CompanySupplierGuiToCompanySupplierFacadeInterface
{
    /**
     * @var \Spryker\Zed\CompanySupplier\Business\CompanySupplierFacadeInterface
     */
    protected $companySupplierFacade;

    /**
     * @param \Spryker\Zed\CompanySupplier\Business\CompanySupplierFacadeInterface $companySupplierFacade
     */
    public function __construct($companySupplierFacade)
    {
        $this->companySupplierFacade = $companySupplierFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanySupplierCollectionTransfer
     */
    public function getAllSuppliers(): CompanySupplierCollectionTransfer
    {
        return $this->companySupplierFacade->getAllSuppliers();
    }

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\CompanySupplierCollectionTransfer
     */
    public function getSuppliersByIdProduct(int $idProduct): CompanySupplierCollectionTransfer
    {
        return $this->companySupplierFacade->getSuppliersByIdProduct($idProduct);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    public function saveCompanySupplierRelationsForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $this->companySupplierFacade->saveCompanySupplierRelationsForProductConcrete($productConcreteTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyTypeCollectionTransfer
     */
    public function getCompanyTypes(): CompanyTypeCollectionTransfer
    {
        return $this->companySupplierFacade->getCompanyTypes();
    }

    /**
     * @param int $idCompanyType
     *
     * @return \Generated\Shared\Transfer\SpyCompanyTypeEntityTransfer
     */
    public function getCompanyTypeByIdCompanyType(int $idCompanyType): SpyCompanyTypeEntityTransfer
    {
        return $this->companySupplierFacade->getCompanyTypeByIdCompanyType($idCompanyType);
    }
}
