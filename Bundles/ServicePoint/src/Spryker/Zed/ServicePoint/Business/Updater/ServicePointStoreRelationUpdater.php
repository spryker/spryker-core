<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface;
use Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToStoreFacadeInterface;
use Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface;
use Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface;

class ServicePointStoreRelationUpdater implements ServicePointStoreRelationUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToStoreFacadeInterface
     */
    protected ServicePointToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface
     */
    protected ServicePointRepositoryInterface $servicePointRepository;

    /**
     * @var \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface
     */
    protected ServicePointEntityManagerInterface $servicePointEntityManager;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface
     */
    protected ServicePointStoreExtractorInterface $servicePointStoreExtractor;

    /**
     * @param \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointRepositoryInterface $servicePointRepository
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface $servicePointEntityManager
     * @param \Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface $servicePointStoreExtractor
     */
    public function __construct(
        ServicePointToStoreFacadeInterface $storeFacade,
        ServicePointRepositoryInterface $servicePointRepository,
        ServicePointEntityManagerInterface $servicePointEntityManager,
        ServicePointStoreExtractorInterface $servicePointStoreExtractor
    ) {
        $this->storeFacade = $storeFacade;
        $this->servicePointRepository = $servicePointRepository;
        $this->servicePointEntityManager = $servicePointEntityManager;
        $this->servicePointStoreExtractor = $servicePointStoreExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function updateServicePointStoreRelations(ServicePointTransfer $servicePointTransfer): ServicePointTransfer
    {
        $requestedStoreNames = $this->servicePointStoreExtractor->extractStoreNamesFromStoreRelationTransfer(
            $servicePointTransfer->getStoreRelationOrFail(),
        );
        $requestedStoreTransfers = $this->storeFacade->getStoreTransfersByStoreNames($requestedStoreNames);
        $servicePointTransfer->getStoreRelationOrFail()->setStores(new ArrayObject($requestedStoreTransfers));
        $requestedStoreIds = $this->servicePointStoreExtractor->extractStoreIdsFromStoreTransfers($requestedStoreTransfers);

        $assignedStoreTransfers = $this->servicePointRepository->getServicePointStoresGroupedByIdServicePoint([
            $servicePointTransfer->getIdServicePointOrFail(),
        ]);
        $assignedStoreIds = $this->servicePointStoreExtractor->extractStoreIdsFromStoreTransfers(
            $assignedStoreTransfers[$servicePointTransfer->getIdServicePointOrFail()],
        );

        $storeIdsToAssign = array_diff($requestedStoreIds, $assignedStoreIds);
        $storeIdsToUnassign = array_diff($assignedStoreIds, $requestedStoreIds);

        return $this->getTransactionHandler()->handleTransaction(
            function () use ($servicePointTransfer, $storeIdsToAssign, $storeIdsToUnassign) {
                return $this->executeUpdateServicePointStoreRelationsTransaction(
                    $servicePointTransfer,
                    $storeIdsToAssign,
                    $storeIdsToUnassign,
                );
            },
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param list<int> $storeIdsToAssign
     * @param list<int> $storeIdsToUnassign
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    protected function executeUpdateServicePointStoreRelationsTransaction(
        ServicePointTransfer $servicePointTransfer,
        array $storeIdsToAssign,
        array $storeIdsToUnassign
    ): ServicePointTransfer {
        if ($storeIdsToAssign) {
            $this->servicePointEntityManager->createServicePointStores(
                $servicePointTransfer->getIdServicePointOrFail(),
                $storeIdsToAssign,
            );
        }

        if ($storeIdsToUnassign) {
            $this->servicePointEntityManager->deleteServicePointStores(
                $servicePointTransfer->getIdServicePointOrFail(),
                $storeIdsToUnassign,
            );
        }

        return $servicePointTransfer;
    }
}
