<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Decoder;

use Codeception\Test\Unit;
use Spryker\Glue\GlueJsonApiConvention\Decoder\JsonDecoder;
use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceBridge;
use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Decoder
 * @group DecoderTest
 * Add your own group annotations below this line
 */
class DecoderTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testEmptyStringJsonDecoder(): void
    {
        //Act
        $decoder = new JsonDecoder($this->getUtilEncodingService());
        $result = $decoder->decode('');

        //Assert
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @return void
     */
    public function testJsonDecoder(): void
    {
        //Act
        $decoder = new JsonDecoder($this->getUtilEncodingService());
        $result = $decoder->decode($this->acceptedTypesForDecode());

        //Assert
        $this->assertEquals(json_decode($this->acceptedTypesForDecode(), true), $result);
    }

    /**
     * @return \Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): GlueJsonApiConventionToUtilEncodingServiceInterface
    {
        return new GlueJsonApiConventionToUtilEncodingServiceBridge(
            $this->tester->getLocator()->utilEncoding()->service(),
        );
    }

    /**
     * @return string
     */
    protected function acceptedTypesForDecode(): string
    {
        return json_encode([
            'string',
            [100],
            'array_key' => [1.2],
            [['array_key' => 'array_value']],
            [null],
        ]);
    }
}
