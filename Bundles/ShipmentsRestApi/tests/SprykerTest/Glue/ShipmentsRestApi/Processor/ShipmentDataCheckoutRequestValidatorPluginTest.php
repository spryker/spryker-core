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
use Spryker\Glue\ShipmentsRestApi\Plugin\CheckoutRestApi\ShipmentDataCheckoutRequestValidatorPlugin;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiConfig;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiDependencyProvider;
use Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\ShippingAddressValidationStrategyPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentsRestApi
 * @group Processor
 * @group ShipmentDataCheckoutRequestValidatorPluginTest
 * Add your own group annotations below this line
 */
class ShipmentDataCheckoutRequestValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\ShipmentsRestApi\ShipmentsRestApiProcessorTester
     */
    protected $tester;

    /**
     * @dataProvider shipmentDataCheckoutRequestValidatorPluginDataProvider
     *
     * @param \Generated\Shared\Transfer\RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer
     * @param int $expectedErrorsCount
     * @param string $message
     *
     * @return void
     */
    public function testShipmentDataCheckoutRequestValidatorPlugin(
        RestCheckoutRequestAttributesTransfer $restCheckoutRequestAttributesTransfer,
        int $expectedErrorsCount,
        string $message
    ): void {
        // Act
        $restErrorCollectionTransfer = (new ShipmentDataCheckoutRequestValidatorPlugin())
            ->validateAttributes($restCheckoutRequestAttributesTransfer);

        // Assert
        $this->assertCount(
            $expectedErrorsCount,
            $restErrorCollectionTransfer->getRestErrors(),
            $message,
        );
    }

    /**
     * @return void
     */
    public function testValidateExecutesShippingAddressValidationStrategyPluginsWhenPluginsExecutionIsConfigured(): void
    {
        // Arrange
        $configMock = $this->getMockBuilder(ShipmentsRestApiConfig::class)->getMock();
        $configMock->method('shouldExecuteShippingAddressValidationStrategyPlugins')->willReturn(true);

        $shippingAddressValidationStrategyPluginMock = $this
            ->getMockBuilder(ShippingAddressValidationStrategyPluginInterface::class)
            ->getMock();
        $shippingAddressValidationStrategyPluginMock->method('isApplicable')->willReturn(true);
        $shippingAddressValidationStrategyPluginMock->expects($this->once())->method('validate');

        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->setShipment((new RestShipmentTransfer()))
            ->setShippingAddress((new RestAddressTransfer()));

        $this->tester->mockFactoryMethod('getConfig', $configMock);
        $factory = $this->tester->mockFactoryMethod('getShippingAddressValidationStrategyPlugins', [$shippingAddressValidationStrategyPluginMock]);
        $factory->setConfig($configMock);

        // Act, Assert
        (new ShipmentDataCheckoutRequestValidatorPlugin())
            ->setFactory($factory)
            ->validateAttributes($restCheckoutRequestAttributesTransfer);
    }

    /**
     * @return void
     */
    public function testValidateDoesNotExecutesShippingAddressValidationStrategyPluginsWhenPluginsExecutionIsNotConfigured(): void
    {
        // Arrange
        $shippingAddressValidationStrategyPluginMock = $this
            ->getMockBuilder(ShippingAddressValidationStrategyPluginInterface::class)
            ->getMock();
        $shippingAddressValidationStrategyPluginMock->expects($this->never())->method('validate');

        $this->tester->setDependency(
            ShipmentsRestApiDependencyProvider::PLUGINS_SHIPPING_ADDRESS_VALIDATION_STRATEGY,
            [$shippingAddressValidationStrategyPluginMock],
        );

        // Act, Assert
        (new ShipmentDataCheckoutRequestValidatorPlugin())
            ->validateAttributes(new RestCheckoutRequestAttributesTransfer());
    }

    /**
     * @return array<mixed>
     */
    public function shipmentDataCheckoutRequestValidatorPluginDataProvider(): array
    {
        return [
            [
                (new RestCheckoutRequestAttributesTransfer())
                    ->setShipment((new RestShipmentTransfer()))
                    ->setShippingAddress((new RestAddressTransfer())),
                0,
                'Passing single shipment method should be valid.',
            ],
            [
                (new RestCheckoutRequestAttributesTransfer())
                    ->setShippingAddress((new RestAddressTransfer())),
                1,
                'Passing no single shipment method should not be valid.',
            ],
            [
                (new RestCheckoutRequestAttributesTransfer())
                    ->setShipment((new RestShipmentTransfer())),
                1,
                'Passing no shipping address should not be valid.',
            ],
            [
                (new RestCheckoutRequestAttributesTransfer()),
                1,
                'Passing no shipment method should not be valid.',
            ],
            [
                (new RestCheckoutRequestAttributesTransfer())
                    ->addShipment(new RestShipmentsTransfer()),
                1,
                'Passing item level shipments without address should not be valid.',
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
