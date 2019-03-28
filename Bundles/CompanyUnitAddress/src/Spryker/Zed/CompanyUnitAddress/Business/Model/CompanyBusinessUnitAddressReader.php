<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Model;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface;

class CompanyBusinessUnitAddressReader implements CompanyBusinessUnitAddressReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressPluginExecutorInterface
     */
    protected $companyUnitAddressPluginExecutor;

    /**
     * @param \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface $repository
     * @param \Spryker\Zed\CompanyUnitAddress\Business\Model\CompanyUnitAddressPluginExecutorInterface $companyUnitAddressPluginExecutor
     */
    public function __construct(
        CompanyUnitAddressRepositoryInterface $repository,
        CompanyUnitAddressPluginExecutorInterface $companyUnitAddressPluginExecutor
    ) {
        $this->repository = $repository;
        $this->companyUnitAddressPluginExecutor = $companyUnitAddressPluginExecutor;
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
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressById(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        $companyUnitAddress = $this->repository->getCompanyUnitAddressById($companyUnitAddressTransfer);
        $companyUnitAddress = $this->companyUnitAddressPluginExecutor
            ->executeCompanyUnitAddressHydratorPlugins($companyUnitAddress);

        return $companyUnitAddress;
    }

    /**
     * @param int $idCompanyUnitAddress
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|null
     */
    public function findCompanyUnitAddressById(int $idCompanyUnitAddress): ?CompanyUnitAddressTransfer
    {
        $companyUnitAddressTransfer = $this->repository->findCompanyUnitAddressById($idCompanyUnitAddress);

        if (!$companyUnitAddressTransfer) {
            return null;
        }

        return $this->companyUnitAddressPluginExecutor->executeCompanyUnitAddressHydratorPlugins($companyUnitAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    public function findCompanyBusinessUnitAddressByUuid(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressResponseTransfer
    {
        $companyUnitAddressResponseTransfer = new CompanyUnitAddressResponseTransfer();
        $companyUnitAddressTransfer = $this->repository->findCompanyBusinessUnitAddressByUuid($companyUnitAddressTransfer);
        if (!$companyUnitAddressTransfer) {
            return $companyUnitAddressResponseTransfer->setIsSuccessful(false);
        }

        $companyUnitAddressTransfer = $this->companyUnitAddressPluginExecutor
            ->executeCompanyUnitAddressHydratorPlugins($companyUnitAddressTransfer);

        return $companyUnitAddressResponseTransfer
            ->setIsSuccessful(true)
            ->setCompanyUnitAddressTransfer($companyUnitAddressTransfer);
    }
}
