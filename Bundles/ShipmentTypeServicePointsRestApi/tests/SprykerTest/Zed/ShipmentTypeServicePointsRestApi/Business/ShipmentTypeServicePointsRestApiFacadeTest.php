<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentTypeServicePointsRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\RestShipmentTransfer;
use SprykerTest\Zed\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiBusinessTester as ShipmentTypeServicePointsRestApiBusinessTesterAlias;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentTypeServicePointsRestApi
 * @group Business
 * @group Facade
 * @group ShipmentTypeServicePointsRestApiFacadeTest
 * Add your own group annotations below this line
 */
class ShipmentTypeServicePointsRestApiFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_FIRST_NAME = 'firstname';

    /**
     * @var string
     */
    protected const TEST_LAST_NAME = 'lastname';

    /**
     * @var string
     */
    protected const TEST_SALUTATION = 'salutation';

    /**
     * @var \SprykerTest\Zed\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiBusinessTester
     */
    protected ShipmentTypeServicePointsRestApiBusinessTesterAlias $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->mockGetApplicableShipmentTypeKeysForShippingAddressConfigMethod();
        $this->tester->setUpShipmentTypeShipmentMethodCollectionExpanderPluginDependency();
    }

    /**
     * @return void
     */
    public function testMapCustomerAddressDataToShippingAddressesExpandsQuoteLevelShippingAddressWithCustomerData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $shipmentMethodTransfer = $this->tester->haveShipmentMethodWithApplicableShipmentType($storeTransfer);
        $customerTransfer = $this->tester->haveCustomer();

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer, $shipmentMethodTransfer, [
            AddressTransfer::FIRST_NAME => null,
            AddressTransfer::LAST_NAME => null,
            AddressTransfer::SALUTATION => null,
        ]);
        $restCheckoutRequestAttributesTransfer = $this->tester->createRestCheckoutRequestAttributesTransferWithSingleShipment(
            $customerTransfer,
            $shipmentMethodTransfer,
        );
        $restCheckoutRequestAttributesTransfer->setShipment((new RestShipmentTransfer())->setIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethodOrFail()));

        // Act
        $quoteTransfer = $this->tester->getFacade()->mapCustomerAddressDataToShippingAddresses(
            $restCheckoutRequestAttributesTransfer,
            $quoteTransfer,
        );

        // Assert
        $this->assertSame($customerTransfer->getFirstNameOrFail(), $quoteTransfer->getShippingAddressOrFail()->getFirstName());
        $this->assertSame($customerTransfer->getLastNameOrFail(), $quoteTransfer->getShippingAddressOrFail()->getLastName());
        $this->assertSame($customerTransfer->getSalutationOrFail(), $quoteTransfer->getShippingAddressOrFail()->getSalutation());
    }

    /**
     * @return void
     */
    public function testMapCustomerAddressDataToShippingAddressesExpandsQuoteLevelShipmentShippingAddressWithCustomerData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $shipmentMethodTransfer = $this->tester->haveShipmentMethodWithApplicableShipmentType($storeTransfer);
        $customerTransfer = $this->tester->haveCustomer();

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer, $shipmentMethodTransfer, [
            AddressTransfer::FIRST_NAME => null,
            AddressTransfer::LAST_NAME => null,
            AddressTransfer::SALUTATION => null,
        ]);
        $restCheckoutRequestAttributesTransfer = $this->tester->createRestCheckoutRequestAttributesTransferWithSingleShipment(
            $customerTransfer,
            $shipmentMethodTransfer,
        );

        // Act
        $quoteTransfer = $this->tester->getFacade()->mapCustomerAddressDataToShippingAddresses(
            $restCheckoutRequestAttributesTransfer,
            $quoteTransfer,
        );

        // Assert
        $shippingAddressTransfer = $quoteTransfer->getShipment()->getShippingAddress();
        $this->assertSame($customerTransfer->getFirstNameOrFail(), $shippingAddressTransfer->getFirstName());
        $this->assertSame($customerTransfer->getLastNameOrFail(), $shippingAddressTransfer->getLastName());
        $this->assertSame($customerTransfer->getSalutationOrFail(), $shippingAddressTransfer->getSalutation());
    }

    /**
     * @return void
     */
    public function testMapCustomerAddressDataToShippingAddressesExpandsItemLevelShippingAddressWithCustomerData(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $shipmentMethodTransfer = $this->tester->haveShipmentMethodWithApplicableShipmentType($storeTransfer);
        $customerTransfer = $this->tester->haveCustomer();

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer, $shipmentMethodTransfer, [
            AddressTransfer::FIRST_NAME => null,
            AddressTransfer::LAST_NAME => null,
            AddressTransfer::SALUTATION => null,
        ]);
        $restCheckoutRequestAttributesTransfer = $this->tester->createRestCheckoutRequestAttributesTransferWithSplitShipment(
            $customerTransfer,
            $shipmentMethodTransfer,
        );

        // Act
        $quoteTransfer = $this->tester->getFacade()->mapCustomerAddressDataToShippingAddresses(
            $restCheckoutRequestAttributesTransfer,
            $quoteTransfer,
        );

        // Assert
        $shippingAddressTransfer = $quoteTransfer->getItems()->getIterator()->current()->getShipment()->getShippingAddress();
        $this->assertSame($customerTransfer->getFirstNameOrFail(), $shippingAddressTransfer->getFirstName());
        $this->assertSame($customerTransfer->getLastNameOrFail(), $shippingAddressTransfer->getLastName());
        $this->assertSame($customerTransfer->getSalutationOrFail(), $shippingAddressTransfer->getSalutation());
    }

    /**
     * @return void
     */
    public function testMapCustomerAddressDataToShippingAddressesDoesNothingWhenCustomerDataIsSetInShippingAddress(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();

        $shipmentMethodTransfer = $this->tester->haveShipmentMethodWithApplicableShipmentType($storeTransfer);
        $customerTransfer = $this->tester->haveCustomer();

        $quoteTransfer = $this->tester->createQuoteTransfer($storeTransfer, $shipmentMethodTransfer, [
            AddressTransfer::FIRST_NAME => static::TEST_FIRST_NAME,
            AddressTransfer::LAST_NAME => static::TEST_LAST_NAME,
            AddressTransfer::SALUTATION => static::TEST_SALUTATION,
        ]);
        $restCheckoutRequestAttributesTransfer = $this->tester->createRestCheckoutRequestAttributesTransferWithSingleShipment(
            $customerTransfer,
            $shipmentMethodTransfer,
        );

        // Act
        $quoteTransfer = $this->tester->getFacade()->mapCustomerAddressDataToShippingAddresses(
            $restCheckoutRequestAttributesTransfer,
            $quoteTransfer,
        );

        // Assert
        $this->assertSame(static::TEST_FIRST_NAME, $quoteTransfer->getShippingAddressOrFail()->getFirstName());
        $this->assertSame(static::TEST_LAST_NAME, $quoteTransfer->getShippingAddressOrFail()->getLastName());
        $this->assertSame(static::TEST_SALUTATION, $quoteTransfer->getShippingAddressOrFail()->getSalutation());

        $shippingAddressTransfer = $quoteTransfer->getItems()->getIterator()->current()->getShipment()->getShippingAddress();
        $this->assertSame(static::TEST_FIRST_NAME, $shippingAddressTransfer->getFirstName());
        $this->assertSame(static::TEST_LAST_NAME, $shippingAddressTransfer->getLastName());
        $this->assertSame(static::TEST_SALUTATION, $shippingAddressTransfer->getSalutation());
    }
}
