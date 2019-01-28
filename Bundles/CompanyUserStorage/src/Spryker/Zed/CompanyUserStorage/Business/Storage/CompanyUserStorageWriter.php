<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Business\Storage;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserStorage\Dependency\Facade\CompanyUserStorageToCompanyUserFacadeInterface;
use Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageEntityManagerInterface;
use Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface;

class CompanyUserStorageWriter implements CompanyUserStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserStorage\Dependency\Facade\CompanyUserStorageToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @var \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface
     */
    protected $companyUserStorageRepository;

    /**
     * @var \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageEntityManagerInterface
     */
    protected $companyUserStorageEntityManager;

    /**
     * @var \Spryker\Zed\CompanyUserStorageExtension\Dependency\Plugin\CompanyUserStorageExpanderPluginInterface[]
     */
    protected $companyUserStorageExpanderPlugins;

    /**
     * @param \Spryker\Zed\CompanyUserStorage\Dependency\Facade\CompanyUserStorageToCompanyUserFacadeInterface $companyUserFacade
     * @param \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface $companyUserStorageRepository
     * @param \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageEntityManagerInterface $companyUserStorageEntityManager
     * @param \Spryker\Zed\CompanyUserStorageExtension\Dependency\Plugin\CompanyUserStorageExpanderPluginInterface[] $companyUserStorageExpanderPlugins
     */
    public function __construct(
        CompanyUserStorageToCompanyUserFacadeInterface $companyUserFacade,
        CompanyUserStorageRepositoryInterface $companyUserStorageRepository,
        CompanyUserStorageEntityManagerInterface $companyUserStorageEntityManager,
        array $companyUserStorageExpanderPlugins
    ) {
        $this->companyUserFacade = $companyUserFacade;
        $this->companyUserStorageRepository = $companyUserStorageRepository;
        $this->companyUserStorageEntityManager = $companyUserStorageEntityManager;
        $this->companyUserStorageExpanderPlugins = $companyUserStorageExpanderPlugins;
    }

    /**
     * @param array $companyUserIds
     *
     * @return void
     */
    public function publish(array $companyUserIds): void
    {
        $companyUserTransfers = $this->companyUserFacade->findCompanyUserTransfers($companyUserIds);
        $companyUserStorageTransfers = $this->findCompanyUserStorageTransfers($companyUserIds);
        $mappedCompanyUnitStorageTransfers = $this->mapCompanyUserStorageTransfers($companyUserStorageTransfers);

        $this->storeData($companyUserTransfers, $mappedCompanyUnitStorageTransfers);
    }

    /**
     * @param array $companyUserIds
     *
     * @return void
     */
    public function unpublish(array $companyUserIds): void
    {
        // TODO: Implement unpublish() method.
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer[] $companyUserTransfers
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer[] $mappedCompanyUnitStorageTransfers
     *
     * @return void
     */
    protected function storeData(array $companyUserTransfers, array $mappedCompanyUnitStorageTransfers): void
    {
        foreach ($companyUserTransfers as $companyUserTransfer) {
            $idCompanyUser = $companyUserTransfer->getIdCompanyUser();
            $companyUserStorageTransfer = $this->selectCompanyUserStorageEntity($mappedCompanyUnitStorageTransfers, $idCompanyUser);

            unset($mappedCompanyUnitStorageTransfers[$idCompanyUser]);
            $this->storeDataSet($companyUserTransfer, $companyUserStorageTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return void
     */
    protected function storeDataSet(CompanyUserTransfer $companyUserTransfer, CompanyUserStorageTransfer $companyUserStorageTransfer): void
    {
        $companyUserStorageTransfer->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());
        $companyUserStorageTransfer->setIdCompany($companyUserTransfer->getFkCompany());

        $this->companyUserStorageEntityManager->saveCompanyUserStorage($companyUserStorageTransfer);
    }

    /**
     * @param int[] $companyUserIds
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer[]
     */
    protected function findCompanyUserStorageTransfers(array $companyUserIds): array
    {
        $companyUserStorageTransfers = $this->companyUserStorageRepository->findCompanyUserStorageTransfers($companyUserIds);

        return $this->expandCompanyUserStorageTransfers($companyUserStorageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer[] $companyUserStorageTransfers
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer[]
     */
    protected function expandCompanyUserStorageTransfers(array $companyUserStorageTransfers): array
    {
        foreach ($companyUserStorageTransfers as $companyUserStorageTransfer) {
            foreach ($this->companyUserStorageExpanderPlugins as $companyUserStorageExpanderPlugin) {
                $companyUserStorageTransfer = $companyUserStorageExpanderPlugin->expand($companyUserStorageTransfer);
            }
        }

        return $companyUserStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer
     */
    protected function executeCompanyUserStorageExpanderPlugins(CompanyUserStorageTransfer $companyUserStorageTransfer): CompanyUserStorageTransfer
    {
        foreach ($this->companyUserStorageExpanderPlugins as $companyUserStorageExpanderPlugin) {
            $companyUserStorageTransfer = $companyUserStorageExpanderPlugin->expand($companyUserStorageTransfer);
        }

        return $companyUserStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer[] $companyUserStorageTransfers
     *
     * @return array
     */
    protected function mapCompanyUserStorageTransfers(array $companyUserStorageTransfers): array
    {
        $mappedCompanyUserStorageTransfers = [];
        foreach ($companyUserStorageTransfers as $companyUserStorageTransfer) {
            $mappedCompanyUserStorageTransfers[$companyUserStorageTransfer->getIdCompanyUser()] = $companyUserStorageTransfer;
        }

        return $mappedCompanyUserStorageTransfers;
    }

    /**
     * @param array $mappedCompanyUnitStorageTransfers
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer
     */
    protected function selectCompanyUserStorageEntity(array $mappedCompanyUnitStorageTransfers, int $idCompanyUser): CompanyUserStorageTransfer
    {
        return $mappedCompanyUnitStorageTransfers[$idCompanyUser] ?? new CompanyUserStorageTransfer();
    }
}
