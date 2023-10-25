<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentTypeServicePointsRestApi;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\RestAddressBuilder;
use Generated\Shared\DataBuilder\RestCheckoutRequestAttributesBuilder;
use Generated\Shared\DataBuilder\RestServicePointBuilder;
use Generated\Shared\DataBuilder\RestShipmentsBuilder;
use Generated\Shared\DataBuilder\ServicePointAddressStorageBuilder;
use Generated\Shared\DataBuilder\ServicePointStorageBuilder;
use Generated\Shared\DataBuilder\ShipmentTypeStorageBuilder;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestCustomerTransfer;
use Generated\Shared\Transfer\RestServicePointTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Generated\Shared\Transfer\RestShipmentTransfer;
use Generated\Shared\Transfer\ServicePointAddressStorageTransfer;
use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToServicePointStorageClientInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\Dependency\Client\ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface;
use Spryker\Glue\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiDependencyProvider;

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
 *
 * @SuppressWarnings(\SprykerTest\Glue\ShipmentTypeServicePointsRestApi\PHPMD)
 */
class ShipmentTypeServicePointsRestApiTester extends Actor
{
    use _generated\ShipmentTypeServicePointsRestApiTesterActions;

    /**
     * @var string
     */
    public const ITEM_GROUP_KEY_1 = 'item-group-key-1';

    /**
     * @var string
     */
    public const ITEM_GROUP_KEY_2 = 'item-group-key-2';

    /**
     * @var string
     */
    public const SERVICE_POINT_UUID_1 = 'service-point-uuid-1';

    /**
     * @var string
     */
    protected const COL_FK_SHIPMENT_TYPE = 'FkShipmentType';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_KEY_APPLICABLE = 'test-pickup';

    /**
     * @var string
     */
    protected const SHIPMENT_TYPE_KEY_NON_APPLICABLE = 'test-non-pickable';

    /**
     * @var int
     */
    protected const APPLICABLE_SHIPMENT_METHOD_ID = 1;

    /**
     * @var int
     */
    protected const NON_APPLICABLE_SHIPMENT_METHOD_ID = 2;

