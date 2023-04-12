<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Resource;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResourceMethodConfigurationTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\Resource\PreFlightResource;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Resource
 * @group PreFlightResourceTest
 * Add your own group annotations below this line
 */
class PreFlightResourceTest extends Unit
{
    /**
     * @var int
     */
    protected const RESPONSE_CODE = 204;

    /**
     * @var string
     */
    protected const METHOD_GET = 'GET';

    /**
     * @var string
     */
    protected const METHOD_OPTIONS = 'OPTIONS';

    /**
     * @var string
     */
    protected const METHOD_POST = 'POST';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_ALLOW_METHODS = 'Access-Control-Allow-Methods';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_ALLOW_HEADERS = 'Access-Control-Allow-Headers';

    /**
     * @return void
     */
    public function testPreFlightResourceGetResponse(): void
    {
        //Arrange
        $preFlightResource = new PreFlightResource();

        //Act
        $result = call_user_func($preFlightResource->getResource(new GlueRequestTransfer()));

        //Assert
        $this->assertInstanceOf(GlueResponseTransfer::class, $result);
        $this->assertSame(static::RESPONSE_CODE, $result->getHttpStatus());
        $meta = $result->getMeta();
        $this->assertIsArray($meta);
        $this->assertArrayHasKey(static::HEADER_ACCESS_CONTROL_ALLOW_METHODS, $meta);
        $this->assertArrayHasKey(static::HEADER_ACCESS_CONTROL_ALLOW_HEADERS, $meta);
        $accessControlAllowMethods = explode(',', $meta[static::HEADER_ACCESS_CONTROL_ALLOW_METHODS]);
        $accessControlAllowMethods = array_map('trim', $accessControlAllowMethods);
        $accessControlAllowMethods = array_flip($accessControlAllowMethods);
        $this->assertArrayHasKey(static::METHOD_GET, $accessControlAllowMethods);
        $this->assertArrayHasKey(static::METHOD_POST, $accessControlAllowMethods);
        $this->assertArrayHasKey(static::METHOD_OPTIONS, $accessControlAllowMethods);
    }

    /**
     * @return void
     */
    public function testPreFlightResourceGetResourceResponse(): void
    {
        //Arrange
        $resourceMock = $this->createMock(ResourceInterface::class);
        $resourceMock->expects($this->once())
            ->method('getDeclaredMethods')
            ->willReturn(
                ((new GlueResourceMethodCollectionTransfer())
                    ->setGet((new GlueResourceMethodConfigurationTransfer())->setAction('getAction'))
                    ->setOptions((new GlueResourceMethodConfigurationTransfer())->setAction('optionAction'))
                ),
            );

        $preFlightResource = new PreFlightResource($resourceMock);

        //Act
        $result = call_user_func($preFlightResource->getResource(new GlueRequestTransfer()));

        //Assert
        $this->assertInstanceOf(GlueResponseTransfer::class, $result);
        $this->assertSame(static::RESPONSE_CODE, $result->getHttpStatus());
        $meta = $result->getMeta();
        $this->assertIsArray($result->getMeta());
        $this->assertArrayHasKey(static::HEADER_ACCESS_CONTROL_ALLOW_METHODS, $meta);
        $this->assertArrayHasKey(static::HEADER_ACCESS_CONTROL_ALLOW_HEADERS, $meta);
        $accessControlAllowMethods = explode(',', $meta[static::HEADER_ACCESS_CONTROL_ALLOW_METHODS]);
        $accessControlAllowMethods = array_map('trim', $accessControlAllowMethods);
        $accessControlAllowMethods = array_flip($accessControlAllowMethods);
        $this->assertArrayNotHasKey(static::METHOD_POST, $accessControlAllowMethods);
        $this->assertArrayHasKey(static::METHOD_GET, $accessControlAllowMethods);
        $this->assertArrayHasKey(static::METHOD_OPTIONS, $accessControlAllowMethods);
    }
}
