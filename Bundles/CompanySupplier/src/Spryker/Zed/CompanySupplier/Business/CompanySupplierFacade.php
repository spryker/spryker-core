<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplier\Business;

use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Generated\Shared\Transfer\CompanyTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyCompanyTypeEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanySupplier\Business\CompanySupplierBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierEntityManagerInterface getEntityManager()
 */
class CompanySupplierFacade extends AbstractFacade implements CompanySupplierFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanyTypeCollectionTransfer
     */
    public function getCompanyTypes(): CompanyTypeCollectionTransfer
    {
        return $this->getRepository()->getCompanyTypes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanySupplierCollectionTransfer
     */
    public function getAllSuppliers(): CompanySupplierCollectionTransfer
    {
        return $this->getRepository()->getAllSuppliers();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\CompanySupplierCollectionTransfer
     */
    public function getSuppliersByIdProduct(int $idProduct): CompanySupplierCollectionTransfer
    {
        return $this->getRepository()->getSuppliersByIdProduct($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function saveCompanySupplierRelationsForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getEntityManager()->saveCompanySupplierRelationsForProductConcrete($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCompanyType
     *
     * @return \Generated\Shared\Transfer\SpyCompanyTypeEntityTransfer
     */
    public function getCompanyTypeByIdCompanyType(int $idCompanyType): SpyCompanyTypeEntityTransfer
    {
        return $this->getRepository()->getCompanyTypeByIdCompanyType($idCompanyType);
    }
}
