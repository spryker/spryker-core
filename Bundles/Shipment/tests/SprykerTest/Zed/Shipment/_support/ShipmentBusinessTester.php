<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Shipment;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\CalculableObjectBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Service\Shipment\ShipmentServiceInterface;
use Spryker\Shared\Shipment\ShipmentConfig as SharedShipmentConfig;
use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Shipment\Business\ShipmentFacadeInterface;
use Spryker\Zed\Shipment\Communication\Plugin\Checkout\OrderShipmentSavePlugin;
use Spryker\Zed\Shipment\Communication\Plugin\ShipmentOrderHydratePlugin;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ShipmentBusinessTester extends Actor
{
    use _generated\ShipmentBusinessTesterActions;

    /**
     * @var string
     */
    protected const FAKE_EXPENSE_TYPE = 'FAKE_EXPENSE_TYPE';

    /**
     * @return \Spryker\Zed\Shipment\Business\ShipmentFacadeInterface
     */
    public function getShipmentFacade(): ShipmentFacadeInterface
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
     * @return array<int>
     */
    public function getIdShipmentMethodCollection(ShipmentMethodsTransfer $shipmentMethodsTransfer): array
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
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethod(ShipmentMethodsTransfer $shipmentMethodsTransfer, int $idShipmentMethod)
    {
        foreach ($shipmentMethodsTransfer->getMethods() as $shipmentMethodTransfer) {
            if ($shipmentMethodTransfer->getIdShipmentMethod() === $idShipmentMethod) {
                return $shipmentMethodTransfer;
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $data
     * @param array|null $idFilter
     *
     * @return void
     */
    public function updateShipmentMethod(array $data, ?array $idFilter = null): void
    {
        $shipmentMethodQuery = $this->getShipmentMethodQuery();

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
    public function disableAllShipmentMethods(): void
    {
        $this->updateShipmentMethod(['is_active' => false]);
    }

    /**
     * @param int $shipmentMethodCount
     *
     * @return array<\Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function haveActiveShipmentMethods(int $shipmentMethodCount): array
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
    public function getDefaultStoreName(): string
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
     * @param array<\Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethodTransferList
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
            [new OrderShipmentSavePlugin()],
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
                $this->haveShipmentMethod(),
            );

        $originalQuoteTransfer = (new QuoteBuilder())
            ->build()
            ->setShipment($shipmentTransfer)
            ->fromArray($originalQuoteSeed);

        return (new CalculableObjectBuilder())
            ->build()
            ->setOriginalQuote($originalQuoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $iso2Code
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addNewItemIntoQuoteTransfer(
        QuoteTransfer $quoteTransfer,
        string $iso2Code,
        ShipmentMethodTransfer $shipmentMethodTransfer
    ): QuoteTransfer {
        $addressBuilder = (new AddressBuilder([AddressTransfer::ISO2_CODE => $iso2Code]));
        $shipmentTransfer = (new ShipmentBuilder())
            ->withShippingAddress($addressBuilder)
            ->build();

        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        $itemTransfer = (new ItemBuilder())->build();
        $itemTransfer->setShipment($shipmentTransfer);

        $quoteTransfer->addItem($itemTransfer);

        return $quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function createCalculableObjectWithFakeExpenses(): CalculableObjectTransfer
    {
        $expenseTransfers = [
            (new ExpenseTransfer())
                ->setType(SharedShipmentConfig::SHIPMENT_EXPENSE_TYPE)
                ->setSumPrice(100),
            (new ExpenseTransfer())
                ->setType(SharedShipmentConfig::SHIPMENT_EXPENSE_TYPE)
                ->setSumPrice(200),
            (new ExpenseTransfer())
                ->setType(static::FAKE_EXPENSE_TYPE)
                ->setSumPrice(300),
        ];

        return (new CalculableObjectTransfer())
            ->setExpenses(new ArrayObject($expenseTransfers))
            ->setTotals(new TotalsTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param string $pricePluginDependencyKey
     *
     * @return void
     */
    public function assignShipmentPricePluginToShipmentMethod(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        string $pricePluginDependencyKey
    ): void {
        $shipmentMethodEntity = $this->getShipmentMethodQuery()
            ->findOneByIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail());

        $shipmentMethodEntity->setPricePlugin($pricePluginDependencyKey);
        $shipmentMethodEntity->save();
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected function getShipmentMethodQuery(): SpyShipmentMethodQuery
    {
        return SpyShipmentMethodQuery::create();
    }
}
