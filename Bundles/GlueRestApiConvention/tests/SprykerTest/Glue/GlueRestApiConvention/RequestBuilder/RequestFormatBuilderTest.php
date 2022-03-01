<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\RequestBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\RequestFormatBuilder;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group RequestBuilder
 * @group RequestFormatBuilderTest
 * Add your own group annotations below this line
 */
class RequestFormatBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const CONTENT_TYPE_KEY = 'content-type';

    /**
     * @var string
     */
    protected const CONTENT_TYPE_AND_ACCEPT_VALUE = 'application/json';

    /**
     * @var string
     */
    protected const ACCEPT_KEY = 'accept';

    /**
     * @return void
     */
    public function testRequestFormatBuilderWithEmptyGlueRequestTransfer(): void
    {
        //Arrange
        $responseEncoderPluginInterfaceMock = $this->getMockBuilder(ResponseEncoderPluginInterface::class)->getMock();

        //Act
        $requestFormatBuilder = new RequestFormatBuilder([$responseEncoderPluginInterfaceMock]);
        $glueRequestTransfer = $requestFormatBuilder->buildRequest(new GlueRequestTransfer());

        //Assert
        $this->assertEmpty($glueRequestTransfer->getRequestedFormat());
        $this->assertEmpty($glueRequestTransfer->getAcceptedFormat());
    }

    /**
     * @return void
     */
    public function testRequestFormatBuilderWithoutResponseEncoderPlugins(): void
    {
        //Arrange
        $responseEncoderPluginInterfaceMock = $this->getMockBuilder(ResponseEncoderPluginInterface::class)->getMock();

        //Act
        $requestFormatBuilder = new RequestFormatBuilder([$responseEncoderPluginInterfaceMock]);
        $glueRequestTransfer = $requestFormatBuilder->buildRequest($this->createGlueRequestTransfer());

        //Assert
        $this->assertEquals(static::CONTENT_TYPE_AND_ACCEPT_VALUE, $glueRequestTransfer->getRequestedFormat());
        $this->assertEmpty($glueRequestTransfer->getAcceptedFormat());
    }

    /**
     * @return void
     */
    public function testRequestFormatBuilder(): void
    {
        //Arrange
        $responseEncoderPluginInterfaceMock = $this->createMock(ResponseEncoderPluginInterface::class);
        $responseEncoderPluginInterfaceMock->expects($this->any())
            ->method('getAcceptedFormats')
            ->willReturn([static::CONTENT_TYPE_AND_ACCEPT_VALUE]);

        //Act
        $requestFormatBuilder = new RequestFormatBuilder([$responseEncoderPluginInterfaceMock]);
        $glueRequestTransfer = $requestFormatBuilder->buildRequest($this->createGlueRequestTransfer());

        //Assert
        $this->assertEquals(static::CONTENT_TYPE_AND_ACCEPT_VALUE, $glueRequestTransfer->getRequestedFormat());
        $this->assertEquals(static::CONTENT_TYPE_AND_ACCEPT_VALUE, $glueRequestTransfer->getAcceptedFormat());
    }

    /**
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function createGlueRequestTransfer(): GlueRequestTransfer
    {
        return (new GlueRequestTransfer())->setMeta([
            static::CONTENT_TYPE_KEY => [static::CONTENT_TYPE_AND_ACCEPT_VALUE],
            static::ACCEPT_KEY => [static::CONTENT_TYPE_AND_ACCEPT_VALUE],
            ]);
    }
}
