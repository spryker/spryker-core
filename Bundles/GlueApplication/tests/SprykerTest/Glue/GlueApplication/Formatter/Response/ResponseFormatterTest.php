<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\ResponseBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface;
use Spryker\Glue\GlueApplication\Formatter\Response\ResponseFormatter;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use SprykerTest\Glue\GlueApplication\Stub\AttributesTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group ResponseBuilder
 * @group ResponseFormatterTest
 * Add your own group annotations below this line
 */
class ResponseFormatterTest extends Unit
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
        $responseBuilder = new ResponseFormatter([], $this->getGlueApplicationConfigMock());
        $result = $responseBuilder->format($glueResponse, $glueRequest);

        //Assert
        $this->assertSame(200, $result->getHttpStatus());
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
        $responseBuilder = new ResponseFormatter([], $this->getGlueApplicationConfigMock());
        $result = $responseBuilder->format($glueResponse, $glueRequest);

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
        $responseBuilder = new ResponseFormatter([], $this->getGlueApplicationConfigMock());
        $result = $responseBuilder->format($glueResponse, $glueRequest);

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
        $responseBuilder = new ResponseFormatter([$this->createJsonEncoderMock()], $this->getGlueApplicationConfigMock());
        $result = $responseBuilder->format($glueResponse, $glueRequest);

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
        $responseBuilder = new ResponseFormatter([$this->createJsonEncoderMock()], $this->getGlueApplicationConfigMock());
        $result = $responseBuilder->format($glueResponse, $glueRequest);

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
        $encoderMock = $this->createMock(ResponseEncoderStrategyInterface::class);
        $encoderMock->expects($this->once())
            ->method('getAcceptedType')
            ->willReturn('application/test');

        //Act
        $responseBuilder = new ResponseFormatter([$encoderMock], $this->getGlueApplicationConfigMock());
        $result = $responseBuilder->format($glueResponse, $glueRequest);

        //Assert
        $this->assertSame(200, $result->getHttpStatus());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface
     */
    protected function createJsonEncoderMock(): ResponseEncoderStrategyInterface
    {
        $jsonEncoderMock = $this->createMock(ResponseEncoderStrategyInterface::class);
        $jsonEncoderMock->expects($this->once())
            ->method('getAcceptedType')
            ->willReturn('application/json');
        $jsonEncoderMock->expects($this->once())
            ->method('encode')
            ->willReturnCallback(function ($content, $glueResponseTransfer): GlueResponseTransfer {
                return $glueResponseTransfer->setContent(json_encode($content));
            });

        return $jsonEncoderMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\GlueApplicationConfig|mixed
     */
    protected function getGlueApplicationConfigMock()
    {
        $configMock = $this->createMock(GlueApplicationConfig::class);
        $configMock->expects($this->any())
            ->method('getDefaultResponseFormat')
            ->willReturn(static::DEFAULT_FORMAT);

        return $configMock;
    }
}
