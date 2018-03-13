<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface;

class CompanyBusinessUnitAddressReader implements CompanyBusinessUnitAddressReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressHydratePluginInterface[]
     */
    protected $companyUnitAddressHydratingPlugins;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface $repository
     * @param \Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressHydratePluginInterface[] $companyUnitAddressHydratingPlugins
     */
    public function __construct(
        CompanyUnitAddressRepositoryInterface $repository,
        array $companyUnitAddressHydratingPlugins
    ) {
        $this->repository = $repository;
        $this->companyUnitAddressHydratingPlugins = $companyUnitAddressHydratingPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyBusinessUnitAddresses(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyUnitAddressCollectionTransfer {
        $criteriaFilterTransfer = new CompanyUnitAddressCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompanyBusinessUnit(
            $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
        );

        return $this->repository->getCompanyUnitAddressCollection($criteriaFilterTransfer);
    }

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressById(int $idCompanyUnitAddress): CompanyUnitAddressTransfer
    {
        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $companyUnitAddress = $this->repository->getCompanyUnitAddressById($companyUnitAddressTransfer);

        $this->executeCompanyUnitAddressHydratorPlugins($companyUnitAddress);

        return $companyUnitAddress;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    protected function executeCompanyUnitAddressHydratorPlugins(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        foreach ($this->companyUnitAddressHydratingPlugins as $plugin) {
            $plugin->hydrate($companyUnitAddressTransfer);
        }
    }
}
