<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\ResponseBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig;
use Spryker\Glue\GlueRestApiConvention\ResponseBuilder\ResponseContentBuilder;
use Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface;
use SprykerTest\Glue\GlueRestApiConvention\Stub\AttributesTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group ResponseBuilder
 * @group ResponseContentBuilderTest
 * Add your own group annotations below this line
 */
class ResponseContentBuilderTest extends Unit
{
    /**
     * @var string
     */
    protected const DEFAULT_FORMAT = 'application/json';

    /**
     * @return void
     */
    public function testSkipWhenContentIsAlreadySet(): void
    {
        //Arrange
        $glueRequest = new GlueRequestTransfer();
        $glueResponse = new GlueResponseTransfer();
        $glueResponse->setStatus(200);
        $glueResponse->setContent('test');

        //Act
        $responseBuilder = new ResponseContentBuilder([], $this->getRestApiConventionConfigMock());
        $result = $responseBuilder->buildResponse($glueResponse, $glueRequest);

        //Assert
        $this->assertSame(200, $result->getStatus());
        $this->assertSame('test', $glueResponse->getContent());
    }

    /**
     * @return void
     */
    public function testDefaultStatusCodeIsSet(): void
    {
        //Arrange
        $glueRequest = new GlueRequestTransfer();
        $glueResponse = new GlueResponseTransfer();
        $glueResponse->setContent('test');

        //Act
        $responseBuilder = new ResponseContentBuilder([], $this->getRestApiConventionConfigMock());
        $result = $responseBuilder->buildResponse($glueResponse, $glueRequest);

        //Assert
        $this->assertSame(200, $result->getHttpStatus());
        $this->assertSame('test', $glueResponse->getContent());
    }

    /**
     * @return void
     */
    public function testAlreadySetStatusCodeIsNotOverwritten(): void
    {
        //Arrange
        $glueRequest = new GlueRequestTransfer();
        $glueResponse = new GlueResponseTransfer();
        $glueResponse->setStatus(300);
        $glueResponse->setContent('test');

        //Act
        $responseBuilder = new ResponseContentBuilder([], $this->getRestApiConventionConfigMock());
        $result = $responseBuilder->buildResponse($glueResponse, $glueRequest);

        //Assert
        $this->assertSame(300, $result->getStatus());
        $this->assertSame('test', $glueResponse->getContent());
    }

    /**
     * @return void
     */
    public function testResponseExpander(): void
    {
        //Arrange
        $glueRequest = (new GlueRequestTransfer())->setAcceptedFormat('application/json');
        $glueRequest->setRequestedFormat('application/json');
        $glueResponse = new GlueResponseTransfer();
        $glueResponse->addResource(
            (new GlueResourceTransfer())->setAttributes(
                (new AttributesTransfer())->setAttribute1('Foo')->setAttribute2('Bar'),
            ),
        );

        //Act
        $responseBuilder = new ResponseContentBuilder([$this->createJsonEncoderMock()], $this->getRestApiConventionConfigMock());
        $result = $responseBuilder->buildResponse($glueResponse, $glueRequest);

        //Assert
        $this->assertSame(200, $result->getHttpStatus());
        $this->assertSame('[{"attribute1":"Foo","attribute2":"Bar"}]', $glueResponse->getContent());
    }

    /**
     * @return void
     */
    public function testEmptyResponse(): void
    {
        //Arrange
        $glueRequest = (new GlueRequestTransfer())->setAcceptedFormat('application/json');
        $glueRequest->setRequestedFormat('application/json');
        $glueResponse = new GlueResponseTransfer();

        //Act
        $responseBuilder = new ResponseContentBuilder([$this->createJsonEncoderMock()], $this->getRestApiConventionConfigMock());
        $result = $responseBuilder->buildResponse($glueResponse, $glueRequest);

        //Assert
        $this->assertSame(200, $result->getHttpStatus());
        $this->assertSame('[]', $glueResponse->getContent());
    }

    /**
     * @return void
     */
    public function testNoAcceptingEncoder(): void
    {
        //Arrange
        $glueRequest = (new GlueRequestTransfer())->setAcceptedFormat('application/json');
        $glueRequest->setRequestedFormat('application/json');
        $glueResponse = new GlueResponseTransfer();
        $encoderMock = $this->createMock(ResponseEncoderPluginInterface::class);
        $encoderMock->expects($this->once())
            ->method('getAcceptedFormats')
            ->willReturn(['application/json']);
        $encoderMock->expects($this->once())
            ->method('accepts')
            ->willReturn(false);

        //Act
        $responseBuilder = new ResponseContentBuilder([$encoderMock], $this->getRestApiConventionConfigMock());
        $result = $responseBuilder->buildResponse($glueResponse, $glueRequest);

        //Assert
        $this->assertSame(200, $result->getHttpStatus());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConventionExtension\Dependency\Plugin\ResponseEncoderPluginInterface
     */
    protected function createJsonEncoderMock(): ResponseEncoderPluginInterface
    {
        $jsonEncoderMock = $this->createMock(ResponseEncoderPluginInterface::class);
        $jsonEncoderMock->expects($this->once())
            ->method('getAcceptedFormats')
            ->willReturn(['application/json']);
        $jsonEncoderMock->expects($this->once())
            ->method('accepts')
            ->willReturn(true);
        $jsonEncoderMock->expects($this->once())
            ->method('encode')
            ->willReturnCallback(function ($content, $glueResponseTransfer): GlueResponseTransfer {
                return $glueResponseTransfer->setContent(json_encode($content));
            });

        return $jsonEncoderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionConfig|mixed
     */
    protected function getRestApiConventionConfigMock()
    {
        $configMock = $this->createMock(GlueRestApiConventionConfig::class);
        $configMock->expects($this->any())
            ->method('getDefaultFormat')
            ->willReturn(static::DEFAULT_FORMAT);

        return $configMock;
    }
}
