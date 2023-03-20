<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Validator\Rules;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockCriteriaFilterTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;
use Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface;
use Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface;

class WarehouseExistsValidatorRule implements WarehouseUserAssignmentValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND = 'warehouse_user_assignment.validation.warehouse_not_found';

    /**
     * @var \Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface
     */
    protected WarehouseUserToStockFacadeInterface $stockFacade;

    /**
     * @var \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface
     */
    protected WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder;

    /**
     * @param \Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToStockFacadeInterface $stockFacade
     * @param \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder
     */
    public function __construct(
        WarehouseUserToStockFacadeInterface $stockFacade,
        WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder
    ) {
        $this->stockFacade = $stockFacade;
        $this->warehouseUserAssignmentIdentifierBuilder = $warehouseUserAssignmentIdentifierBuilder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function validateCollection(
        ArrayObject $warehouseUserAssignmentTransfers,
        WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        $stockCriteriaFilterTransfer = $this->createStockCriteriaFilterTransfer($warehouseUserAssignmentTransfers);
        $stockCollectionTransfer = $this->stockFacade->getStocksByStockCriteriaFilter($stockCriteriaFilterTransfer);

        return $this->validateProvidedWarehousesExist(
            $warehouseUserAssignmentTransfers,
            $stockCollectionTransfer,
            $warehouseUserAssignmentCollectionResponseTransfer,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \Generated\Shared\Transfer\StockCriteriaFilterTransfer
     */
    protected function createStockCriteriaFilterTransfer(ArrayObject $warehouseUserAssignmentTransfers): StockCriteriaFilterTransfer
    {
        $stockCriteriaFilterTransfer = new StockCriteriaFilterTransfer();
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if (!$warehouseUserAssignmentTransfer->getWarehouse()) {
                continue;
            }

            $stockTransfer = $warehouseUserAssignmentTransfer->getWarehouseOrFail();
            if ($stockTransfer->getIdStock()) {
                $stockCriteriaFilterTransfer->addIdStock($stockTransfer->getIdStockOrFail());
            }

            if ($stockTransfer->getUuid()) {
                $stockCriteriaFilterTransfer->addUuid($stockTransfer->getUuidOrFail());
            }
        }

        return $stockCriteriaFilterTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    protected function validateProvidedWarehousesExist(
        ArrayObject $warehouseUserAssignmentTransfers,
        StockCollectionTransfer $stockCollectionTransfer,
        WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        $indexedStockTransfers = $this->getStockTransfersIndexedByUuid($stockCollectionTransfer);
        /** @var \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer */
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if ($warehouseUserAssignmentTransfer->getWarehouse() === null) {
                continue;
            }
            if (!isset($indexedStockTransfers[$warehouseUserAssignmentTransfer->getWarehouseOrFail()->getUuidOrFail()])) {
                $warehouseUserAssignmentCollectionResponseTransfer->addError(
                    $this->createErrorTransfer(
                        static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND,
                        $this->warehouseUserAssignmentIdentifierBuilder->buildIdentifier($warehouseUserAssignmentTransfer),
                    ),
                );
            }
        }

        return $warehouseUserAssignmentCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockCollectionTransfer $stockCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\StockTransfer>
     */
    protected function getStockTransfersIndexedByUuid(
        StockCollectionTransfer $stockCollectionTransfer
    ): array {
        $indexedStockTransfers = [];
        foreach ($stockCollectionTransfer->getStocks() as $stockTransfer) {
            $indexedStockTransfers[$stockTransfer->getUuidOrFail()] = $stockTransfer;
        }

        return $indexedStockTransfers;
    }

    /**
     * @param string $message
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createErrorTransfer(string $message, string $identifier): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setMessage($message)
            ->setEntityIdentifier($identifier);
    }
}