    /**
     * @var string
     */
    protected const SERVICE_POINT_UUID_2 = 'service-point-uuid-2';

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
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     *
     * @return void
     */
    public function mockShipmentTypeStorageClient(array $shipmentTypeStorageTransfers): void
    {
        $shipmentTypeStorageCollection = (new ShipmentTypeStorageCollectionTransfer())
            ->setShipmentTypeStorages(new ArrayObject($shipmentTypeStorageTransfers));

        $shipmentTypeStorageClientMock = Stub::makeEmpty(
            ShipmentTypeServicePointsRestApiToShipmentTypeStorageClientInterface::class,
            [
                'getShipmentTypeStorageCollection' => $shipmentTypeStorageCollection,
            ],
        );

        $this->setDependency(
            ShipmentTypeServicePointsRestApiDependencyProvider::CLIENT_SHIPMENT_TYPE_STORAGE,
            $shipmentTypeStorageClientMock,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointStorageTransfer> $servicePointStorageTransfers
     *
     * @return void
     */
    public function mockServicePointStorageClient(array $servicePointStorageTransfers): void
    {
        $servicePointStorageCollection = (new ServicePointStorageCollectionTransfer())
            ->setServicePointStorages(new ArrayObject($servicePointStorageTransfers));

        $servicePointStorageClientMock = Stub::makeEmpty(
            ShipmentTypeServicePointsRestApiToServicePointStorageClientInterface::class,
            [
                'getServicePointStorageCollection' => $servicePointStorageCollection,
            ],
        );

        $this->setDependency(
            ShipmentTypeServicePointsRestApiDependencyProvider::CLIENT_SERVICE_POINT_STORAGE,
            $servicePointStorageClientMock,
        );
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageTransfer
     */
    public function createApplicableShipmentTypeStorageTransfer(): ShipmentTypeStorageTransfer
    {
        return $this->createShipmentTypeStorageTransfer([
            ShipmentTypeStorageTransfer::KEY => static::SHIPMENT_TYPE_KEY_APPLICABLE,
            ShipmentTypeStorageTransfer::SHIPMENT_METHOD_IDS => [static::APPLICABLE_SHIPMENT_METHOD_ID],
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageTransfer
     */
    public function createNonApplicableShipmentTypeStorageTransfer(): ShipmentTypeStorageTransfer
    {
        return $this->createShipmentTypeStorageTransfer([
            ShipmentTypeStorageTransfer::KEY => static::SHIPMENT_TYPE_KEY_NON_APPLICABLE,
            ShipmentTypeStorageTransfer::SHIPMENT_METHOD_IDS => [static::SHIPMENT_TYPE_KEY_NON_APPLICABLE],
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\ServicePointStorageTransfer
     */
    public function createServicePointStorageTransfer(): ServicePointStorageTransfer
    {
        return (new ServicePointStorageBuilder([
            ServicePointStorageTransfer::UUID => static::SERVICE_POINT_UUID_1,
        ]))->build();
    }

    /**
     * @return \Generated\Shared\Transfer\ServicePointStorageTransfer
     */
    public function createServicePointStorageTransferWithAddress(): ServicePointStorageTransfer
    {
        return (new ServicePointStorageBuilder([
            ServicePointStorageTransfer::UUID => static::SERVICE_POINT_UUID_1,
        ]))->withAddress((new ServicePointAddressStorageBuilder())->withCountry())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithoutServicePoints(): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = $this->createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();

        return $restCheckoutRequestAttributesTransfer->setServicePoints(new ArrayObject([]));
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerFirstName(): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = $this->createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();
        $customerTransfer = $restCheckoutRequestAttributesTransfer
            ->getCustomerOrFail()
            ->setFirstName(null);

        return $restCheckoutRequestAttributesTransfer->setCustomer($customerTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerLastName(): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = $this->createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();
        $customerTransfer = $restCheckoutRequestAttributesTransfer
            ->getCustomerOrFail()
            ->setLastName(null);

        return $restCheckoutRequestAttributesTransfer->setCustomer($customerTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerSalutation(): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = $this->createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();
        $customerTransfer = $restCheckoutRequestAttributesTransfer
            ->getCustomerOrFail()
            ->setSalutation(null);

        return $restCheckoutRequestAttributesTransfer->setCustomer($customerTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod(): RestCheckoutRequestAttributesTransfer
    {
        return (new RestCheckoutRequestAttributesBuilder())
            ->withShipment([
                RestShipmentsTransfer::ITEMS => [static::ITEM_GROUP_KEY_1],
                RestShipmentsTransfer::ID_SHIPMENT_METHOD => static::APPLICABLE_SHIPMENT_METHOD_ID,
            ])
            ->withServicePoint([
                RestServicePointTransfer::ID_SERVICE_POINT => static::SERVICE_POINT_UUID_1,
                RestServicePointTransfer::ITEMS => [static::ITEM_GROUP_KEY_1],
            ])
            ->withCustomer()
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndNonApplicableShipmentMethod(): RestCheckoutRequestAttributesTransfer
    {
        return (new RestCheckoutRequestAttributesBuilder())
            ->withShipment((new RestShipmentsBuilder([
                RestShipmentsTransfer::ITEMS => [static::ITEM_GROUP_KEY_1],
                RestShipmentsTransfer::ID_SHIPMENT_METHOD => static::NON_APPLICABLE_SHIPMENT_METHOD_ID,
            ]))->withShippingAddress())
            ->withServicePoint([
                RestServicePointTransfer::ID_SERVICE_POINT => static::SERVICE_POINT_UUID_1,
                RestServicePointTransfer::ITEMS => [static::ITEM_GROUP_KEY_1],
            ])
            ->withCustomer()
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableAndNonApplicableShipmentMethods(): RestCheckoutRequestAttributesTransfer
    {
        return (new RestCheckoutRequestAttributesBuilder())
            ->withShipment([
                RestShipmentsTransfer::ITEMS => [static::ITEM_GROUP_KEY_1],
                RestShipmentsTransfer::ID_SHIPMENT_METHOD => static::APPLICABLE_SHIPMENT_METHOD_ID,
            ])
            ->withAnotherShipment((new RestShipmentsBuilder([
                RestShipmentsTransfer::ITEMS => [static::ITEM_GROUP_KEY_1],
                RestShipmentsTransfer::ID_SHIPMENT_METHOD => static::NON_APPLICABLE_SHIPMENT_METHOD_ID,
            ]))->withShippingAddress())
            ->withServicePoint([
                RestServicePointTransfer::ID_SERVICE_POINT => static::SERVICE_POINT_UUID_1,
                RestServicePointTransfer::ITEMS => [static::ITEM_GROUP_KEY_1],
            ])
            ->withCustomer()
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithoutServicePoints(): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = $this->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();

        return $restCheckoutRequestAttributesTransfer->setServicePoints(new ArrayObject([]));
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithServicePoints(): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = $this->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();

        return $restCheckoutRequestAttributesTransfer->addServicePoint(
            (new RestServicePointBuilder())->build(),
        );
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithMultipleServicePoints(): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = $this->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();

        return $restCheckoutRequestAttributesTransfer
            ->addServicePoint((new RestServicePointTransfer())->setIdServicePoint(static::SERVICE_POINT_UUID_1))
            ->addServicePoint((new RestServicePointTransfer())->setIdServicePoint(static::SERVICE_POINT_UUID_2));
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerFirstName(): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = $this->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();
        $customerTransfer = $restCheckoutRequestAttributesTransfer
            ->getCustomerOrFail()
            ->setFirstName(null);

        return $restCheckoutRequestAttributesTransfer->setCustomer($customerTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerLastName(): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = $this->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();
        $customerTransfer = $restCheckoutRequestAttributesTransfer
            ->getCustomerOrFail()
            ->setLastName(null);

        return $restCheckoutRequestAttributesTransfer->setCustomer($customerTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithEmptyCustomerSalutation(): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesTransfer = $this->createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod();
        $customerTransfer = $restCheckoutRequestAttributesTransfer
            ->getCustomerOrFail()
            ->setSalutation(null);

        return $restCheckoutRequestAttributesTransfer->setCustomer($customerTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndApplicableShipmentMethod(): RestCheckoutRequestAttributesTransfer
    {
        return (new RestCheckoutRequestAttributesBuilder([
            RestCheckoutRequestAttributesTransfer::SHIPMENT => [
                RestShipmentTransfer::ID_SHIPMENT_METHOD => static::APPLICABLE_SHIPMENT_METHOD_ID,
            ],
        ]))->withServicePoint([
                RestServicePointTransfer::ID_SERVICE_POINT => static::SERVICE_POINT_UUID_1,
                RestServicePointTransfer::ITEMS => [static::ITEM_GROUP_KEY_1],
            ])
            ->withCustomer()
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithValidAttributesAndNonApplicableShipmentMethod(): RestCheckoutRequestAttributesTransfer
    {
        return (new RestCheckoutRequestAttributesBuilder([
            RestCheckoutRequestAttributesTransfer::SHIPMENT => [
                RestShipmentTransfer::ID_SHIPMENT_METHOD => static::NON_APPLICABLE_SHIPMENT_METHOD_ID,
            ],
        ]))->withServicePoint([
            RestServicePointTransfer::ID_SERVICE_POINT => static::SERVICE_POINT_UUID_1,
            RestServicePointTransfer::ITEMS => [static::ITEM_GROUP_KEY_1],
        ])
            ->withShippingAddress()
            ->withCustomer()
            ->build();
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithApplicableShipmentMethod(): RestCheckoutRequestAttributesTransfer
    {
        return $this->createRestCheckoutRequestAttributesTransferWithShipments([
            [RestShipmentsTransfer::ID_SHIPMENT_METHOD => static::APPLICABLE_SHIPMENT_METHOD_ID],
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithNonApplicableShipmentMethod(): RestCheckoutRequestAttributesTransfer
    {
        return $this->createRestCheckoutRequestAttributesTransferWithShipments([
            [RestShipmentsTransfer::ID_SHIPMENT_METHOD => static::NON_APPLICABLE_SHIPMENT_METHOD_ID],
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithApplicableAndNonApplicableShipmentMethodsWithoutAddress(): RestCheckoutRequestAttributesTransfer
    {
        return $this->createRestCheckoutRequestAttributesTransferWithShipments([
            [
                RestShipmentsTransfer::ID_SHIPMENT_METHOD => static::APPLICABLE_SHIPMENT_METHOD_ID,
            ],
            [
                RestShipmentsTransfer::ITEMS => [static::ITEM_GROUP_KEY_1],
                RestShipmentsTransfer::ID_SHIPMENT_METHOD => static::NON_APPLICABLE_SHIPMENT_METHOD_ID,
                RestShipmentsTransfer::SHIPPING_ADDRESS => null,
            ],
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createMultiShipmentRestCheckoutRequestAttributesTransferWithApplicableAndNonApplicableShipmentMethods(): RestCheckoutRequestAttributesTransfer
    {
        return $this->createRestCheckoutRequestAttributesTransferWithShipments([
            [
                RestShipmentsTransfer::ID_SHIPMENT_METHOD => static::APPLICABLE_SHIPMENT_METHOD_ID,
            ],
            [
                RestShipmentsTransfer::ID_SHIPMENT_METHOD => static::NON_APPLICABLE_SHIPMENT_METHOD_ID,
                RestShipmentsTransfer::SHIPPING_ADDRESS => (new RestAddressBuilder())->build()->toArray(),
            ],
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithApplicableShipmentMethod(): RestCheckoutRequestAttributesTransfer
    {
        return $this->createRestCheckoutRequestAttributesTransferWithShipment([
            RestShipmentTransfer::ID_SHIPMENT_METHOD => static::APPLICABLE_SHIPMENT_METHOD_ID,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithApplicableShipmentMethodAndServicePoint(): RestCheckoutRequestAttributesTransfer
    {
        return $this->createRestCheckoutRequestAttributesTransferWithShipment([
            RestShipmentTransfer::ID_SHIPMENT_METHOD => static::APPLICABLE_SHIPMENT_METHOD_ID,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    public function createSingleShipmentRestCheckoutRequestAttributesTransferWithNonApplicableShipmentMethod(): RestCheckoutRequestAttributesTransfer
    {
        return $this->createRestCheckoutRequestAttributesTransferWithShipment([
            RestShipmentTransfer::ID_SHIPMENT_METHOD => static::NON_APPLICABLE_SHIPMENT_METHOD_ID,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCustomerTransfer $restCustomerTransfer
     * @param \Generated\Shared\Transfer\ServicePointAddressStorageTransfer $servicePointAddressStorageTransfer
     * @param \Generated\Shared\Transfer\RestAddressTransfer $restAddressTransfer
     *
     * @return void
     */
    public function assertShippingAddressReplaced(
        RestCustomerTransfer $restCustomerTransfer,
        ServicePointAddressStorageTransfer $servicePointAddressStorageTransfer,
        RestAddressTransfer $restAddressTransfer
    ): void {
        $this->assertSame($restCustomerTransfer->getFirstNameOrFail(), $restAddressTransfer->getFirstName());
        $this->assertSame($restCustomerTransfer->getLastNameOrFail(), $restAddressTransfer->getLastName());
        $this->assertSame($restCustomerTransfer->getSalutationOrFail(), $restAddressTransfer->getSalutation());
        $this->assertSame($servicePointAddressStorageTransfer->getAddress1OrFail(), $restAddressTransfer->getAddress1());
        $this->assertSame($servicePointAddressStorageTransfer->getAddress2OrFail(), $restAddressTransfer->getAddress2());
        $this->assertSame($servicePointAddressStorageTransfer->getAddress3OrFail(), $restAddressTransfer->getAddress3());
        $this->assertSame($servicePointAddressStorageTransfer->getZipCodeOrFail(), $restAddressTransfer->getZipCode());
        $this->assertSame($servicePointAddressStorageTransfer->getCityOrFail(), $restAddressTransfer->getCity());
        $this->assertSame($servicePointAddressStorageTransfer->getCountryOrFail()->getIso2CodeOrFail(), $restAddressTransfer->getIso2Code());
    }

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeStorageTransfer
     */
    protected function createShipmentTypeStorageTransfer(array $seedData = []): ShipmentTypeStorageTransfer
    {
        return (new ShipmentTypeStorageBuilder($seedData))->build();
    }

    /**
     * @param list<array<string, mixed>> $restShipmentsData
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function createRestCheckoutRequestAttributesTransferWithShipments(array $restShipmentsData): RestCheckoutRequestAttributesTransfer
    {
        $restCheckoutRequestAttributesBuilder = (new RestCheckoutRequestAttributesBuilder());
        foreach ($restShipmentsData as $restShipmentsDataItem) {
            $restCheckoutRequestAttributesBuilder->withAnotherShipment($restShipmentsDataItem);
        }

        return $restCheckoutRequestAttributesBuilder->build();
    }

    /**
     * @param array<string, mixed> $restShipmentData
     *
     * @return \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer
     */
    protected function createRestCheckoutRequestAttributesTransferWithShipment(array $restShipmentData): RestCheckoutRequestAttributesTransfer
    {
        return (new RestCheckoutRequestAttributesBuilder([
            RestCheckoutRequestAttributesTransfer::SHIPMENT => $restShipmentData,
        ]))->build();
    }
}
