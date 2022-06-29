<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueRestApiConvention\Plugin\GlueRestApiConvention;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Spryker\Glue\GlueRestApiConvention\Plugin\DocumentationGeneratorApi\RestApiSchemaFormatterPlugin;
use SprykerTest\Glue\GlueRestApiConvention\Stub\TestEmptyRestResourcePlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueRestApiConvention
 * @group Plugin
 * @group GlueRestApiConvention
 * @group RestApiSchemaFormatterPluginTest
 * Add your own group annotations below this line
 */
class RestApiSchemaFormatterPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const COMPONENTS_SCHEMAS_ATTRIBUTES = '#/components/schemas/TestsRestAttributes';

    /**
     * @var string
     */
    protected const COMPONENTS_SCHEMAS_REQUEST_ATTRIBUTES = '#/components/schemas/TestsRestRequestAttributes';

    /**
     * @var string
     */
    protected const COMPONENTS_SCHEMAS_REQUEST = '#/components/schemas/TestsRestApiConventionRequest';

    /**
     * @var string
     */
    protected const COMPONENTS_SCHEMAS_RESPONSE = '#/components/schemas/TestsRestApiConventionResponse';

    /**
     * @var string
     */
    protected const COMPONENTS_SCHEMAS_ERROR_MESSAGE = '#/components/schemas/RestErrorMessage';

    /**
     * @var \SprykerTest\Glue\GlueRestApiConvention\GlueRestApiConventionTester
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
            ->setResourcePluginName(TestEmptyRestResourcePlugin::class)
            ->setResourceType('tests');
        $apiApplicationSchemaContextTransfer->addResourceContext($resourceContextTransfer);

        $plugin = new RestApiSchemaFormatterPlugin();
        $formattedData = $this->tester->createFormattedData();

        //Act
        $formattedData = $plugin->format($formattedData, $apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertGetById($formattedData);
        $this->assertUpdateById($formattedData);
        $this->assertDeleteById($formattedData);
        $this->assertCreate($formattedData);
    }

    /**
     * @param array $formattedData
     *
     * @return void
     */
    protected function assertGetById(array $formattedData): void
    {
        $getTestByIdResponsesData = $formattedData['paths']['/tests/{testId}']['get']['responses'];
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ATTRIBUTES, $getTestByIdResponsesData['200']['content']['application/json']['schema']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ERROR_MESSAGE, $getTestByIdResponsesData['404']['content']['application/json']['schema']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ERROR_MESSAGE, $getTestByIdResponsesData['default']['content']['application/json']['schema']['$ref']);
    }

    /**
     * @param array $formattedData
     *
     * @return void
     */
    protected function assertUpdateById(array $formattedData): void
    {
        $patchTestByIdResponsesData = $formattedData['paths']['/tests/{testId}']['patch']['responses'];
        $patchTestByIdRequestData = $formattedData['paths']['/tests/{testId}']['patch']['requestBody'];
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ATTRIBUTES, $patchTestByIdResponsesData['200']['content']['application/json']['schema']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ERROR_MESSAGE, $patchTestByIdResponsesData['404']['content']['application/json']['schema']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ERROR_MESSAGE, $patchTestByIdResponsesData['default']['content']['application/json']['schema']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_REQUEST_ATTRIBUTES, $patchTestByIdRequestData['content']['application/json']['schema']['$ref']);
    }

    /**
     * @param array $formattedData
     *
     * @return void
     */
    protected function assertDeleteById(array $formattedData): void
    {
        $deleteTestByIdResponsesData = $formattedData['paths']['/tests/{testId}']['delete']['responses'];
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ATTRIBUTES, $deleteTestByIdResponsesData['200']['content']['application/json']['schema']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ERROR_MESSAGE, $deleteTestByIdResponsesData['404']['content']['application/json']['schema']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ERROR_MESSAGE, $deleteTestByIdResponsesData['default']['content']['application/json']['schema']['$ref']);
    }

    /**
     * @param array $formattedData
     *
     * @return void
     */
    protected function assertGetList(array $formattedData): void
    {
        $getTestsResponsesData = $formattedData['paths']['/tests']['get']['responses'];
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ATTRIBUTES, $getTestsResponsesData['200']['content']['application/json']['schema']['items']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ERROR_MESSAGE, $getTestsResponsesData['default']['content']['application/json']['schema']['$ref']);
    }

    /**
     * @param array $formattedData
     *
     * @return void
     */
    protected function assertCreate(array $formattedData): void
    {
        $postTestByIdResponsesData = $formattedData['paths']['/tests']['post']['responses'];
        $postTestByIdRequestData = $formattedData['paths']['/tests']['post']['requestBody'];
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ATTRIBUTES, $postTestByIdResponsesData['200']['content']['application/json']['schema']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ERROR_MESSAGE, $postTestByIdResponsesData['404']['content']['application/json']['schema']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_ERROR_MESSAGE, $postTestByIdResponsesData['default']['content']['application/json']['schema']['$ref']);
        $this->assertEquals(static::COMPONENTS_SCHEMAS_REQUEST_ATTRIBUTES, $postTestByIdRequestData['content']['application/json']['schema']['$ref']);
    }
}
