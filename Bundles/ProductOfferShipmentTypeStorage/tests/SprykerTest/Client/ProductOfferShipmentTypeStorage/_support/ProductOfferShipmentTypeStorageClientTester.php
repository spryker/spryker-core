<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductOfferShipmentTypeStorage;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\ProductOfferShipmentTypeStorage\Dependency\Client\ProductOfferShipmentTypeStorageToStoreClientInterface;
use Spryker\Client\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageDependencyProvider;
use Spryker\Shared\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageConfig;

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
 * @method \Spryker\Client\ProductOfferShipmentTypeStorage\ProductOfferShipmentTypeStorageClientInterface getClient(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Client\ProductOfferShipmentTypeStorage\PHPMD)
 */
class ProductOfferShipmentTypeStorageClientTester extends Actor
{
    use _generated\ProductOfferShipmentTypeStorageClientTesterActions;

    /**
     * @var string
     */
    protected const TEMPLATE_STORAGE_KEY = '%s:%s:%s';

    /**
     * @var string
     */
    protected const TEMPLATE_STORAGE_KEY_MAPPING = '%s:%s:uuid:%s';

    /**
     * @uses \Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig::SHIPMENT_TYPE_RESOURCE_NAME
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_RESOURCE_NAME = 'shipment_type';

    /**
     * @var string
     */
    protected const PAYLOAD_KEY_ID = 'id';

    /**
     * @param string $currentStore
     *
     * @return void
     */
    public function mockStoreClient(string $currentStore): void
    {
        $storeClientMock = Stub::makeEmpty(
            ProductOfferShipmentTypeStorageToStoreClientInterface::class,
            [
                'getCurrentStore' => (new StoreTransfer())->setName($currentStore),
            ],
        );

        $this->setDependency(ProductOfferShipmentTypeStorageDependencyProvider::CLIENT_STORE, $storeClientMock);
    }

    /**
     * @param string $productOfferReference
     * @param array<string, mixed> $payload
     * @param string $store
     *
     * @return void
     */
    public function mockProductOfferShipmentTypeStorageData(string $productOfferReference, array $payload, string $store): void
    {
        $productOfferShipmentTypeKey = sprintf(
            static::TEMPLATE_STORAGE_KEY,
            ProductOfferShipmentTypeStorageConfig::PRODUCT_OFFER_SHIPMENT_TYPE_RESOURCE_NAME,
            strtolower($store),
            $productOfferReference,
        );

        $this->mockStorageData($productOfferShipmentTypeKey, json_encode($payload));
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param string $store
     *
     * @return void
     */
    public function mockShipmentTypeStorageData(ShipmentTypeTransfer $shipmentTypeTransfer, string $store): void
    {
        $shipmentTypeKey = sprintf(
            static::TEMPLATE_STORAGE_KEY,
            static::SHIPMENT_TYPE_RESOURCE_NAME,
            strtolower($store),
            $shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $this->mockStorageData(
            $shipmentTypeKey,
            json_encode([ShipmentTypeStorageTransfer::UUID => $shipmentTypeTransfer->getUuidOrFail()]),
        );

        $this->mockShipmentTypeStorageMappingData($shipmentTypeTransfer, $store);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param string $store
     *
     * @return void
     */
    protected function mockShipmentTypeStorageMappingData(ShipmentTypeTransfer $shipmentTypeTransfer, string $store): void
    {
        $shipmentTypeMappingKey = sprintf(
            static::TEMPLATE_STORAGE_KEY_MAPPING,
            static::SHIPMENT_TYPE_RESOURCE_NAME,
            strtolower($store),
            $shipmentTypeTransfer->getUuidOrFail(),
        );

        $this->mockStorageData(
            $shipmentTypeMappingKey,
            json_encode([static::PAYLOAD_KEY_ID => $shipmentTypeTransfer->getIdShipmentTypeOrFail()]),
        );
    }
}
