<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CartsRestApi\Processor;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilder;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Service\Container\Container;
use Spryker\Shared\Kernel\Container\GlobalContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group CartsRestApi
 * @group Processor
 * @group CartsRestApiResourceTest
 * Add your own group annotations below this line
 */
class CartsRestApiResourceTest extends Unit
{
    /**
     * @uses \Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin::SERVICE_RESOURCE_BUILDER
     */
    protected const SERVICE_RESOURCE_BUILDER = 'resource_builder';

    /**
     * @var \SprykerTest\Glue\CartsRestApi\CartsRestApiTester
     */
    protected $tester;

    /**
     * @var \Spryker\Glue\CartsRestApi\CartsRestApiResourceInterface
     */
    protected $cartsRestApiResource;

    /**
     * @return void
     */
    public function _before(): void
    {
        parent::_before();

        $globalContainer = new GlobalContainer();
        $globalContainer->setContainer(new Container([
            static::SERVICE_RESOURCE_BUILDER => new RestResourceBuilder(),
        ]));
    }

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->cartsRestApiResource = $this->tester->getLocator()->cartsRestApi()->resource();
    }

    /**
     * @dataProvider getCreateCartRestResponseDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testCreateCartRestResponseWillCreateAFullCartsResource(QuoteTransfer $quoteTransfer): void
    {
        // Act
        $actualCartsRestResponse = $this->cartsRestApiResource
            ->createCartRestResponse($quoteTransfer, $this->getMockRestRequest());

        // Assert
        $actualCartsRestResource = current($actualCartsRestResponse->getResources());
        $this->tester->assertCartsResource($actualCartsRestResource, $quoteTransfer);
    }

    /**
     * @dataProvider getCreateCartRestResponseDataProvider
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function testCreateGuestCartRestResponseWillCreateAFullCartsResource(QuoteTransfer $quoteTransfer): void
    {
        // Act
        $actualCartsRestResponse = $this->cartsRestApiResource
            ->createGuestCartRestResponse($quoteTransfer, $this->getMockRestRequest());

        // Assert
        $actualCartsRestResource = current($actualCartsRestResponse->getResources());
        $this->tester->assertGuestCartsResource($actualCartsRestResource, $quoteTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    protected function getMockRestRequest(): RestRequestInterface
    {
        return $this->getMockBuilder(RestRequestInterface::class)->getMock();
    }

    /**
     * @return array
     */
    public function getCreateCartRestResponseDataProvider(): array
    {
        return [
            [(new QuoteBuilder([
                QuoteTransfer::CUSTOMER_REFERENCE => 'DE--1',
                QuoteTransfer::CUSTOMER => (new CustomerTransfer())->setCustomerReference('DE--1'),
            ]))
                ->withCurrency()
                ->withStore()
                ->withTotals()
                ->build()],
            [(new QuoteBuilder([
                QuoteTransfer::CUSTOMER_REFERENCE => 'DE--1',
                QuoteTransfer::CUSTOMER => (new CustomerTransfer())->setCustomerReference('DE--1'),
            ]))
                ->withCurrency()
                ->withStore()
                ->withTotals()
                ->withCartRuleDiscount([DiscountTransfer::AMOUNT => 17])
                ->build()],
            [(new QuoteBuilder([
                QuoteTransfer::CUSTOMER_REFERENCE => 'DE--1',
                QuoteTransfer::CUSTOMER => (new CustomerTransfer())->setCustomerReference('DE--1'),
            ]))
                ->withCurrency()
                ->withStore()
                ->withTotals()
                ->withVoucherDiscount([DiscountTransfer::VOUCHER_CODE => 'voucher code'])
                ->build()],
        ];
    }
}
