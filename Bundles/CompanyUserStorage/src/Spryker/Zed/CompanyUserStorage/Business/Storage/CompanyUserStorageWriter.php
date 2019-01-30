<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Business\Storage;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage;
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
    public function publishByCompanyUserIds(array $companyUserIds): void
    {
        $activeCompanyUserTransfers = $this->companyUserFacade->findActiveCompanyUsers($companyUserIds);
        $indexedCompanyUserStorageEntities = $this->companyUserStorageRepository->findCompanyUserStorageEntities($companyUserIds);

        $this->storeData($activeCompanyUserTransfers, $indexedCompanyUserStorageEntities);
    }

    /**
     * @param array $companyIds
     *
     * @return void
     */
    public function publishByCompanyIds(array $companyIds): void
    {
        $companyUserIds = $this->companyUserFacade->findActiveCompanyUserIdsByCompanyIds($companyIds);

        $this->publishByCompanyUserIds($companyUserIds);
    }

    /**
     * @param array $companyUserIds
     *
     * @return void
     */
    public function unpublishByCompanyUserIds(array $companyUserIds): void
    {
        $companyUserStorageEntities = $this->companyUserStorageRepository->findCompanyUserStorageEntities($companyUserIds);
        $this->deleteStorageEntities($companyUserStorageEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer[] $activeCompanyUserTransfers
     * @param \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[] $indexedCompanyUserStorageEntities
     *
     * @return void
     */
    protected function storeData(array $activeCompanyUserTransfers, array $indexedCompanyUserStorageEntities): void
    {
        foreach ($activeCompanyUserTransfers as $companyUserTransfer) {
            $idCompanyUser = $companyUserTransfer->getIdCompanyUser();
            $companyUserStorageEntity = $this->selectCompanyUserStorageEntity($indexedCompanyUserStorageEntities, $idCompanyUser);

            unset($indexedCompanyUserStorageEntities[$idCompanyUser]);
            $this->storeDataSet($companyUserTransfer, $companyUserStorageEntity);
        }

        $this->deleteStorageEntities($indexedCompanyUserStorageEntities);
    }

    /**
     * @param \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[] $companyUserStorageEntities
     *
     * @return void
     */
    protected function deleteStorageEntities(array $companyUserStorageEntities): void
    {
        foreach ($companyUserStorageEntities as $companyUserStorageEntity) {
            $companyUserStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage $companyUserStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(CompanyUserTransfer $companyUserTransfer, SpyCompanyUserStorage $companyUserStorageEntity): void
    {
        $companyUserStorageTransfer = $this->getCompanyUserStorageTransfer($companyUserTransfer);

        $companyUserStorageEntity->setFkCompanyUser($companyUserTransfer->getIdCompanyUser())
            ->setData($companyUserStorageTransfer->toArray());

        $companyUserStorageEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer
     */
    protected function getCompanyUserStorageTransfer(CompanyUserTransfer $companyUserTransfer): CompanyUserStorageTransfer
    {
        $companyUserStorageTransfer = new CompanyUserStorageTransfer();
        $companyUserStorageTransfer->fromArray($companyUserTransfer->toArray(), true);
        $companyUserStorageTransfer = $this->expandCompanyUserStorageTransfers($companyUserStorageTransfer);

        return $companyUserStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer
     */
    protected function expandCompanyUserStorageTransfers(CompanyUserStorageTransfer $companyUserStorageTransfer): CompanyUserStorageTransfer
    {
        foreach ($this->companyUserStorageExpanderPlugins as $companyUserStorageExpanderPlugin) {
            $companyUserStorageTransfer = $companyUserStorageExpanderPlugin->expand($companyUserStorageTransfer);
        }

        return $companyUserStorageTransfer;
    }

    /**
     * @param \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage[] $indexedCompanyUserStorageEntities
     * @param int $idCompanyUser
     *
     * @return \Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage
     */
    protected function selectCompanyUserStorageEntity(array $indexedCompanyUserStorageEntities, int $idCompanyUser): SpyCompanyUserStorage
    {
        return $indexedCompanyUserStorageEntities[$idCompanyUser] ?? new SpyCompanyUserStorage();
    }
}
