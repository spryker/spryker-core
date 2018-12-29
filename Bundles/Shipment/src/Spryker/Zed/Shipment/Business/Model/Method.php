<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Shipment\Persistence\Map\SpyShipmentMethodTableMap;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Spryker\Service\Shipment\ShipmentService;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;
use Spryker\Zed\Shipment\ShipmentDependencyProvider;

class Method implements MethodInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface
     */
    protected $methodPrice;

    /**
     * @var \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface
     */
    protected $methodTransformer;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Service\Shipment\ShipmentService
     */
    protected $shipmentService;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Plugin\ShipmentMethodFilterPluginInterface[]
     */
    protected $shipmentMethodFilters;

    /**
     * @var array
     */
    protected $plugins;

    /**
     * @var int[] Keys are currency iso codes, values are currency ids.
     */
    protected static $idCurrencyCache = [];

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface $methodPrice
     * @param \Spryker\Zed\Shipment\Business\Model\Transformer\ShipmentMethodTransformerInterface $methodTransformer
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     * @param \Spryker\Service\Shipment\ShipmentService $shipmentFacade
     * @param array $plugins
     * @param \Spryker\Zed\Shipment\Dependency\Plugin\ShipmentMethodFilterPluginInterface[] $shipmentMethodFilters
     */
    public function __construct(
        ShipmentQueryContainerInterface $queryContainer,
        MethodPriceInterface $methodPrice,
        ShipmentMethodTransformerInterface $methodTransformer,
        ShipmentToCurrencyInterface $currencyFacade,
        ShipmentToStoreInterface $storeFacade,
        ShipmentService $shipmentFacade,
        array $plugins,
        array $shipmentMethodFilters
    ) {
        $this->queryContainer = $queryContainer;
        $this->methodPrice = $methodPrice;
        $this->methodTransformer = $methodTransformer;
        $this->currencyFacade = $currencyFacade;
        $this->storeFacade = $storeFacade;
        $this->shipmentFacade = $shipmentFacade;
        $this->plugins = $plugins;
        $this->shipmentMethodFilters = $shipmentMethodFilters;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function create(ShipmentMethodTransfer $methodTransfer)
    {
        $methodEntity = new SpyShipmentMethod();
        $methodEntity->fromArray($methodTransfer->toArray());
        $methodEntity->save();

        $idShipmentMethod = $methodEntity->getPrimaryKey();
        $methodTransfer->setIdShipmentMethod($idShipmentMethod);
        $this->methodPrice->save($methodTransfer);

        return $idShipmentMethod;
    }

    /**
     * @deprecated Use getAvailableMethodsByShipment() instead
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        $shipmentMethodsTransfer = $this->getAvailableMethodsTransfer($quoteTransfer);
        $shipmentMethodsTransfer = $this->applyFilters($shipmentMethodsTransfer, $quoteTransfer);

        return $shipmentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): array
    {
        $shipmentGroupTransferArray = $this->getShipmentGroupWithAvailableMethods($quoteTransfer);
        $shipmentGroupTransferArray = $this->applyFilters($shipmentGroupTransferArray, $quoteTransfer);

        return $shipmentGroupTransferArray;
    }

    /**
     * @deprecated Use getAvailableMethodsByShipmentTransfer() instead
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableMethodsTransfer(QuoteTransfer $quoteTransfer)
    {
        $shipmentMethodsTransfer = new ShipmentMethodsTransfer();
        $methods = $this->queryContainer->queryActiveMethodsWithMethodPricesAndCarrier()->find();

        foreach ($methods as $shipmentMethodEntity) {
            if ($this->isShipmentMethodAvailable($shipmentMethodEntity, $quoteTransfer) === false) {
                continue;
            }

            $storeCurrencyPrice = $this->findStoreCurrencyPriceAmount($shipmentMethodEntity, $quoteTransfer);

            if ($storeCurrencyPrice === null) {
                continue;
            }

            $shipmentMethodTransfer = $this->transformShipmentMethod($shipmentMethodEntity, $quoteTransfer, $storeCurrencyPrice);
            $shipmentMethodsTransfer->addMethod($shipmentMethodTransfer);
        }

        return $shipmentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function getShipmentGroupWithAvailableMethods(QuoteTransfer $quoteTransfer): array
    {
        $methods = $this->queryContainer->queryActiveMethodsWithMethodPricesAndCarrier()->find();
        $shipmentGroupTransfers = $this->getShipmentGroups($quoteTransfer);

        foreach ($shipmentGroupTransfers as $shipmentGroupTransfer) {
            $shipmentGroupTransfer->setAvailableShipmentMethods(
                $this->getAvailableMethodForShipmentGroup($shipmentGroupTransfer, $methods, $quoteTransfer)
            );
        }

        return $shipmentGroupTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $methods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function getAvailableMethodForShipmentGroup(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        ShipmentMethodsTransfer $methods,
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
     * @return array|\Generated\Shared\Transfer\ShipmentGroupTransfer[]
     */
    protected function getShipmentGroups(QuoteTransfer $quoteTransfer): \ArrayObject
    {
        $shipmentGroupTransfer = $this->shipmentService->groupItemsByShipment($quoteTransfer->getItems());

        return $shipmentGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    protected function applyFilters(ShipmentMethodsTransfer $shipmentMethodsTransfer, QuoteTransfer $quoteTransfer)
    {
        $shipmentMethods = $shipmentMethodsTransfer->getMethods();

        foreach ($this->shipmentMethodFilters as $shipmentMethodFilter) {
            /**
             * @todo Ask Jeremy what to do here, probably should be done in separate ticket.
             */
            $shipmentMethods = $shipmentMethodFilter->filterShipmentMethods($shipmentMethods, $quoteTransfer);
        }

        return $shipmentMethodsTransfer->setMethods($shipmentMethods);
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $storeCurrencyPrice
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function transformShipmentMethod(SpyShipmentMethod $shipmentMethodEntity, QuoteTransfer $quoteTransfer, $storeCurrencyPrice)
    {
        $quoteTransfer->requireCurrency()
            ->getCurrency()
            ->requireCode();

        $shipmentMethodTransfer = $this->methodTransformer->transformEntityToTransfer($shipmentMethodEntity);
        $shipmentMethodTransfer
            ->setStoreCurrencyPrice($storeCurrencyPrice)
            ->setDeliveryTime(
                $this->getDeliveryTime($shipmentMethodEntity, $quoteTransfer)
            )
            ->setCurrencyIsoCode(
                $quoteTransfer->getCurrency()->getCode()
            );

        return $shipmentMethodTransfer;
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
            )
        ;

        return $shipmentMethodTransfer;
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function hasMethod($idMethod)
    {
        $methodQuery = $this->queryContainer->queryMethodByIdMethod($idMethod);

        return $methodQuery->count() > 0;
    }

    /**
     * @param int $idMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransferById($idMethod)
    {
        $shipmentMethodTransfer = new ShipmentMethodTransfer();

        $methodQuery = $this->queryContainer->queryMethodByIdMethod($idMethod);
        $shipmentMethodTransferEntity = $methodQuery->findOne();

        $shipmentMethodTransfer = $this->mapEntityToTransfer($shipmentMethodTransferEntity, $shipmentMethodTransfer);

        return $shipmentMethodTransfer;
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodTransferById($idShipmentMethod)
    {
        $shipmentMethodEntity = $this->queryContainer
            ->queryActiveMethodsWithMethodPricesAndCarrierById($idShipmentMethod)
            ->find()
            ->getFirst();

        if (!$shipmentMethodEntity) {
            return null;
        }

        $shipmentMethodTransfer = new ShipmentMethodTransfer();
        $shipmentMethodTransfer = $this->mapEntityToTransfer($shipmentMethodEntity, $shipmentMethodTransfer);

        return $shipmentMethodTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function getShipmentMethodTransfers()
    {
        $shipmentMethodTransfers = [];

        $query = $this->queryContainer
            ->queryActiveMethods();

        foreach ($query->find() as $shipmentMethodEntity) {
            $shipmentMethodTransfer = new ShipmentMethodTransfer();
            $shipmentMethodTransfer = $this->mapEntityToTransfer($shipmentMethodEntity, $shipmentMethodTransfer);
            $shipmentMethodTransfers[] = $shipmentMethodTransfer;
        }

        return $shipmentMethodTransfers;
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod)
    {
        $methodQuery = $this->queryContainer->queryMethodByIdMethod($idMethod);
        $entity = $methodQuery->findOne();

        if ($entity) {
            $entity->delete();
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int|bool
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer)
    {
        if ($this->hasMethod($methodTransfer->getIdShipmentMethod())) {
            $methodEntity =
                $this->queryContainer->queryMethodByIdMethod($methodTransfer->getIdShipmentMethod())->findOne();

            $methodEntity->fromArray($methodTransfer->toArray());
            $methodEntity->save();
            $this->methodPrice->save($methodTransfer);

            return $methodEntity->getPrimaryKey();
        }

        return false;
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function isShipmentMethodActive($idShipmentMethod)
    {
        $idShipmentMethod = $this->queryContainer
            ->queryActiveShipmentMethodByIdShipmentMethod($idShipmentMethod)
            ->select(SpyShipmentMethodTableMap::COL_ID_SHIPMENT_METHOD)
            ->findOne();

        return $idShipmentMethod !== null;
    }

    /**
     * @param int $idShipmentMethod
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findAvailableMethodById($idShipmentMethod, QuoteTransfer $quoteTransfer)
    {
        $shipmentMethodEntity = $this->queryContainer
            ->queryMethodByIdMethod($idShipmentMethod)
            ->findOne();

        if (!$shipmentMethodEntity) {
            return null;
        }

        $storeCurrencyPrice = $this->findStoreCurrencyPriceAmount($shipmentMethodEntity, $quoteTransfer);

        if ($storeCurrencyPrice === null) {
            return null;
        }

        return $this->transformShipmentMethod($shipmentMethodEntity, $quoteTransfer, $storeCurrencyPrice);
    }

    /**
     * @deprecated use isShipmentMethodAvailable() instead
     *
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isShipmentMethodAvailable(SpyShipmentMethod $method, QuoteTransfer $quoteTransfer)
    {
        $availabilityPlugins = $this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS];
        $isAvailable = true;

        if ($this->isSetAvailabilityPlugin($method, $availabilityPlugins)) {
            $availabilityPlugin = $this->getAvailabilityPlugin($method, $availabilityPlugins);
            $isAvailable = $availabilityPlugin->isAvailable($quoteTransfer);
        }

        return $isAvailable;
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
    ) {
        $availabilityPlugins = $this->plugins[ShipmentDependencyProvider::AVAILABILITY_PLUGINS];
        $isAvailable = true;

        if ($this->isSetAvailabilityPlugin($method, $availabilityPlugins)) {
            $availabilityPlugin = $this->getAvailabilityPlugin($method, $availabilityPlugins);
            $isAvailable = $availabilityPlugin->isAvailable($shipmentGroupTransfer, $quoteTransfer);
        }

        return $isAvailable;
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param array $availabilityPlugins
     *
     * @return \Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface|\Spryker\Zed\ShipmentExtension\Communication\Plugin\ShipmentMethodAvailabilityPluginInterface
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
     * @deprecated
     *
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function findStoreCurrencyPriceAmount(SpyShipmentMethod $method, QuoteTransfer $quoteTransfer)
    {
        $idStore = $this->storeFacade->getCurrentStore()->getIdStore();
        $pricePlugins = $this->plugins[ShipmentDependencyProvider::PRICE_PLUGINS];

        if (isset($pricePlugins[$method->getPricePlugin()])) {
            $pricePlugin = $this->getPricePlugin($method, $pricePlugins);
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

        if (isset($pricePlugins[$method->getPricePlugin()])) {
            $pricePlugin = $this->getPricePlugin($method, $pricePlugins);
            return $pricePlugin->getPrice($shipmentGroupTransfer, $quoteTransfer);
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
     * @return \Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodPricePluginInterface
     */
    protected function getPricePlugin(SpyShipmentMethod $method, array $pricePlugins)
    {
        return $pricePlugins[$method->getPricePlugin()];
    }

    /**
     * @deprecated
     *
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $method
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function getDeliveryTime(SpyShipmentMethod $method, QuoteTransfer $quoteTransfer)
    {
        $deliveryTime = null;
        $deliveryTimePlugins = $this->plugins[ShipmentDependencyProvider::DELIVERY_TIME_PLUGINS];

        if (isset($deliveryTimePlugins[$method->getDeliveryTimePlugin()])) {
            $deliveryTimePlugin = $this->getDeliveryTimePlugin($method, $deliveryTimePlugins);
            $deliveryTime = $deliveryTimePlugin->getTime($quoteTransfer);
        }

        return $deliveryTime;
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
        return $deliveryTimePlugins[$method->getDeliveryTimePlugin()];
    }

    /**
     * @param \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected function mapEntityToTransfer(SpyShipmentMethod $shipmentMethodEntity, ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        $shipmentMethodTransfer->fromArray($shipmentMethodEntity->toArray(), true);
        $shipmentMethodTransfer->setCarrierName($shipmentMethodEntity->getShipmentCarrier()->getName());

        return $shipmentMethodTransfer;
    }
}
