<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Deleter;

use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCriteriaTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\WarehouseUser\Business\Mapper\WarehouseUserAssignmentCriteriaMapperInterface;
use Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface;
use Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface;

class WarehouseUserAssignmentDeleter implements WarehouseUserAssignmentDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface
     */
    protected WarehouseUserEntityManagerInterface $warehouseUserEntityManager;

    /**
     * @var \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface
     */
    protected WarehouseUserRepositoryInterface $warehouseUserRepository;

    /**
     * @var \Spryker\Zed\WarehouseUser\Business\Mapper\WarehouseUserAssignmentCriteriaMapperInterface
     */
    protected WarehouseUserAssignmentCriteriaMapperInterface $warehouseUserAssignmentCriteriaMapper;

    /**
     * @param \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserEntityManagerInterface $warehouseUserEntityManager
     * @param \Spryker\Zed\WarehouseUser\Persistence\WarehouseUserRepositoryInterface $warehouseUserRepository
     * @param \Spryker\Zed\WarehouseUser\Business\Mapper\WarehouseUserAssignmentCriteriaMapperInterface $warehouseUserAssignmentCriteriaMapper
     */
    public function __construct(
        WarehouseUserEntityManagerInterface $warehouseUserEntityManager,
        WarehouseUserRepositoryInterface $warehouseUserRepository,
        WarehouseUserAssignmentCriteriaMapperInterface $warehouseUserAssignmentCriteriaMapper
    ) {
        $this->warehouseUserEntityManager = $warehouseUserEntityManager;
        $this->warehouseUserRepository = $warehouseUserRepository;
        $this->warehouseUserAssignmentCriteriaMapper = $warehouseUserAssignmentCriteriaMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function deleteWarehouseUserAssignmentCollection(
        WarehouseUserAssignmentCollectionDeleteCriteriaTransfer $warehouseUserAssignmentCollectionDeleteCriteriaTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        $warehouseUserAssignmentCriteriaTransfer = $this->warehouseUserAssignmentCriteriaMapper
            ->mapWarehouseUserAssignmentCollectionDeleteCriteriaTransferToWarehouseUserAssignmentCriteriaTransfer(
                $warehouseUserAssignmentCollectionDeleteCriteriaTransfer,
                new WarehouseUserAssignmentCriteriaTransfer(),
            );
        $warehouseUserAssignmentCollectionTransfer = $this->warehouseUserRepository->getWarehouseUserAssignmentCollection($warehouseUserAssignmentCriteriaTransfer);

        return $this->getTransactionHandler()->handleTransaction(function () use ($warehouseUserAssignmentCollectionTransfer) {
            return $this->executeDeleteWarehouseUserCollectionTransaction($warehouseUserAssignmentCollectionTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    protected function executeDeleteWarehouseUserCollectionTransaction(
        WarehouseUserAssignmentCollectionTransfer $warehouseUserAssignmentCollectionTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        $this->warehouseUserEntityManager->deleteWarehouseUserAssignments($warehouseUserAssignmentCollectionTransfer);

        return (new WarehouseUserAssignmentCollectionResponseTransfer())
            ->setWarehouseUserAssignments($warehouseUserAssignmentCollectionTransfer->getWarehouseUserAssignments());
    }
}
