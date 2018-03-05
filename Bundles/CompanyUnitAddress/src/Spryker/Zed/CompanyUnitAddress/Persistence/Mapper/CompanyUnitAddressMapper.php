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
    protected $companyUnitAddressEntityHydratorPlugins;

    protected $companyUnitAddressTransferHydratorPlugins;

    public function __construct(
        array $companyUnitAddressEntityHydratorPlugins,
        array $companyUnitAddressTransferHydratorPlugins
    ) {
        $this->companyUnitAddressEntityHydratorPlugins = $companyUnitAddressEntityHydratorPlugins;
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

        $this->executeAddressEntityTransferHydratorPlugins($spyCompanyUnitAddressEntityTransfer);

        return $spyCompanyUnitAddressEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer $addressEntityTransfer
     *
     * @return void
     */
    protected function executeAddressEntityTransferHydratorPlugins(SpyCompanyUnitAddressEntityTransfer $addressEntityTransfer): void
    {
        foreach ($this->companyUnitAddressEntityHydratorPlugins as $plugin) {
            $plugin->hydrate($addressEntityTransfer);
        }
    }

    /**
     * @return void
     */
    protected function executeAddressTransferHydratorPlugins(CompanyUnitAddressTransfer $addressTransfer): void
    {
        foreach ($this->companyUnitAddressTransferHydratorPlugins as $plugin) {
            $plugin->hydrate($addressTransfer);
        }
    }
}
