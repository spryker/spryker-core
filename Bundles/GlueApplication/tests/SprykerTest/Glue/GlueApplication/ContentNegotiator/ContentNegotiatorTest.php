<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\ContentNegotiator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\ContentNegotiator\ContentNegotiator;
use Spryker\Glue\GlueApplication\ContentNegotiator\ContentNegotiatorInterface;
use Spryker\Glue\GlueApplication\Encoder\Response\ResponseEncoderStrategyInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ConventionPluginInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group ContentNegotiator
 * @group ContentNegotiatorTest
 * Add your own group annotations below this line
 */
class ContentNegotiatorTest extends Unit
{
    /**
     * @var string
     */
    protected const CONTENT_TYPE_APPLICATION_JSON = 'application/json';

    /**
     * @var string
     */
    protected const METHOD_GET_ACCEPTED_TYPE = 'getAcceptedType';

    /**
     * @return void
     */
    public function testWildCardAcceptTypeIsPassed(): void
    {
        //Arrange
        $contentNegotiator = $this->createContentNegotiator();
        $glueRequestTrasfer = (new GlueRequestTransfer())
            ->setMeta([
                'content-type' => ['application/json'],
                'accept' => ['*/*'],
            ]);

        //Act
        $glueRequestTrasfer = $contentNegotiator->negotiate($glueRequestTrasfer);

        //Assert
        $this->assertSame('application/json', $glueRequestTrasfer->getRequestedFormat());
        $this->assertSame(static::CONTENT_TYPE_APPLICATION_JSON, $glueRequestTrasfer->getAcceptedFormat());
    }

    /**
     * @return void
     */
    public function testNotSupportedWildcardAcceptTypeIsPassed(): void
    {
        //Arrange
        $contentNegotiator = $this->createContentNegotiator();
        $glueRequestTrasfer = (new GlueRequestTransfer())
            ->setMeta([
                'content-type' => ['application/json'],
                'accept' => 'text/*, image/*',
            ]);

        //Act
        $glueRequestTrasfer = $contentNegotiator->negotiate($glueRequestTrasfer);

        //Assert
        $this->assertSame('application/json', $glueRequestTrasfer->getRequestedFormat());
        $this->assertNull($glueRequestTrasfer->getAcceptedFormat());
    }

    /**
     * @return void
     */
    public function testEmptyAcceptTypeIsPassed(): void
    {
        //Arrange
        $contentNegotiator = $this->createContentNegotiator();
        $glueRequestTrasfer = (new GlueRequestTransfer())
            ->setMeta([
                'content-type' => ['application/json'],
            ]);

        //Act
        $glueRequestTrasfer = $contentNegotiator->negotiate($glueRequestTrasfer);

        //Assert
        $this->assertSame('application/json', $glueRequestTrasfer->getRequestedFormat());
        $this->assertNull($glueRequestTrasfer->getAcceptedFormat());
    }

    /**
     * @return void
     */
    public function testHeaderAcceptedByPriority(): void
    {
        //Arrange
        $contentNegotiator = $this->createContentNegotiator();
        $glueRequestTrasfer = (new GlueRequestTransfer())
            ->setMeta([
                'content-type' => ['application/json'],
                'accept' => ['*/*, application/vnd.api+json;q=0.9, application/json'],
            ]);

        //Act
        $glueRequestTrasfer = $contentNegotiator->negotiate($glueRequestTrasfer);

        //Assert
        $this->assertSame('application/json', $glueRequestTrasfer->getRequestedFormat());
        $this->assertSame(static::CONTENT_TYPE_APPLICATION_JSON, $glueRequestTrasfer->getAcceptedFormat());
    }

    /**
     * @return \Spryker\Glue\GlueApplication\ContentNegotiator\ContentNegotiatorInterface
     */
    protected function createContentNegotiator(): ContentNegotiatorInterface
    {
        $apiApplicationConventionMock = $this->createMock(ConventionPluginInterface::class);
        $defaultEncodergMock = $this->createMock(ResponseEncoderStrategyInterface::class);

        $defaultEncodergMock->expects($this->any())
            ->method(static::METHOD_GET_ACCEPTED_TYPE)
            ->willReturn(static::CONTENT_TYPE_APPLICATION_JSON);

        return new ContentNegotiator(
            [$apiApplicationConventionMock],
            [$defaultEncodergMock],
        );
    }
}
