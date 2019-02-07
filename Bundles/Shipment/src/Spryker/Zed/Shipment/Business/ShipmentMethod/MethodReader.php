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
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Business\Model\Method;
use Spryker\Zed\Shipment\Business\Model\MethodPriceInterface;
use Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

class MethodReader extends Method
{
    /**
     * @var \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    protected $shipmentService;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface $methodPrice
     * @param \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface $methodTransformer
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     * @param \Spryker\Service\Shipment\ShipmentServiceInterface $shipmentService
     * @param array $plugins
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodFilterPluginInterface[] $shipmentMethodFilters
     */
    public function __construct(
        ShipmentQueryContainerInterface $queryContainer,
        MethodPriceInterface $methodPrice,
        ShipmentMethodTransformerInterface $methodTransformer,
        ShipmentToCurrencyInterface $currencyFacade,
        ShipmentToStoreInterface $storeFacade,
        ShipmentServiceInterface $shipmentService,
        array $plugins,
        array $shipmentMethodFilters
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
        $methods = $this->queryContainer->queryActiveMethodsWithMethodPricesAndCarrier()->find();
        $shipmentGroupCollection = $this->getShipmentGroupCollection($quoteTransfer);

        $shipmentGroupCollectionTransfer = new ShipmentGroupCollectionTransfer();
        foreach ($shipmentGroupCollection as $shipmentGroupTransfer) {
            $shipmentGroupTransfer->setAvailableShipmentMethods(
                $this->getAvailableMethodForShipmentGroup($shipmentGroupTransfer, $methods, $quoteTransfer)
            );

            $shipmentGroupCollectionTransfer->addGroup($shipmentGroupTransfer);
        }

        return $shipmentGroupCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Propel\Runtime\Collection\ObjectCollection $methods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableMethodForShipmentGroup(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        ObjectCollection $methods,
        QuoteTransfer $quoteTransfer
    ): ShipmentMethodsTransfer {
        $shipmentMethodsTransfer = new ShipmentMethodsTransfer();
        foreach ($methods as $shipmentMethodEntity) {
            $isShipmentMethodAvailableForShipmentGroup = $this->isShipmentMethodAvailableForShipmentGroup(
                $shipmentMethodEntity,
                $shipmentGroupTransfer,
                $quoteTransfer
            );

            if ($isShipmentMethodAvailableForShipmentGroup === false) {
                continue;
            }

            $storeCurrencyPrice = $this->getShipmentGroupShippingPrice($shipmentMethodEntity, $shipmentGroupTransfer, $quoteTransfer);

            if ($storeCurrencyPrice === null) {
                continue;
            }

            $shipmentMethodTransfer = $this->transformShipmentMethodByShipmentGroup(
                $shipmentMethodEntity,
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
        $shipmentGroups = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        return $shipmentGroups;
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
            /**
             * @todo Update this with plugin resolving
             */
            foreach ($this->shipmentMethodFilters as $shipmentMethodFilter) {
                $shipmentMethods = $shipmentMethodFilter->filterShipmentMethods(
                    $shipmentGroupTransfer,
                    $quoteTransfer
                );
            }
            $shipmentGroupTransfer->setAvailableShipmentMethods($shipmentMethods);
        }

        return $shipmentGroupCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $storeCurrencyPrice
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function transformShipmentMethodByShipmentGroup(
        SpyShipmentMethod $shipmentMethodEntity,
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer,
        $storeCurrencyPrice
    ): ShipmentMethodTransfer {
        $quoteTransfer
            ->requireCurrency()
            ->getCurrency()
            ->requireCode();

        $shipmentMethodTransfer = $this->methodTransformer->transformEntityToTransfer($shipmentMethodEntity);
        $shipmentMethodTransfer
            ->setStoreCurrencyPrice($storeCurrencyPrice)
            ->setDeliveryTime(
                $this->getDeliveryTimeForShippingGroup($shipmentMethodEntity, $shipmentGroupTransfer, $quoteTransfer)
            )
            ->setCurrencyIsoCode(
                $quoteTransfer->getCurrency()->getCode()
            );

        return $shipmentMethodTransfer;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentMethodAvailableForShipmentGroup(
        SpyShipmentMethod $method,
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer
    ): bool {
        $availabilityPlugins = $this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS];
        $isAvailable = true;

        if ($this->isSetAvailabilityPlugin($method, $availabilityPlugins)) {
            $availabilityPlugin = $this->getAvailabilityPlugin($method, $availabilityPlugins);
            if ($availabilityPlugin instanceof ShipmentMethodAvailabilityPluginInterface) {
                $isAvailable = $availabilityPlugin->isAvailable($shipmentGroupTransfer, $quoteTransfer);
            } else {
                /**
                 * @deprecated Will be removed in next major release.
                 */
                $isAvailable = $availabilityPlugin->isAvailable($quoteTransfer);
            }
        }

        return $isAvailable;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param array $availabilityPlugins
     *
     * @return \Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface
     */
    protected function getAvailabilityPlugin(SpyShipmentMethod $method, array $availabilityPlugins)
    {
        return $availabilityPlugins[$method->getAvailabilityPlugin()];
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param array $availabilityPlugins
     *
     * @return bool
     */
    protected function isSetAvailabilityPlugin(SpyShipmentMethod $method, array $availabilityPlugins): bool
    {
        return isset($availabilityPlugins[$method->getAvailabilityPlugin()]);
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function getShipmentGroupShippingPrice(
        SpyShipmentMethod $method,
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer
    ): ?int {
        $idStore = $this->storeFacade->getCurrentStore()->getIdStore();
        $pricePlugins = $this->plugins[ShipmentDependencyProvider::PRICE_PLUGINS];

        if ($this->isSetPricePlugin($method, $pricePlugins)) {
            $pricePlugin = $this->getPricePlugin($method, $pricePlugins);
            if ($pricePlugin instanceof ShipmentMethodPricePluginInterface) {
                return $pricePlugin->getPrice($shipmentGroupTransfer, $quoteTransfer);
            }

            /**
             * @deprecated Will be removed in next major release.
             */
            return $pricePlugin->getPrice($quoteTransfer);
        }

        $methodPriceEntity = $this->queryContainer
            ->queryMethodPriceByShipmentMethodAndStoreCurrency(
                $method->getIdShipmentMethod(),
                $idStore,
                $this->getIdCurrencyByIsoCode($quoteTransfer->getCurrency()->getCode())
            )
            ->findOne();

        if ($methodPriceEntity === null) {
            return null;
        }

        $price = $quoteTransfer->getPriceMode() === ShipmentConstants::PRICE_MODE_GROSS ?
            $methodPriceEntity->getDefaultGrossPrice() :
            $methodPriceEntity->getDefaultNetPrice();

        return $price;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return int
     */
    protected function getIdCurrencyByIsoCode($currencyIsoCode)
    {
        if (!isset(static::$idCurrencyCache[$currencyIsoCode])) {
            static::$idCurrencyCache[$currencyIsoCode] = $this->currencyFacade
                ->fromIsoCode($currencyIsoCode)
                ->getIdCurrency();
        }

        return static::$idCurrencyCache[$currencyIsoCode];
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param array $pricePlugins
     *
     * @return \Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentMethodPricePluginInterface|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodPricePluginInterface
     */
    protected function getPricePlugin(SpyShipmentMethod $method, array $pricePlugins)
    {
        /**
         * @todo Update this with plugin resolving
         */
        return $pricePlugins[$method->getPricePlugin()];
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param array $pricePlugins
     *
     * @return bool
     */
    protected function isSetPricePlugin(SpyShipmentMethod $method, array $pricePlugins): bool
    {
        return isset($pricePlugins[$method->getPricePlugin()]);
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function getDeliveryTimeForShippingGroup(
        SpyShipmentMethod $method,
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer
    ): ?int {
        /**
         * @todo Update this with plugin resolving
         */
        $deliveryTime = null;
        $deliveryTimePlugins = $this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS];

        if ($this->issetDeliveryTimePlugin($method, $deliveryTimePlugins)) {
            $deliveryTimePlugin = $this->getDeliveryTimePlugin($method, $deliveryTimePlugins);
            $deliveryTime = $deliveryTimePlugin->getTime($shipmentGroupTransfer, $quoteTransfer);
        }

        return $deliveryTime;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param array $deliveryTimePlugins
     *
     * @return bool
     */
    protected function issetDeliveryTimePlugin(SpyShipmentMethod $method, array $deliveryTimePlugins): bool
    {
        /**
         * @todo Update this with plugin resolving
         */
        return isset($deliveryTimePlugins[$method->getDeliveryTimePlugin()]);
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param array $deliveryTimePlugins
     *
     * @return \Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentMethodDeliveryTimePluginInterface
     */
    protected function getDeliveryTimePlugin(SpyShipmentMethod $method, array $deliveryTimePlugins)
    {
        /**
         * @todo Update this with plugin resolving
         */
        return $deliveryTimePlugins[$method->getDeliveryTimePlugin()];
    }
}
