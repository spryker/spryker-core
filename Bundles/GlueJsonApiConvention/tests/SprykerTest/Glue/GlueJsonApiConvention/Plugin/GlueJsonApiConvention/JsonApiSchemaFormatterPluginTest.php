<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueJsonApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Spryker\Glue\GlueJsonApiConvention\Plugin\GlueJsonApiConvention\JsonApiSchemaFormatterPlugin;
use SprykerTest\Glue\GlueJsonApiConvention\Stub\TestEmptyJsonApiResourcePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueJsonApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group JsonApiSchemaFormatterPluginTest
 * Add your own group annotations below this line
 */
class JsonApiSchemaFormatterPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const COMPONENTS_SCHEMAS_REQUEST = '#/components/schemas/TestsRestRequest';

    /**
     * @var string
     */
    protected const COMPONENTS_SCHEMAS_RESPONSE = '#/components/schemas/TestsRestResponse';

    /**
     * @var string
     */
    protected const COMPONENTS_SCHEMAS_COLLECTION_RESPONSE = '#/components/schemas/TestsRestCollectionResponse';

    /**
     * @var string
     */
    protected const COMPONENTS_SCHEMAS_ERROR = '#/components/schemas/JsonApiErrorMessage';

    /**
     * @var string
     */
    protected const APPLICATION_API_JSON = 'application/vnd.api+json';

    /**
     * @var \SprykerTest\Glue\GlueJsonApiConvention\GlueJsonApiConventionTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFormatThenCompareArrayStructure(): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = new ApiApplicationSchemaContextTransfer();
        $resourceContextTransfer = (new ResourceContextTransfer())
            ->setResourcePluginName(TestEmptyJsonApiResourcePlugin::class)
            ->setResourceType('tests');
        $apiApplicationSchemaContextTransfer->addResourceContext($resourceContextTransfer);
        $plugin = new JsonApiSchemaFormatterPlugin();

        //Act
        $formattedData = $plugin->format($this->tester->createSchemaForamtedData(), $apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertPathsGetTestsById($formattedData);
        $this->assertPathsPatchTestsById($formattedData);
        $this->assertPathsDeleteTestsById($formattedData);
        $this->assertPathsGetTests($formattedData);
        $this->assertPathsPostTests($formattedData);
        $this->assertComponentsSchemas($formattedData);
        $this->assertComponentsParameters($formattedData);
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return void
     */
    protected function assertPathsGetTestsById(array $formattedData): void
    {
        $data = $formattedData['paths']['/tests/{testId}']['get'];
        $this->assertEquals(5, count($data['parameters']));
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_PAGE, $data['parameters'][2]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FIELDS, $data['parameters'][3]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FILTER, $data['parameters'][4]['$ref']);

        $responseData = $data['responses'];

        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['200']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_RESPONSE,
            $responseData['200']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['404']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_ERROR,
            $responseData['404']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['default']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_ERROR,
            $responseData['default']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return void
     */
    protected function assertPathsPatchTestsById(array $formattedData): void
    {
        $data = $formattedData['paths']['/tests/{testId}']['patch'];
        $responseData = $data['responses'];
        $requestBody = $data['requestBody'];

        $this->assertEquals(5, count($data['parameters']));
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_PAGE, $data['parameters'][2]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FIELDS, $data['parameters'][3]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FILTER, $data['parameters'][4]['$ref']);

        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['200']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_RESPONSE,
            $responseData['200']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['404']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_ERROR,
            $responseData['404']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['default']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_ERROR,
            $responseData['default']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $requestBody['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_REQUEST,
            $requestBody['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return void
     */
    protected function assertPathsDeleteTestsById(array $formattedData): void
    {
        $data = $formattedData['paths']['/tests/{testId}']['delete'];
        $responseData = $data['responses'];

        $this->assertEquals(5, count($data['parameters']));
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_PAGE, $data['parameters'][2]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FIELDS, $data['parameters'][3]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FILTER, $data['parameters'][4]['$ref']);

        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['200']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_RESPONSE,
            $responseData['200']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['404']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_ERROR,
            $responseData['404']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['default']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_ERROR,
            $responseData['default']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return void
     */
    protected function assertPathsGetTests(array $formattedData): void
    {
        $data = $formattedData['paths']['/tests']['get'];
        $responseData = $data['responses'];

        $this->assertEquals(5, count($data['parameters']));
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_PAGE, $data['parameters'][2]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FIELDS, $data['parameters'][3]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FILTER, $data['parameters'][4]['$ref']);

        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['200']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_COLLECTION_RESPONSE,
            $responseData['200']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );

        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['default']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_ERROR,
            $responseData['default']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return void
     */
    protected function assertPathsPostTests(array $formattedData): void
    {
        $data = $formattedData['paths']['/tests']['post'];
        $responseData = $data['responses'];
        $requestBody = $data['requestBody'];

        $this->assertEquals(5, count($data['parameters']));
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_PAGE, $data['parameters'][2]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FIELDS, $data['parameters'][3]['$ref']);
        $this->assertEquals($this->tester::COMPONENTS_PARAMETERS_FILTER, $data['parameters'][4]['$ref']);

        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['200']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_RESPONSE,
            $responseData['200']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['404']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_ERROR,
            $responseData['404']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $responseData['default']['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_ERROR,
            $responseData['default']['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
        $this->assertArrayHasKey(static::APPLICATION_API_JSON, $requestBody['content']);
        $this->assertEquals(
            static::COMPONENTS_SCHEMAS_REQUEST,
            $requestBody['content'][static::APPLICATION_API_JSON]['schema']['$ref'],
        );
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return void
     */
    protected function assertComponentsSchemas(array $formattedData): void
    {
        $schemasData = $formattedData['components']['schemas'];
        $expectedJsonApiErrorMessageData = [
            'type' => 'object',
            'properties' => [
                'errors' => [
                    'type' => 'object',
                    'properties' => [
                        'status' => [
                            'type' => 'integer',
                        ],
                        'code' => [
                            'type' => 'string',
                        ],
                        'message' => [
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ];
        $this->assertArrayHasKey('JsonApiErrorMessage', $schemasData);
        $this->assertEquals($expectedJsonApiErrorMessageData, $schemasData['JsonApiErrorMessage']);
    }

    /**
     * @param array<mixed> $formattedData
     *
     * @return void
     */
    protected function assertComponentsParameters(array $formattedData): void
    {
        $parametersData = $formattedData['components']['parameters'];

        $this->assertArrayHasKey('Page', $parametersData);
        $this->assertArrayHasKey('Fields', $parametersData);
        $this->assertArrayHasKey('Filter', $parametersData);
    }
}
