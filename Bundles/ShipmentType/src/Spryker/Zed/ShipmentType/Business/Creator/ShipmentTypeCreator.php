<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ShipmentType\Business\Grouper\ShipmentTypeGrouperInterface;
use Spryker\Zed\ShipmentType\Business\Validator\ShipmentTypeValidatorInterface;
use Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface;

class ShipmentTypeCreator implements ShipmentTypeCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface
     */
    protected ShipmentTypeEntityManagerInterface $shipmentTypeEntityManager;

    /**
     * @var \Spryker\Zed\ShipmentType\Business\Validator\ShipmentTypeValidatorInterface
     */
    protected ShipmentTypeValidatorInterface $shipmentTypeValidator;

    /**
     * @var \Spryker\Zed\ShipmentType\Business\Creator\ShipmentTypeStoreRelationCreatorInterface
     */
    protected ShipmentTypeStoreRelationCreatorInterface $shipmentTypeStoreRelationCreator;

    /**
     * @var \Spryker\Zed\ShipmentType\Business\Grouper\ShipmentTypeGrouperInterface
     */
    protected ShipmentTypeGrouperInterface $shipmentTypeGrouper;

    /**
     * @param \Spryker\Zed\ShipmentType\Persistence\ShipmentTypeEntityManagerInterface $shipmentTypeEntityManager
     * @param \Spryker\Zed\ShipmentType\Business\Validator\ShipmentTypeValidatorInterface $shipmentTypeValidator
     * @param \Spryker\Zed\ShipmentType\Business\Creator\ShipmentTypeStoreRelationCreatorInterface $shipmentTypeStoreRelationCreator
     * @param \Spryker\Zed\ShipmentType\Business\Grouper\ShipmentTypeGrouperInterface $shipmentTypeGrouper
     */
    public function __construct(
        ShipmentTypeEntityManagerInterface $shipmentTypeEntityManager,
        ShipmentTypeValidatorInterface $shipmentTypeValidator,
        ShipmentTypeStoreRelationCreatorInterface $shipmentTypeStoreRelationCreator,
        ShipmentTypeGrouperInterface $shipmentTypeGrouper
    ) {
        $this->shipmentTypeEntityManager = $shipmentTypeEntityManager;
        $this->shipmentTypeValidator = $shipmentTypeValidator;
        $this->shipmentTypeStoreRelationCreator = $shipmentTypeStoreRelationCreator;
        $this->shipmentTypeGrouper = $shipmentTypeGrouper;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionResponseTransfer
     */
    public function createShipmentTypeCollection(
        ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
    ): ShipmentTypeCollectionResponseTransfer {
        $this->assertRequiredFields($shipmentTypeCollectionRequestTransfer);

        $shipmentTypeCollectionResponseTransfer = $this->shipmentTypeValidator->validateCollection($shipmentTypeCollectionRequestTransfer);

        if ($shipmentTypeCollectionRequestTransfer->getIsTransactional() && $shipmentTypeCollectionResponseTransfer->getErrors()->count()) {
            return $shipmentTypeCollectionResponseTransfer;
        }

        [$validShipmentTypeTransfers, $invalidShipmentTypeTransfers] = $this->shipmentTypeGrouper->groupShipmentTypeTransfersByValidity(
            $shipmentTypeCollectionResponseTransfer,
        );

        $persistedShipmentTypeTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validShipmentTypeTransfers) {
            return $this->executeCreateShipmentTypeCollectionTransaction($validShipmentTypeTransfers);
        });

        return $shipmentTypeCollectionResponseTransfer->setShipmentTypes(
            $this->shipmentTypeGrouper->mergeShipmentTypeTransfers($persistedShipmentTypeTransfers, $invalidShipmentTypeTransfers),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer>
     */
    protected function executeCreateShipmentTypeCollectionTransaction(
        ArrayObject $shipmentTypeTransfers
    ): ArrayObject {
        $persistedShipmentTypeTransfers = new ArrayObject();
        foreach ($shipmentTypeTransfers as $entityIdentifier => $shipmentTypeTransfer) {
            $shipmentTypeTransfer = $this->shipmentTypeEntityManager->createShipmentType($shipmentTypeTransfer);
            $shipmentTypeTransfer = $this->shipmentTypeStoreRelationCreator->createShipmentTypeStoreRelations($shipmentTypeTransfer);

            $persistedShipmentTypeTransfers->offsetSet(
                $entityIdentifier,
                $shipmentTypeTransfer,
            );
        }

        return $persistedShipmentTypeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(ShipmentTypeCollectionRequestTransfer $shipmentTypeCollectionRequestTransfer): void
    {
        $shipmentTypeCollectionRequestTransfer
            ->requireIsTransactional()
            ->requireShipmentTypes();

        foreach ($shipmentTypeCollectionRequestTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            $shipmentTypeTransfer
                ->requireKey()
                ->requireName()
                ->requireIsActive()
                ->requireStoreRelation();

            $this->assertRequiredStoreRelationFields($shipmentTypeTransfer->getStoreRelationOrFail());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    protected function assertRequiredStoreRelationFields(StoreRelationTransfer $storeRelationTransfer): void
    {
        $storeRelationTransfer->requireStores();

        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeTransfer->requireName();
        }
    }
}
