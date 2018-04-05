<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplier\Persistence;

use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Generated\Shared\Transfer\CompanyTypeCollectionTransfer;
use Generated\Shared\Transfer\SpyCompanyTypeEntityTransfer;

interface CompanySupplierRepositoryInterface
{
    /**
     * Specification:
     * - Retrieves collection of company types
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanyTypeCollectionTransfer
     */
    public function getCompanyTypes(): CompanyTypeCollectionTransfer;

    /**
     * Specification:
     * - Get all companies with 'supplier' type
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanySupplierCollectionTransfer
     */
    public function getAllSuppliers(): CompanySupplierCollectionTransfer;

    /**
     * Specification:
     * - Get supplier companies for the concrete product
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\CompanySupplierCollectionTransfer
     */
    public function getSuppliersByIdProduct(int $idProduct): CompanySupplierCollectionTransfer;

    /**
     * Specification:
     * - Gets company type by id
     *
     * @api
     *
     * @param int $idCompanyType
     *
     * @return \Generated\Shared\Transfer\SpyCompanyTypeEntityTransfer
     */
    public function getCompanyTypeByIdCompanyType(int $idCompanyType): SpyCompanyTypeEntityTransfer;
}
