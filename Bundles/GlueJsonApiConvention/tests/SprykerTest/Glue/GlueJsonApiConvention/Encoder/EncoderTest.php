<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Encoder;

use Codeception\Test\Unit;
use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceBridge;
use Spryker\Glue\GlueJsonApiConvention\Dependency\Service\GlueJsonApiConventionToUtilEncodingServiceInterface;
use Spryker\Glue\GlueJsonApiConvention\Encoder\JsonEncoder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Encoder
 * @group EncoderTest
 * Add your own group annotations below this line
 */
class EncoderTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testJsonEncodeTestData(): void
    {
        //Act
        $encoder = new JsonEncoder($this->getUtilEncodingService());
        $result = $encoder->encode($this->acceptedTypesForEncode());

        //Assert
        $this->assertIsString($result);
        $this->assertEquals(json_encode($this->acceptedTypesForEncode()), $result);
    }

    /**
     * @return void
     */
    public function testJsonEncodeEmptyData(): void
    {
        //Act
        $encoder = new JsonEncoder($this->getUtilEncodingService());
        $result = $encoder->encode([]);

        //Assert
        $this->assertEmpty($result);
        $this->assertIsString($result);
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
     * @return array
     */
    protected function acceptedTypesForEncode(): array
    {
        return [
            'string',
            [100],
            'array_key' => [1.2],
            [['array_key' => 'array_value']],
            [null],
        ];
    }
}
