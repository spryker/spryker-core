<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Expander;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Shipment\ShipmentConfig;
use Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface;
use Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface;
use Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCalculationFacadeInterface;

class QuoteShipmentExpander implements QuoteShipmentExpanderInterface
{
    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface
     */
    protected $methodReader;

    /**
     * @var \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface
     */
    protected $expenseSanitizer;

    /**
     * @var \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface
     */
    protected $shipmentMapper;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @var \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentGroupsSanitizerPluginInterface[]
     */
    protected $shipmentGroupsSanitizers;

    /**
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface $methodReader
     * @param \Spryker\Zed\Shipment\Business\Sanitizer\ExpenseSanitizerInterface $expenseSanitizer
     * @param \Spryker\Zed\Shipment\Business\Mapper\ShipmentMapperInterface $shipmentMapper
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCalculationFacadeInterface $calculationFacade
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentGroupsSanitizerPluginInterface[] $shipmentGroupsSanitizers
     */
    public function __construct(
        ShipmentServiceInterface $shipmentService,
        MethodReaderInterface $methodReader,
        ExpenseSanitizerInterface $expenseSanitizer,
        ShipmentMapperInterface $shipmentMapper,
        ShipmentToCalculationFacadeInterface $calculationFacade,
        array $shipmentGroupsSanitizers
    ) {
        $this->shipmentService = $shipmentService;
        $this->methodReader = $methodReader;
        $this->expenseSanitizer = $expenseSanitizer;
        $this->shipmentMapper = $shipmentMapper;
        $this->calculationFacade = $calculationFacade;
        $this->shipmentGroupsSanitizers = $shipmentGroupsSanitizers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteWithShipmentGroups(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $shipmentGroupCollection = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
        $shipmentGroupCollection = $this->setAvailableShipmentMethodsToShipmentGroups($quoteTransfer, $shipmentGroupCollection);
        $shipmentGroupCollection = $this->setShipmentGroupsSelectedMethodTransfer($shipmentGroupCollection);
        $shipmentGroupCollection = $this->sanitizeShipmentGroupCollection($shipmentGroupCollection);

        $quoteTransfer = $this->setShipmentExpenseTransfers($quoteTransfer, $shipmentGroupCollection);
        $quoteTransfer = $this->updateQuoteLevelShipment($quoteTransfer, $shipmentGroupCollection);

        return $this->calculationFacade->recalculateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function setAvailableShipmentMethodsToShipmentGroups(
        QuoteTransfer $quoteTransfer,
        iterable $shipmentGroupCollection
    ): iterable {
        $availableShipmentMethodsGroupedByShipment = $this->methodReader->getAvailableMethodsByShipment($quoteTransfer);

        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $availableShipmentMethodsTransfer = $this->findAvailableShipmentMethodsByShipmentGroup(
                $availableShipmentMethodsGroupedByShipment,
                $shipmentGroupTransfer
            );

            if ($availableShipmentMethodsTransfer === null) {
                continue;
            }

            $shipmentGroupTransfer->setAvailableShipmentMethods($availableShipmentMethodsTransfer);
        }

        return $shipmentGroupCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsCollectionTransfer $availableShipmentMethodsGroupedByShipment
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer|null
     */
    protected function findAvailableShipmentMethodsByShipmentGroup(
        ShipmentMethodsCollectionTransfer $availableShipmentMethodsGroupedByShipment,
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): ?ShipmentMethodsTransfer {
        $shipmentHashKey = $shipmentGroupTransfer->requireHash()->getHash();

        foreach ($availableShipmentMethodsGroupedByShipment->getShipmentMethods() as $shipmentMethodsTransfer) {
            if ($shipmentHashKey === $shipmentMethodsTransfer->getShipmentHash()) {
                return $shipmentMethodsTransfer;
            }
        }

        return null;
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function setShipmentGroupsSelectedMethodTransfer(iterable $shipmentGroupCollection): iterable
    {
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentGroupTransfer->requireShipment()
                ->requireAvailableShipmentMethods();

            $shipmentTransfer = $shipmentGroupTransfer->getShipment();

            $shipmentMethodTransfer = $this->findShipmentMethod(
                $shipmentGroupTransfer->getAvailableShipmentMethods(),
                $shipmentTransfer
            );
            $shipmentTransfer->setMethod($shipmentMethodTransfer);

            $this->updateItemLevelShipmentReferences($shipmentGroupTransfer);
        }

        return $shipmentGroupCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return void
     */
    protected function updateItemLevelShipmentReferences(ShipmentGroupTransfer $shipmentGroupTransfer): void
    {
        $shipmentTransfer = $shipmentGroupTransfer->getShipment();
        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findShipmentMethod(
        ShipmentMethodsTransfer $shipmentMethodsTransfer,
        ShipmentTransfer $shipmentTransfer
    ): ?ShipmentMethodTransfer {
        $shipmentSelection = $shipmentTransfer->getShipmentSelection();
        if ($shipmentSelection === ShipmentConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT) {
            return $this->findNoShipmentMethod($shipmentMethodsTransfer);
        }

        $shipmentSelection = (int)$shipmentSelection;
        if ($shipmentSelection === 0) {
            return null;
        }

        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethod() === $shipmentSelection) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    protected function findNoShipmentMethod(ShipmentMethodsTransfer $shipmentMethodsTransfer): ?ShipmentMethodTransfer
    {
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getName() === ShipmentConfig::SHIPMENT_METHOD_NAME_NO_SHIPMENT) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeAllShipmentExpensesFromQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteExpenseForRemoveIndexes = [];
        foreach ($quoteTransfer->getExpenses() as $expenseTransferIndex => $expenseTransfer) {
            if ($expenseTransfer->getType() === ShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                $quoteExpenseForRemoveIndexes[] = $expenseTransferIndex;
            }
        }

        foreach ($quoteExpenseForRemoveIndexes as $quoteExpenseForRemoveIndex) {
            $quoteTransfer->getExpenses()->offsetUnset($quoteExpenseForRemoveIndex);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createShipmentExpenseTransfer(ShipmentMethodTransfer $shipmentMethodTransfer, $priceMode): ExpenseTransfer
    {
        $shipmentExpenseTransfer = $this->shipmentMapper->mapShipmentMethodTransferToShipmentExpenseTransfer($shipmentMethodTransfer, new ExpenseTransfer());
        $shipmentExpenseTransfer->setType(ShipmentConfig::SHIPMENT_EXPENSE_TYPE);
        $shipmentExpenseTransfer->setQuantity(1);

        $shipmentMethodTransfer->requireStoreCurrencyPrice();

        return $this->expenseSanitizer->sanitizeShipmentExpensePricesByPriceMode($shipmentExpenseTransfer, $shipmentMethodTransfer->getStoreCurrencyPrice(), $priceMode);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setShipmentExpenseTransfers(QuoteTransfer $quoteTransfer, iterable $shipmentGroupCollection): QuoteTransfer
    {
        $quoteTransfer = $this->removeAllShipmentExpensesFromQuote($quoteTransfer);

        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentExpenseKey = $shipmentGroupTransfer->getHash();
            if ($quoteTransfer->getExpenses()->offsetExists($shipmentExpenseKey)) {
                continue;
            }

            $shipmentGroupTransfer->requireShipment();
            $shipmentTransfer = $shipmentGroupTransfer->getShipment();
            $shipmentTransfer->requireMethod();

            $shipmentExpenseTransfer = $this->createShipmentExpenseTransfer($shipmentTransfer->getMethod(), $quoteTransfer->getPriceMode());
            $shipmentExpenseTransfer->setShipment($shipmentTransfer);

            $quoteTransfer->getExpenses()->offsetSet($shipmentExpenseKey, $shipmentExpenseTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function updateQuoteLevelShipment(QuoteTransfer $quoteTransfer, iterable $shipmentGroupCollection): QuoteTransfer
    {
        if (count($shipmentGroupCollection) > 1) {
            return $quoteTransfer->setShipment(null);
        }

        $firstShipmentGroup = current($shipmentGroupCollection);
        $firstShipmentGroup->requireShipment();

        return $quoteTransfer->setShipment($firstShipmentGroup->getShipment());
    }

    /**
     * @param iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection
     *
     * @return iterable|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function sanitizeShipmentGroupCollection(iterable $shipmentGroupCollection): iterable
    {
        foreach ($this->shipmentGroupsSanitizers as $shipmentGroupsSanitizer) {
            $shipmentGroupCollection = $shipmentGroupsSanitizer->sanitizeShipmentGroupCollection($shipmentGroupCollection);
        }

        return $shipmentGroupCollection;
    }
}
