<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Mapper;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;

class CompanyUnitAddressMapper implements CompanyUnitAddressMapperInterface
{
    /**
     * @var array
     */
    protected $companyUnitAddressTransferHydratorPlugins;

    /**
     * @param array $companyUnitAddressTransferHydratorPlugins
     */
    public function __construct(
        array $companyUnitAddressTransferHydratorPlugins
    ) {
        $this->companyUnitAddressTransferHydratorPlugins = $companyUnitAddressTransferHydratorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $unitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function mapEntityTransferToCompanyUnitAddressTransfer(
        SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer,
        CompanyUnitAddressTransfer $unitAddressTransfer
    ): CompanyUnitAddressTransfer {

        $addressTransfer = (new CompanyUnitAddressTransfer())->fromArray(
            $unitAddressEntityTransfer->toArray(),
            true
        );

        $this->executeAddressTransferHydratorPlugins($addressTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer
     */
    public function mapCompanyUnitAddressTransferToEntityTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        SpyCompanyUnitAddressEntityTransfer $unitAddressEntityTransfer
    ): SpyCompanyUnitAddressEntityTransfer {
        $spyCompanyUnitAddressEntityTransfer = (new SpyCompanyUnitAddressEntityTransfer())->fromArray(
            $companyUnitAddressTransfer->modifiedToArray(),
            true
        );

        return $spyCompanyUnitAddressEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $addressTransfer
     *
     * @return void
     */
    protected function executeAddressTransferHydratorPlugins(CompanyUnitAddressTransfer $addressTransfer): void
    {
        foreach ($this->companyUnitAddressTransferHydratorPlugins as $plugin) {
            $plugin->hydrate($addressTransfer);
        }
    }
}
