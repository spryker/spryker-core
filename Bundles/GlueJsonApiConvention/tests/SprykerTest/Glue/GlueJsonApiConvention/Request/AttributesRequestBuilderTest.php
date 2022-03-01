<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Request;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConvention\Decoder\DecoderInterface;
use Spryker\Glue\GlueJsonApiConvention\Decoder\JsonDecoder;
use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceBridge;
use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface;
use Spryker\Glue\GlueJsonApiConvention\Request\AttributesRequestBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Request
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
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
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
        $this->assertEmpty($glueRequestTransfer->getAttributes());
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
    protected function extractAttributesRequest($glueRequestTransfer): GlueRequestTransfer
    {
        $attributesRequestBuilder = new AttributesRequestBuilder($this->createJsonDecoder());

        return $attributesRequestBuilder->extract($glueRequestTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Decoder\DecoderInterface
     */
    protected function createJsonDecoder(): DecoderInterface
    {
        return new JsonDecoder($this->createUtilEncodingService());
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface
     */
    protected function createUtilEncodingService(): GlueJsonApiConventionToUtilEncodingServiceInterface
    {
        return new GlueJsonApiConventionToUtilEncodingServiceBridge(
            $this->tester->getLocator()->utilEncoding()->service(),
        );
    }

    /**
     * @return string
     */
    protected function getWrongContentData(): string
    {
        return json_encode([
            static::ATTRIBUTES_FIRST_FIELD,
            static::ATTRIBUTES_SECOND_KEY => static::ATTRIBUTES_SECOND_FIELD,
        ]);
    }

    /**
     * @return string
     */
    protected function getContentData(): string
    {
        return json_encode([
            'data' => [
                'attributes' => [
                    static::ATTRIBUTES_FIRST_FIELD,
                    static::ATTRIBUTES_SECOND_KEY => static::ATTRIBUTES_SECOND_FIELD,
                ],
            ],
        ]);
    }
}
