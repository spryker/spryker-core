<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface;
use Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToStoreFacadeInterface;
use Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface;

class ServicePointStoreRelationCreator implements ServicePointStoreRelationCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToStoreFacadeInterface
     */
    protected ServicePointToStoreFacadeInterface $storeFacade;

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
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface $servicePointEntityManager
     * @param \Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface $servicePointStoreExtractor
     */
    public function __construct(
        ServicePointToStoreFacadeInterface $storeFacade,
        ServicePointEntityManagerInterface $servicePointEntityManager,
        ServicePointStoreExtractorInterface $servicePointStoreExtractor
    ) {
        $this->storeFacade = $storeFacade;
        $this->servicePointEntityManager = $servicePointEntityManager;
        $this->servicePointStoreExtractor = $servicePointStoreExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function createServicePointStoreRelations(ServicePointTransfer $servicePointTransfer): ServicePointTransfer
    {
        $storeNames = $this->servicePointStoreExtractor->extractStoreNamesFromStoreRelationTransfer(
            $servicePointTransfer->getStoreRelationOrFail(),
        );
        $storeTransfers = $this->storeFacade->getStoreTransfersByStoreNames($storeNames);
        $servicePointTransfer->getStoreRelationOrFail()->setStores(new ArrayObject($storeTransfers));
        $storeIds = $this->servicePointStoreExtractor->extractStoreIdsFromStoreTransfers($storeTransfers);

        return $this->getTransactionHandler()->handleTransaction(function () use ($servicePointTransfer, $storeIds) {
            return $this->executeCreateServicePointStoreRelationsTransaction($servicePointTransfer, $storeIds);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param list<int> $storeIds
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    protected function executeCreateServicePointStoreRelationsTransaction(
        ServicePointTransfer $servicePointTransfer,
        array $storeIds
    ): ServicePointTransfer {
        $this->servicePointEntityManager->createServicePointStores(
            $servicePointTransfer->getIdServicePointOrFail(),
            $storeIds,
        );

        return $servicePointTransfer;
    }
}
