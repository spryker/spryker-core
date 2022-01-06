<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress;

interface CompanyUnitAddressMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function mapCompanyUnitAddressEntityTransferToCompanyUnitAddressTransfer(
        SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer,
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer
     */
    public function mapCompanyUnitAddressTransferToEntityTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        SpyCompanyUnitAddressEntityTransfer $companyUnitAddressEntityTransfer
    ): SpyCompanyUnitAddressEntityTransfer;

    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress $companyUnitAddressEntity
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function mapCompanyUnitAddressEntityToCompanyUnitAddressTransfer(
        SpyCompanyUnitAddress $companyUnitAddressEntity,
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress $companyUnitAddressEntity
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress
     */
    public function mapCompanyUnitAddressTransferToCompanyUnitAddressEntity(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        SpyCompanyUnitAddress $companyUnitAddressEntity
    ): SpyCompanyUnitAddress;

    /**
     * @param array<\Generated\Shared\Transfer\SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer> $companyUnitAddressToCompanyBusinessUnitEntities
     *
     * @return array<\Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer>
     */
    public function mapEntitiesToCompanyBusinessUnitTransfers(
        array $companyUnitAddressToCompanyBusinessUnitEntities
    ): array;
}
