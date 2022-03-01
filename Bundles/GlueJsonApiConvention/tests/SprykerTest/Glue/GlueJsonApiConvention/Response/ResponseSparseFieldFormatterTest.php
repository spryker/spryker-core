<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Response;

use Codeception\Test\Unit;
use Spryker\Glue\GlueJsonApiConvention\Response\ResponseSparseFieldFormatter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Response
 * @group ResponseSparseFieldFormatterTest
 *
 * Add your own group annotations below this line
 */
class ResponseSparseFieldFormatterTest extends Unit
{
    /**
     * @return void
     */
    public function testSparseFieldsFormatterForCollection(): void
    {
        //Arrange
        $sparseFields = $this->getTestSparseFieldsData();
        $responseData = [
            'data' => [
                [
                    'type' => 'stores',
                    'id' => 'DE',
                    'attributes' => [
                        'time_zone' => 'Test',
                        'default_currency' => 'EUR',
                        'currencies' => ['USD', 'CHF', 'EUR'],
                    ],
                ],
            ],
            'included' => [
                [
                    'type' => 'regions',
                    'attributes' => [
                        'iso2_code' => 'DE-BE',
                        'name' => 'Berlin',
                    ],
                ],
                [
                    'type' => 'regions',
                    'attributes' => [
                        'iso2_code' => 'US-AL',
                        'name' => 'Alabama',
                    ],
                ],
            ],
        ];

        //Act
        $responseSparseFieldFormatter = new ResponseSparseFieldFormatter();
        $result = $responseSparseFieldFormatter->format($sparseFields, $responseData, null);

        //Assert
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertIsArray($result['data']);
        $this->assertArrayHasKey('attributes', $result['data'][0]);
        $this->assertIsArray($result['data'][0]['attributes']);
        $this->assertArrayHasKey('time_zone', $result['data'][0]['attributes']);
        $this->assertArrayHasKey('currencies', $result['data'][0]['attributes']);
        $this->assertArrayNotHasKey('default_currency', $result['data'][0]['attributes']);

        $this->assertArrayHasKey('included', $result);
        $this->assertIsArray($result['included']);
        $this->assertArrayHasKey('attributes', $result['included'][0]);
        $this->assertIsArray($result['included'][0]['attributes']);
        $this->assertArrayHasKey('name', $result['included'][0]['attributes']);
        $this->assertArrayNotHasKey('iso2_code', $result['included'][0]['attributes']);
        $this->assertArrayHasKey('attributes', $result['included'][1]);
        $this->assertIsArray($result['included'][1]['attributes']);
        $this->assertArrayHasKey('name', $result['included'][1]['attributes']);
        $this->assertArrayNotHasKey('iso2_code', $result['included'][1]['attributes']);
    }

    /**
     * @return void
     */
    public function testSparseFieldsFormatterForSingleResource(): void
    {
        //Arrange
        $sparseFields = $this->getTestSparseFieldsData();
        $responseData = [
            'data' => [
                'type' => 'stores',
                'id' => 'DE',
                'attributes' => [
                    'time_zone' => 'Test',
                    'default_currency' => 'EUR',
                    'currencies' => ['USD', 'CHF', 'EUR'],
                ],
            ],
        ];

        //Act
        $responseSparseFieldFormatter = new ResponseSparseFieldFormatter();
        $result = $responseSparseFieldFormatter->format($sparseFields, $responseData, 'de');

        //Assert
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertIsArray($result['data']);
        $this->assertArrayHasKey('attributes', $result['data']);
        $this->assertIsArray($result['data']['attributes']);
        $this->assertArrayHasKey('time_zone', $result['data']['attributes']);
        $this->assertArrayHasKey('currencies', $result['data']['attributes']);
        $this->assertArrayNotHasKey('default_currency', $result['data']['attributes']);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getTestSparseFieldsData()
    {
        return [
            'stores' => ['time_zone', 'currencies'],
            'regions' => ['name'],
        ];
    }
}
