<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeServicePointsRestApi;

use Codeception\Actor;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCustomerTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ShipmentType\Communication\Plugin\Shipment\ShipmentTypeShipmentMethodCollectionExpanderPlugin;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\ShipmentTypeServicePointsRestApiFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Zed\ShipmentTypeServicePointsRestApi\PHPMD)
 */
class ShipmentTypeServicePointsRestApiBusinessTester extends Actor
{
    use _generated\ShipmentTypeServicePointsRestApiBusinessTesterActions;

    /**
     * @uses \Spryker\Zed\Shipment\ShipmentDependencyProvider::PLUGINS_SHIPMENT_METHOD_COLLECTION_EXPANDER
     *
     * @var string
     */
    protected const PLUGINS_SHIPMENT_METHOD_COLLECTION_EXPANDER = 'PLUGINS_SHIPMENT_METHOD_COLLECTION_EXPANDER';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_KEY_APPLICABLE = 'test-pickup';

    /**
     * @var string
     */
    protected const CURRENCY_EUR = 'EUR';

    /**
     * @return void
     */
    public function mockGetApplicableShipmentTypeKeysForShippingAddressConfigMethod(): void
    {
        $this->mockConfigMethod('getApplicableShipmentTypeKeysForShippingAddress', [
            static::SHIPMENT_TYPE_KEY_APPLICABLE,
        ]);
    }

    /**
     * @return void
     */
    public function setUpShipmentTypeShipmentMethodCollectionExpanderPluginDependency(): void
    {
        $this->setDependency(static::PLUGINS_SHIPMENT_METHOD_COLLECTION_EXPANDER, function () {
            return [
                new ShipmentTypeShipmentMethodCollectionExpanderPlugin(),
            ];
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function haveShipmentMethodWithApplicableShipmentType(StoreTransfer $storeTransfer): ShipmentMethodTransfer
    {
        $shipmentTypeTransfer = $this->haveShipmentType([
            ShipmentTypeTransfer::KEY => static::SHIPMENT_TYPE_KEY_APPLICABLE,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);
        $shipmentMethodTransfer = $this->haveShipmentMethod(
            [ShipmentMethodTransfer::IS_ACTIVE => true],
            [],
            $this->createShipmentMethodPriceList($storeTransfer),
            [$storeTransfer->getIdStore()],
        );

        $this->haveShipmentMethodShipmentTypeRelation(
            $shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        return $shipmentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param array<string, mixed> $shippingAddressSeedData
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(
        StoreTransfer $storeTransfer,
        ShipmentMethodTransfer $shipmentMethodTransfer,
        array $shippingAddressSeedData = []
    ): QuoteTransfer {
        $addressTransfer = (new AddressBuilder($shippingAddressSeedData))->build();
        $itemBuilder = (new ItemBuilder())
            ->withShipment([
                ShipmentTransfer::METHOD => $shipmentMethodTransfer->toArray(),
                ShipmentTransfer::SHIPPING_ADDRESS => $addressTransfer->toArray(),
            ]);

        return (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_EUR])
            ->withItem($itemBuilder)
            ->withShipment([
                ShipmentTransfer::METHOD => $shipmentMethodTransfer->toArray(),
            ])
            ->withShippingAddress($addressTransfer->toArray())
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createRestCheckoutRequestAttributesTransfer(CustomerTransfer $customerTransfer): RestCheckoutRequestAttributesTransfer
    {
        return (new RestCheckoutRequestAttributesBuilder())
            ->withCustomer([RestCustomerTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference()])
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<string, array<string, array<mixed>>>
     */
    protected function createShipmentMethodPriceList(StoreTransfer $storeTransfer): array
    {
        return [
            $storeTransfer->getNameOrFail() => [
                static::CURRENCY_EUR => [],
            ],
        ];
    }
}
