<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFinder;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitPluginExecutorInterface;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;

class CompanyBusinessUnitReader implements CompanyBusinessUnitReaderInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface
     */
    protected $companyBusinessUnitRepository;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitPluginExecutorInterface
     */
    protected $companyBusinessUnitPluginExecutor;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository
     * @param \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor\CompanyBusinessUnitPluginExecutorInterface $transferExpanderPluginExecutor
     */
    public function __construct(
        CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository,
        CompanyBusinessUnitPluginExecutorInterface $transferExpanderPluginExecutor
    ) {
        $this->companyBusinessUnitRepository = $companyBusinessUnitRepository;
        $this->companyBusinessUnitPluginExecutor = $transferExpanderPluginExecutor;
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function getCompanyBusinessUnitById(int $idCompanyBusinessUnit): CompanyBusinessUnitTransfer
    {
        $companyBusinessUnitTransfer = $this->companyBusinessUnitRepository->getCompanyBusinessUnitById($idCompanyBusinessUnit);
        $companyBusinessUnitTransfer = $this->companyBusinessUnitPluginExecutor->executeTransferExpanderPlugins($companyBusinessUnitTransfer);

        return $companyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer $companyBusinessUnitCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyBusinessUnitCollection(CompanyBusinessUnitCriteriaFilterTransfer $companyBusinessUnitCriteriaFilterTransfer): CompanyBusinessUnitCollectionTransfer
    {
        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitRepository
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransferIndex => $companyBusinessUnitTransfer) {
            $companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits()->offsetSet(
                $companyBusinessUnitTransferIndex,
                $this->companyBusinessUnitPluginExecutor->executeTransferExpanderPlugins($companyBusinessUnitTransfer)
            );
        }

        return $companyBusinessUnitCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function findCompanyBusinessUnitByUuid(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitTransfer->requireUuid();

        $companyBusinessUnitTransfer = $this->companyBusinessUnitRepository->findCompanyBusinessUnitByUuid(
            $companyBusinessUnitTransfer->getUuid()
        );

        $companyBusinessUnitResponseTransfer = new CompanyBusinessUnitResponseTransfer();
        if (!$companyBusinessUnitTransfer) {
            return $companyBusinessUnitResponseTransfer->setIsSuccessful(false);
        }

        $companyBusinessUnitTransfer = $this->companyBusinessUnitPluginExecutor->executeTransferExpanderPlugins($companyBusinessUnitTransfer);

        return $companyBusinessUnitResponseTransfer
            ->setIsSuccessful(true)
            ->setCompanyBusinessUnitTransfer($companyBusinessUnitTransfer);
    }
}
