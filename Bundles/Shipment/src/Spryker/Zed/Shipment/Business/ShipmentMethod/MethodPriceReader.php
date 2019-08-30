<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentPriceTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;
use Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodPricePluginInterface;

class MethodPriceReader implements MethodPriceReaderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodPricePluginInterface[]
     */
    protected $shipmentMethodPricePlugins;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @var \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var int[] Keys are currency iso codes, values are currency ids.
     */
    protected static $idCurrencyCache = [];

    /**
     * @param \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodPricePluginInterface[] $shipmentMethodPricePlugins
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreInterface $storeFacade
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     * @param \Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyInterface $currencyFacade
     */
    public function __construct(
        array $shipmentMethodPricePlugins,
        ShipmentToStoreInterface $storeFacade,
        ShipmentRepositoryInterface $shipmentRepository,
        ShipmentToCurrencyInterface $currencyFacade
    ) {
        $this->shipmentMethodPricePlugins = $shipmentMethodPricePlugins;
        $this->storeFacade = $storeFacade;
        $this->shipmentRepository = $shipmentRepository;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer|null $shipmentGroupTransfer
     *
     * @return int|null
     */
    public function findShipmentGroupShippingPrice(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        QuoteTransfer $quoteTransfer,
        ?ShipmentGroupTransfer $shipmentGroupTransfer = null
    ): ?int {
        if (!$this->isSetPricePlugin($shipmentMethodTransfer)) {
            return $this->findShipmentMethodPriceValue($shipmentMethodTransfer, $quoteTransfer);
        }

        if ($shipmentGroupTransfer === null) {
            return null;
        }

        return $this->getPricePluginValue($shipmentMethodTransfer, $shipmentGroupTransfer, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return bool
     */
    protected function isSetPricePlugin(ShipmentMethodTransfer $shipmentMethodTransfer): bool
    {
        return isset($this->shipmentMethodPricePlugins[$shipmentMethodTransfer->getPricePlugin()]);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function getPricePluginValue(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        ShipmentGroupTransfer $shipmentGroupTransfer,
        QuoteTransfer $quoteTransfer
    ): ?int {
        $pricePlugin = $this->getPricePlugin($shipmentMethodTransfer);
        if ($pricePlugin instanceof ShipmentMethodPricePluginInterface) {
            return $pricePlugin->getPrice($shipmentGroupTransfer, $quoteTransfer);
        }

        /**
         * @deprecated Exists for Backward Compatibility reasons only.
         */
        return $pricePlugin->getPrice($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int|null
     */
    protected function findShipmentMethodPriceValue(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        QuoteTransfer $quoteTransfer
    ): ?int {
        $currencyTransfer = $quoteTransfer->getCurrency();
        if ($currencyTransfer === null) {
            return null;
        }

        $currencyCode = $currencyTransfer->getCode();
        if (empty($currencyCode)) {
            return null;
        }

        $storeTransfer = $this->getStore($quoteTransfer);
        $storeTransfer->requireIdStore();

        $shipmentMethodPriceTransfer = $this->shipmentRepository->findShipmentMethodPrice(
            $shipmentMethodTransfer->getIdShipmentMethod(),
            $storeTransfer->getIdStore(),
            $this->getIdCurrencyByIsoCode($currencyCode)
        );

        if ($shipmentMethodPriceTransfer === null) {
            return null;
        }

        return $this->getPriceByMode($quoteTransfer, $shipmentMethodPriceTransfer);
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return int|null
     */
    protected function getIdCurrencyByIsoCode(string $currencyIsoCode): ?int
    {
        if (!isset(static::$idCurrencyCache[$currencyIsoCode])) {
            static::$idCurrencyCache[$currencyIsoCode] = $this->currencyFacade
                ->fromIsoCode($currencyIsoCode)
                ->getIdCurrency();
        }

        return static::$idCurrencyCache[$currencyIsoCode];
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Spryker\Zed\ShipmentExtension\Dependency\Plugin\ShipmentMethodPricePluginInterface|\Spryker\Zed\Shipment\Communication\Plugin\ShipmentMethodPricePluginInterface
     */
    protected function getPricePlugin(ShipmentMethodTransfer $shipmentMethodTransfer)
    {
        return $this->shipmentMethodPricePlugins[$shipmentMethodTransfer->getPricePlugin()];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ShipmentPriceTransfer $shipmentMethodPriceTransfer
     *
     * @return int|null
     */
    protected function getPriceByMode(QuoteTransfer $quoteTransfer, ShipmentPriceTransfer $shipmentMethodPriceTransfer): ?int
    {
        return $quoteTransfer->getPriceMode() === ShipmentConstants::PRICE_MODE_GROSS ?
            $shipmentMethodPriceTransfer->getDefaultGrossPrice() :
            $shipmentMethodPriceTransfer->getDefaultNetPrice();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStore(QuoteTransfer $quoteTransfer): StoreTransfer
    {
        $storeTransfer = $quoteTransfer->getStore();
        if ($storeTransfer !== null) {
            return $storeTransfer;
        }

        return $this->storeFacade->getCurrentStore();
    }
}
