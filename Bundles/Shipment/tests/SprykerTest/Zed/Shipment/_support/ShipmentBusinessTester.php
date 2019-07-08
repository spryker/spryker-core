<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CalculableObjectBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Shipment\Communication\Plugin\Checkout\OrderShipmentSavePlugin;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentOrderHydratePlugin;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentBusinessTester extends Actor
{
    use _generated\ShipmentBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    public function getShipmentFacade()
    {
        return $this->getLocator()->shipment()->facade();
    }

    /**
     * @return \Spryker\Service\Shipment\ShipmentServiceInterface
     */
    public function getShipmentService(): ShipmentServiceInterface
    {
        return $this->getLocator()->shipment()->service();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     *
     * @return int[]
     */
    public function getIdShipmentMethodCollection(ShipmentMethodsTransfer $shipmentMethodsTransfer)
    {
        $idShipmentMethodCollection = [];

        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            $idShipmentMethodCollection[] = $shipmentMethodTransfer->getIdShipmentMethod();
        }

        sort($idShipmentMethodCollection);

        return $idShipmentMethodCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodsTransfer $shipmentMethodsTransfer
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|mixed|null
     */
    public function findShipmentMethod(ShipmentMethodsTransfer $shipmentMethodsTransfer, $idShipmentMethod)
    {
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethod() === $idShipmentMethod) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param array $data
     * @param array|null $idFilter
     *
     * @return void
     */
    public function updateShipmentMethod(array $data, ?array $idFilter = null)
    {
        $shipmentMethodQuery = SpyShipmentMethodQuery::create();

        if ($idFilter !== null) {
            $shipmentMethodQuery->filterByIdShipmentMethod($idFilter, Criteria::IN);
        }

        $shipmentMethodCollection = $shipmentMethodQuery->find();
        foreach ($shipmentMethodCollection as $shipmentMethodEntity) {
            $shipmentMethodEntity->fromArray($data);
            $shipmentMethodEntity->save();
        }
    }

    /**
     * @return void
     */
    public function disableAllShipmentMethods()
    {
        $this->updateShipmentMethod(['is_active' => false]);
    }

    /**
     * @param int $shipmentMethodCount
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function haveActiveShipmentMethods($shipmentMethodCount)
    {
        $shipmentMethodTransferCollection = [];
        for ($i = 0; $i < $shipmentMethodCount; $i++) {
            $shipmentMethodTransferCollection[$i] = $this->haveShipmentMethod(['is_active' => true]);
        }

        return $shipmentMethodTransferCollection;
    }

    /**
     * @return string
     */
    public function getDefaultStoreName()
    {
        return $this->getLocator()->store()->facade()->getCurrentStore()->getName();
    }

    /**
     * @param float $currentTaxRate
     * @param string $iso2Code
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function createShipmentMethodWithTaxSet(float $currentTaxRate, string $iso2Code): ShipmentMethodTransfer
    {
        $idCountry = SpyCountryQuery::create()->filterByIso2Code($iso2Code)->findOne()->getIdCountry();
        $taxSetTransfer = $this->haveTaxSetWithTaxRates([], [
            [
                TaxRateTransfer::FK_COUNTRY => $idCountry,
                TaxRateTransfer::NAME => 'test tax rate 1',
                TaxRateTransfer::RATE => $currentTaxRate,
            ],
            [
                TaxRateTransfer::FK_COUNTRY => $idCountry,
                TaxRateTransfer::NAME => 'test tax rate 2',
                TaxRateTransfer::RATE => 5.00,
            ],
            [
                TaxRateTransfer::FK_COUNTRY => $idCountry,
                TaxRateTransfer::NAME => TaxConstants::TAX_EXEMPT_PLACEHOLDER,
                TaxRateTransfer::RATE => 0.00,
            ],
        ]);

        return $this->haveShipmentMethod([ShipmentMethodTransfer::FK_TAX_SET => $taxSetTransfer->getIdTaxSet()]);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer|null $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function createProductWithTaxSetInDb(?ShipmentMethodTransfer $shipmentMethodTransfer): ProductAbstractTransfer
    {
        $productAbstractOverride = [];
        if ($shipmentMethodTransfer !== null) {
            $productAbstractOverride[ProductAbstractTransfer::ID_TAX_SET] = $shipmentMethodTransfer->getFkTaxSet();
        }

        return $this->haveProductAbstract($productAbstractOverride);
    }

    /**
     * @param string $countryIso2Code
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethodTransferList
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodByAddressIso2CodeInShipmentMethodTransferList(
        string $countryIso2Code,
        array $shipmentMethodTransferList = []
    ): ?ShipmentMethodTransfer {
        if (!isset($shipmentMethodTransferList[$countryIso2Code])) {
            return null;
        }

        return $shipmentMethodTransferList[$countryIso2Code];
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $testStateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createOrderWithoutShipment(QuoteTransfer $quoteTransfer, ?string $testStateMachineProcessName = 'Test01'): SaveOrderTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->haveProduct($itemTransfer->toArray());
        }
        $savedOrderTransfer = $this->haveOrderUsingPreparedQuoteTransfer($quoteTransfer, $testStateMachineProcessName);

        return $savedOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $testStateMachineProcessName
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function createOrderWithMultiShipment(QuoteTransfer $quoteTransfer, ?string $testStateMachineProcessName = 'Test01'): SaveOrderTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->haveProduct($itemTransfer->toArray());
        }
        $savedOrderTransfer = $this->haveOrderUsingPreparedQuoteTransfer(
            $quoteTransfer,
            $testStateMachineProcessName,
            [new OrderShipmentSavePlugin()]
        );

        return $savedOrderTransfer;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransferByIdSalesOrder(int $idSalesOrder): OrderTransfer
    {
        $orderTransfer = $this->getLocator()->sales()->facade()->getOrderByIdSalesOrder($idSalesOrder);
        $orderTransfer = (new ShipmentOrderHydratePlugin())->hydrate($orderTransfer);

        return $orderTransfer;
    }

    /**
     * @param array $originalQuoteSeed
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function buildCalculableObjectTransfer(array $originalQuoteSeed = []): CalculableObjectTransfer
    {
        $shipmentTransfer = (new ShipmentBuilder())
            ->build()
            ->setMethod(
                $this->haveShipmentMethod()
            );

        $originalQuoteTransfer = (new QuoteBuilder())
            ->build()
            ->setShipment($shipmentTransfer)
            ->fromArray($originalQuoteSeed);

        return (new CalculableObjectBuilder())
            ->build()
            ->setOriginalQuote($originalQuoteTransfer);
    }
}
