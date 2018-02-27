<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress;

class CompanyUnitAddressMapper implements CompanyUnitAddressMapperInterface
{
    /**
     * @param \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress $companyUnitAddressEntity
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function mapCompanyUnitAddressEntityToTransfer(
        SpyCompanyUnitAddress $companyUnitAddressEntity
    ): CompanyUnitAddressTransfer {
        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->fromArray($companyUnitAddressEntity->toArray(), true);

        return $companyUnitAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddress
     */
    public function mapCompanyUnitAddressTransferToEntity(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): SpyCompanyUnitAddress {
        $companyUnitAddressEntity = new SpyCompanyUnitAddress();
        $companyUnitAddressEntity->fromArray($companyUnitAddressTransfer->modifiedToArray());
        $companyUnitAddressEntity->setNew($companyUnitAddressTransfer->getIdCompanyUnitAddress() === null);

        return $companyUnitAddressEntity;
    }
}
