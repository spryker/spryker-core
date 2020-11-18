<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ShipmentsRestApi\Processor;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestAddressTransfer;
use Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer;
use Generated\Shared\Transfer\RestShipmentsTransfer;
use Generated\Shared\Transfer\RestShipmentTransfer;
use Spryker\Glue\ShipmentsRestApi\Plugin\CheckoutRestApi\ShipmentDataCheckoutRequestAttributesValidatorPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentsRestApi
 * @group Processor
 * @group ShipmentDataCheckoutRequestAttributesValidatorPluginTest
 * Add your own group annotations below this line
 */
class ShipmentDataCheckoutRequestAttributesValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\ShipmentsRestApi\ShipmentsRestApiProcessorTester
     */
    protected $tester;

    /**
     * @dataProvider shipmentDataCheckoutRequestAttributesValidatorPluginDataProvider
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param int $expectedErrorsCount
     * @param string $message
     *
     * @return void
     */
    public function testShipmentDataCheckoutRequestAttributesValidatorPlugin(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        int $expectedErrorsCount,
        string $message
    ): void {
        // Act
        $restErrorCollectionTransfer = (new ShipmentDataCheckoutRequestAttributesValidatorPlugin())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertCount(
            $expectedErrorsCount,
            $restErrorCollectionTransfer->getRestErrors(),
            $message
        );
    }

    /**
     * @return mixed[]
     */
    public function shipmentDataCheckoutRequestAttributesValidatorPluginDataProvider(): array
    {
        return [
            [
                (new RestCheckoutRequestAttributesTransfer())
                    ->setShipment((new RestShipmentTransfer())),
                0,
                'Passing single shipment method should be valid.',
            ],
            [
                (new RestCheckoutRequestAttributesTransfer())
                    ->setShippingAddress((new RestAddressTransfer())),
                0,
                'Passing single shipping address should be valid.',
            ],
            [
                (new RestCheckoutRequestAttributesTransfer()),
                1,
                'Passing no shipment method should not be valid.',
            ],
            [
                (new RestCheckoutRequestAttributesTransfer())
                    ->addShipment(new RestShipmentsTransfer()),
                0,
                'Passing item level shipments should be valid.',
            ],
            [
                (new RestCheckoutRequestAttributesTransfer())
                    ->setShipment((new RestShipmentTransfer()))
                    ->addShipment(new RestShipmentsTransfer()),
                1,
                'Passing item level shipments + single shipment method should not be valid.',
            ],
            [
                (new RestCheckoutRequestAttributesTransfer())
                    ->setShippingAddress((new RestAddressTransfer()))
                    ->addShipment(new RestShipmentsTransfer()),
                1,
                'Passing item level shipments + single shipping address should not be valid.',
            ],
            [
                (new RestCheckoutRequestAttributesTransfer())
                    ->setShipment((new RestShipmentTransfer()))
                    ->setShippingAddress((new RestAddressTransfer()))
                    ->addShipment(new RestShipmentsTransfer()),
                1,
                'Passing item level shipments + single shipping address + single shipment method should not be valid.',
            ],
        ];
    }
}
