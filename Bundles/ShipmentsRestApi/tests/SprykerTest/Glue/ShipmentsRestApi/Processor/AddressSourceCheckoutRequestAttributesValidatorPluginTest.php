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
use Spryker\Glue\ShipmentsRestApi\Plugin\CheckoutRestApi\AddressSourceCheckoutRequestAttributesValidatorPlugin;
use Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiFactory;
use Spryker\Glue\ShipmentsRestApiExtension\Dependency\Plugin\AddressSourceCheckerPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ShipmentsRestApi
 * @group Processor
 * @group AddressSourceCheckoutRequestAttributesValidatorPluginTest
 * Add your own group annotations below this line
 */
class AddressSourceCheckoutRequestAttributesValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\ShipmentsRestApi\ShipmentsRestApiProcessorTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddressSourceCheckoutRequestAttributesValidatorPluginTestWillCallAddressSourceValidatorPlugins(): void
    {
        // Arrange
        $addressSourceProvidePluginMock = $this->getMockBuilder(AddressSourceCheckerPluginInterface::class)->getMock();
        $addressSourceProvidePluginMock->expects($this->once())->method('isAddressSourceProvided');

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\ShipmentsRestApi\ShipmentsRestApiFactory $shipmentsRestApiFactoryMock */
        $shipmentsRestApiFactoryMock = $this->getMockBuilder(ShipmentsRestApiFactory::class)
            ->onlyMethods(['getAddressSourceProviderPlugins'])
            ->getMock();
        $shipmentsRestApiFactoryMock->method('getAddressSourceProviderPlugins')->willReturn([$addressSourceProvidePluginMock]);

        $addressSourceCheckoutRequestAttributesValidatorPlugin = (new AddressSourceCheckoutRequestAttributesValidatorPlugin())
            ->setFactory($shipmentsRestApiFactoryMock);

        $restCheckoutRequestAttributesTransfer = (new RestCheckoutRequestAttributesTransfer())
            ->addShipment(
                (new RestShipmentsTransfer())->setShippingAddress(new RestAddressTransfer())
            );

        // Act
        $addressSourceCheckoutRequestAttributesValidatorPlugin->validateAttributes($restCheckoutRequestAttributesTransfer);
    }
}
