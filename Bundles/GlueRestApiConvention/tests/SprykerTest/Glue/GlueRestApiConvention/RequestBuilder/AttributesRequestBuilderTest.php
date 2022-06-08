<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\RequestBuilder;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueRestApiConvention\Dependency\Service\GlueRestApiConventionToUtilEncodingServiceBridge;
use Spryker\Glue\GlueRestApiConvention\Dependency\Service\GlueRestApiConventionToUtilEncodingServiceInterface;
use Spryker\Glue\GlueRestApiConvention\RequestBuilder\AttributesRequestBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group RequestBuilder
 * @group AttributesRequestBuilderTest
 * Add your own group annotations below this line
 */
class AttributesRequestBuilderTest extends Unit
{
    /**
     * @var int
     */
    protected const ATTRIBUTES_FIRST_FIELD = 100;

    /**
     * @var string
     */
    protected const ATTRIBUTES_SECOND_FIELD = 'array_key';

    /**
     * @var string
     */
    protected const ATTRIBUTES_SECOND_KEY = 'array_value';

    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConvention
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAttributesRequestBuilderWithoutContent(): void
    {
        //Arrange
        $glueRequestTransfer = new GlueRequestTransfer();

        //Act
        $glueRequestTransfer = $this->extractAttributesRequest($glueRequestTransfer);

        //Assert
        $this->assertEmpty($glueRequestTransfer->getAttributes());
    }

    /**
     * @return void
     */
    public function testAttributesRequestBuilderWithWrongContentData(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setContent($this->getWrongContentData());

        //Act
        $glueRequestTransfer = $this->extractAttributesRequest($glueRequestTransfer);

        //Assert
        $this->assertArrayNotHasKey(static::ATTRIBUTES_FIRST_FIELD, $glueRequestTransfer->getAttributes());
        $this->assertArrayNotHasKey(static::ATTRIBUTES_SECOND_KEY, $glueRequestTransfer->getAttributes());
    }

    /**
     * @return void
     */
    public function testAttributesRequestBuilderWithContentData(): void
    {
        //Arrange
        $glueRequestTransfer = (new GlueRequestTransfer())->setContent($this->getContentData());

        //Act
        $glueRequestTransfer = $this->extractAttributesRequest($glueRequestTransfer);

        //Assert
        $attributes = $glueRequestTransfer->getAttributes();
        $this->assertNotEmpty($attributes);
        $this->assertSame(static::ATTRIBUTES_FIRST_FIELD, $attributes[0]);
        $this->assertArrayHasKey(static::ATTRIBUTES_SECOND_KEY, $attributes);
        $this->assertSame(static::ATTRIBUTES_SECOND_FIELD, $attributes[static::ATTRIBUTES_SECOND_KEY]);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function extractAttributesRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $attributesRequestBuilder = new AttributesRequestBuilder($this->createUtilEncodingService());

        return $attributesRequestBuilder->buildRequest($glueRequestTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueRestApiConvention\Dependency\Service\GlueRestApiConventionToUtilEncodingServiceInterface
     */
    protected function createUtilEncodingService(): GlueRestApiConventionToUtilEncodingServiceInterface
    {
        return new GlueRestApiConventionToUtilEncodingServiceBridge(
            $this->tester->getLocator()->utilEncoding()->service(),
        );
    }

    /**
     * @return string
     */
    protected function getWrongContentData(): string
    {
        return json_encode([
            'data' => [
                static::ATTRIBUTES_FIRST_FIELD,
                static::ATTRIBUTES_SECOND_KEY => static::ATTRIBUTES_SECOND_FIELD,
            ],
        ]);
    }

    /**
     * @return string
     */
    protected function getContentData(): string
    {
        return json_encode([
            static::ATTRIBUTES_FIRST_FIELD,
            static::ATTRIBUTES_SECOND_KEY => static::ATTRIBUTES_SECOND_FIELD,
        ]);
    }
}
