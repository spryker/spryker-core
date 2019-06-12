<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Zed\Shipment\Business\Model\Method;
use Spryker\Zed\Shipment\Business\Model\MethodPriceInterface;
use Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface;

class MethodReader extends Method
{
    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodAvailabilityCheckerInterface
     */
    protected $methodAvailabilityChecker;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodPriceReaderInterface
     */
    protected $methodPriceReader;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodDeliveryTimeReaderInterface
     */
    protected $methodDeliveryTimeReader;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface $methodPrice
     * @param \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface $methodTransformer
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param array $plugins
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface[] $shipmentMethodFilters
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodAvailabilityCheckerInterface $methodAvailabilityChecker
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodPriceReaderInterface $methodPriceReader
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodDeliveryTimeReaderInterface $methodDeliveryTimeReader
     */
    public function __construct(
        ShipmentQueryContainerInterface $queryContainer,
        MethodPriceInterface $methodPrice,
        ShipmentMethodTransformerInterface $methodTransformer,
        ShipmentToCurrencyInterface $currencyFacade,
        ShipmentToStoreInterface $storeFacade,
        ShipmentServiceInterface $shipmentService,
        array $plugins,
        array $shipmentMethodFilters,
        ShipmentRepositoryInterface $shipmentRepository,
        MethodAvailabilityCheckerInterface $methodAvailabilityChecker,
        MethodPriceReaderInterface $methodPriceReader,
        MethodDeliveryTimeReaderInterface $methodDeliveryTimeReader
    ) {
        parent::__construct(
            $queryContainer,
            $methodPrice,
            $methodTransformer,
            $currencyFacade,
            $storeFacade,
            $plugins,
            $shipmentMethodFilters
        );

        $this->shipmentService = $shipmentService;
        $this->shipmentRepository = $shipmentRepository;
        $this->methodAvailabilityChecker = $methodAvailabilityChecker;
        $this->methodPriceReader = $methodPriceReader;
        $this->methodDeliveryTimeReader = $methodDeliveryTimeReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer
    {
        $shipmentGroupCollectionTransfer = $this->getShipmentGroupWithAvailableMethods($quoteTransfer);
        $shipmentGroupCollectionTransfer = $this->applyFiltersByShipment($shipmentGroupCollectionTransfer, $quoteTransfer);

        return $shipmentGroupCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    protected function getShipmentGroupWithAvailableMethods(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer
    {
        $shipmentMethodTransfers = $this->shipmentRepository->getActiveShipmentMethods();
        $quoteShipmentGroupCollection = $this->getShipmentGroupCollection($quoteTransfer);

        $shipmentGroupCollection = new ArrayObject();
        foreach ($quoteShipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentMethodsTransfer = $this->getAvailableMethodForShipmentGroup(
                $shipmentGroupTransfer,
                $shipmentMethodTransfers,
                $quoteTransfer
            );

            $shipmentGroupTransfer->setAvailableShipmentMethods($shipmentMethodsTransfer);
            $shipmentGroupCollection[$shipmentGroupTransfer->getHash()] = $shipmentGroupTransfer;
        }

        return (new ShipmentGroupCollectionTransfer())->setGroups($shipmentGroupCollection);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableMethodForShipmentGroup(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        ArrayObject $shipmentMethodTransfers,
        QuoteTransfer $quoteTransfer
    ): ShipmentMethodsTransfer {
        $shipmentMethodsTransfer = new ShipmentMethodsTransfer();
        foreach ($shipmentMethodTransfers as $shipmentMethodTransfer) {
            $isShipmentMethodAvailableForShipmentGroup = $this->methodAvailabilityChecker
                ->isShipmentMethodAvailableForShipmentGroup(
                    $shipmentMethodTransfer,
                    $shipmentGroupTransfer,
                    $quoteTransfer
                );

            if (!$isShipmentMethodAvailableForShipmentGroup) {
                continue;
            }

            $storeCurrencyPrice = $this->methodPriceReader
                ->getShipmentGroupShippingPrice(
                    $shipmentMethodTransfer,
                    $shipmentGroupTransfer,
                    $quoteTransfer
                );

            if ($storeCurrencyPrice === null) {
                continue;
            }

            $shipmentMethodTransfer = $this->transformShipmentMethodByShipmentGroup(
                $shipmentMethodTransfer,
                $shipmentGroupTransfer,
                $quoteTransfer,
                $storeCurrencyPrice
            );
            $shipmentMethodsTransfer->addMethod($shipmentMethodTransfer);
        }

        return $shipmentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function getShipmentGroupCollection(QuoteTransfer $quoteTransfer): ArrayObject
    {
        return $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer $shipmentGroupCollectionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    protected function applyFiltersByShipment(
        ShipmentGroupCollectionTransfer $shipmentGroupCollectionTransfer,
        QuoteTransfer $quoteTransfer
    ): ShipmentGroupCollectionTransfer {
        foreach ($shipmentGroupCollectionTransfer->getGroups() as $shipmentGroupTransfer) {
            $shipmentMethods = $shipmentGroupTransfer->getAvailableShipmentMethods();

            foreach ($this->shipmentMethodFilters as $shipmentMethodFilter) {
                if ($shipmentMethodFilter instanceof ShipmentMethodFilterPluginInterface) {
                    $shipmentMethods = $shipmentMethodFilter->filterShipmentMethods($shipmentGroupTransfer, $quoteTransfer);
                } else {
                    /**
                     * @deprecated Exists for Backward Compatibility reasons only.
                     */
                    $shipmentMethods = $shipmentMethodFilter->filterShipmentMethods($shipmentMethods->getMethods(), $quoteTransfer);
                }
            }

            $shipmentMethodsTransfer = (new ShipmentMethodsTransfer())->setMethods($shipmentMethods);
            $shipmentGroupTransfer->setAvailableShipmentMethods($shipmentMethodsTransfer);
        }

        return $shipmentGroupCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $storeCurrencyPrice
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function transformShipmentMethodByShipmentGroup(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer,
        $storeCurrencyPrice
    ): ShipmentMethodTransfer {
        $deliveryTimeForShippingGroup = $this->methodDeliveryTimeReader->getDeliveryTimeForShippingGroup(
            $shipmentMethodTransfer,
            $shipmentGroupTransfer,
            $quoteTransfer
        );

        $shipmentMethodTransfer
            ->setStoreCurrencyPrice($storeCurrencyPrice)
            ->setDeliveryTime($deliveryTimeForShippingGroup);

        $currencyTransfer = $quoteTransfer->requireCurrency()->getCurrency();
        if ($currencyTransfer === null) {
            return $shipmentMethodTransfer;
        }

        return $shipmentMethodTransfer->setCurrencyIsoCode($currencyTransfer->getCode());
    }
}
