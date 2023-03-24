<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Formatter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Spryker\Glue\GlueJsonApiConvention\Formatter\JsonApiSchemaParametersFormatter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Formatter
 * @group JsonApiSchemaParametersFormatterTest
 * Add your own group annotations below this line
 */
class JsonApiSchemaParametersFormatterTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSetOperationParametersWithEmptyOperation(): void
    {
        //Arrange
        $formatter = new JsonApiSchemaParametersFormatter();

        //Act
        $operation = $formatter->setOperationParameters([], new ResourceContextTransfer());

        //Assert
        $this->assertIsArray($operation['parameters']);
        $this->assertEquals(5, count($operation['parameters']));
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_CONTENT_TYPE, $operation['parameters'][0]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_PAGE, $operation['parameters'][1]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FIELDS, $operation['parameters'][2]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FILTER, $operation['parameters'][3]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_SORT, $operation['parameters'][4]['$ref']);
    }

    /**
     * @return void
     */
    public function testSetOperationParameters()
    {
        //Arrange
        $resourceContextTransfer = new ResourceContextTransfer();

        $formatter = new JsonApiSchemaParametersFormatter();
        $operation = $this->tester->createOperation();

        //Act
        $operation = $formatter->setOperationParameters($operation, $resourceContextTransfer);

        //Assert
        $this->assertIsArray($operation['parameters']);
        $this->assertEquals(7, count($operation['parameters']));
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_CONTENT_TYPE, $operation['parameters'][2]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_PAGE, $operation['parameters'][3]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FIELDS, $operation['parameters'][4]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FILTER, $operation['parameters'][5]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_SORT, $operation['parameters'][6]['$ref']);
    }

    /**
     * @return void
     */
    public function testSetOperationParametersAddPage()
    {
        //Arrange
        $resourceContextTransfer = new ResourceContextTransfer();
        $operation = $this->createOperationWithRefs(['ContentType', 'Fields', 'Filter', 'Sort']);
        $formatter = new JsonApiSchemaParametersFormatter();

        //Act
        $operation = $formatter->setOperationParameters($operation, $resourceContextTransfer);

        //Assert
        $this->assertEquals(7, count($operation['parameters']));
    }

    /**
     * @return void
     */
    public function testSetComponentParameters(): void
    {
        //Arrange
        $restApiSchemaParametersFormatter = new JsonApiSchemaParametersFormatter();
        $formattedData = ['components' => ['parameters' => []]];

        //Act
        $formattedData = $restApiSchemaParametersFormatter->setComponentParameters($formattedData);
        $parameters = $formattedData['components']['parameters'];

        //Assert
        $this->assertArrayHasKey('ContentType', $parameters);
        $this->assertIsArray($parameters['ContentType']);
        $this->assertArrayHasKey('Page', $parameters);
        $this->assertIsArray($parameters['Page']);
        $this->assertArrayHasKey('Fields', $parameters);
        $this->assertIsArray($parameters['Fields']);
        $this->assertArrayHasKey('Filter', $parameters);
        $this->assertIsArray($parameters['Filter']);
        $this->assertArrayHasKey('Sort', $parameters);
        $this->assertIsArray($parameters['Sort']);
    }

    /**
     * @param array<string> $parameterRefs
     *
     * @return array<mixed>
     */
    protected function createOperationWithRefs(array $parameterRefs): array
    {
        $operation = $this->tester->createOperation();
        $parameters = [];

        foreach ($parameterRefs as $parameterRef) {
            $parameters[] = [
                '$ref' => '#/components/parameters/' . $parameterRef,
            ];
        }

        $operation['parameters'] = $operation['parameters'] + $parameters;

        return $operation;
    }
}
