<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Formatter;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Spryker\Glue\GlueRestApiConvention\Formatter\RestApiSchemaParametersFormatter;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Formatter
 * @group RestApiSchemaParametersFormatterTest
 * Add your own group annotations below this line
 */
class RestApiSchemaParametersFormatterTest extends Unit
{
    /**
     * @var string
     */
    protected const COMPONENTS_PARAMETERS_PAGE = '#/components/parameters/Page';

    /**
     * @var string
     */
    protected const COMPONENTS_PARAMETERS_FIELDS = '#/components/parameters/Fields';

    /**
     * @var string
     */
    protected const COMPONENTS_PARAMETERS_FILTER = '#/components/parameters/Filter';

    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSetOperationParametersWithEmptyOperation(): void
    {
        //Arrange
        $restApiSchemaParametersFormatter = new RestApiSchemaParametersFormatter();
        $resourceContextTransfer = new ResourceContextTransfer();

        //Act
        $operation = $restApiSchemaParametersFormatter->setOperationParameters([], $resourceContextTransfer);

        //Assert
        $this->assertIsArray($operation['parameters']);
        $this->assertEquals(3, count($operation['parameters']));
        $this->assertEquals(static::COMPONENTS_PARAMETERS_PAGE, $operation['parameters'][0]['$ref']);
        $this->assertEquals(static::COMPONENTS_PARAMETERS_FIELDS, $operation['parameters'][1]['$ref']);
        $this->assertEquals(static::COMPONENTS_PARAMETERS_FILTER, $operation['parameters'][2]['$ref']);
    }

    /**
     * @return void
     */
    public function testSetOperationParameters(): void
    {
        //Arrange
        $resourceContextTransfer = new ResourceContextTransfer();

        $formatter = new RestApiSchemaParametersFormatter();
        $operation = $this->tester->createOperation();

        //Act
        $operation = $formatter->setOperationParameters($operation, $resourceContextTransfer);

        //Assert
        $this->assertIsArray($operation['parameters']);
        $this->assertEquals(5, count($operation['parameters']));
        $this->assertEquals(static::COMPONENTS_PARAMETERS_PAGE, $operation['parameters'][2]['$ref']);
        $this->assertEquals(static::COMPONENTS_PARAMETERS_FIELDS, $operation['parameters'][3]['$ref']);
        $this->assertEquals(static::COMPONENTS_PARAMETERS_FILTER, $operation['parameters'][4]['$ref']);
    }

    /**
     * @return void
     */
    public function testSetOperationParametersAddPage(): void
    {
        //Arrange
        $resourceContextTransfer = new ResourceContextTransfer();
        $operation = $this->createOperationWithRefs(['Fields', 'Filter']);
        $formatter = new RestApiSchemaParametersFormatter();

        //Act
        $operation = $formatter->setOperationParameters($operation, $resourceContextTransfer);

        //Assert
        $this->assertEquals(5, count($operation['parameters']));
    }

    /**
     * @return void
     */
    public function testSetComponentParameters(): void
    {
        //Arrange
        $restApiSchemaParametersFormatter = new RestApiSchemaParametersFormatter();
        $formattedData = ['components' => ['parameters' => []]];

        //Act
        $formattedData = $restApiSchemaParametersFormatter->setComponentParameters($formattedData);
        $parameters = $formattedData['components']['parameters'];

        //Assert
        $this->assertArrayHasKey('Page', $parameters);
        $this->assertIsArray($parameters['Page']);
        $this->assertArrayHasKey('Fields', $parameters);
        $this->assertIsArray($parameters['Fields']);
        $this->assertArrayHasKey('Filter', $parameters);
        $this->assertIsArray($parameters['Filter']);
    }

    /**
     * @param array<string> $parameterRefs
     *
     * @return array
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
