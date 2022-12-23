<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DocumentationGeneratorOpenApi\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\DocumentationGeneratorOpenApiSchemaFormatterPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DocumentationGeneratorOpenApi
 * @group Plugin
 * @group DocumentationGeneratorOpenApiSchemaFormatterPluginTest
 * Add your own group annotations below this line
 */
class DocumentationGeneratorOpenApiSchemaFormatterPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION = 'backend';

    /**
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION = 'storefront';

    /**
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION_HOST = 'backend.local';

    /**
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION_HOST = 'storefront.local';

    /**
     * @var \SprykerTest\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiCommunicationTester
     */
    protected $tester;

    /**
     * @dataProvider applicationDataProvider
     *
     * @param string $applicationName
     * @param string $applicationHost
     *
     * @return void
     */
    public function testFormatData(string $applicationName, string $applicationHost): void
    {
        //Arrange
        $apiApplicationSchemaContextTransfer = (new ApiApplicationSchemaContextTransfer())
            ->setApplication($applicationName)
            ->setHost($applicationHost);

        $plugin = new DocumentationGeneratorOpenApiSchemaFormatterPlugin();
        $plugin->setFactory($this->tester->getFactory());

        //Act
        $formattedData = $plugin->format([], $apiApplicationSchemaContextTransfer);

        //Assert
        $this->assertSame($this->createOpenApiExpectedData($applicationHost), $formattedData);
    }

    /**
     * @return array<string, array<string>>
     */
    public function applicationDataProvider(): array
    {
        return [
            'GlueBackendApiApplication' => [
                static::GLUE_BACKEND_API_APPLICATION, static::GLUE_BACKEND_API_APPLICATION_HOST,
            ],
            'GlueStorefrontApiApplication' => [
                static::GLUE_STOREFRONT_API_APPLICATION, static::GLUE_STOREFRONT_API_APPLICATION_HOST,
            ],
        ];
    }

    /**
     * @param string $applicationDomain
     *
     * @return array<mixed>
     */
    protected function createOpenApiExpectedData(string $applicationDomain): array
    {
        return [
            'openapi' => '3.0.0',
            'info' => [
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'Spryker',
                    'url' => 'https://support.spryker.com/',
                    'email' => 'support@spryker.com',
                ],
                'title' => 'Spryker API',
                'license' => [
                    'name' => 'MIT',
                ],
            ],
            'tags' => [
            ],
            'servers' => [
                [
                    'url' => (extension_loaded('openssl') ? 'https://' : 'http://') . $applicationDomain,
                ],
            ],
            'paths' => [
            ],
            'components' => [
                'securitySchemes' => [
                    'BearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                    ],
                ],
                'schemas' => [
                    'Links' => [
                        'properties' => [
                            'self' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                    'Relationships' => [
                        'properties' => [
                            'id' => [
                                'type' => 'string',
                            ],
                            'type' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                    'RelationshipsData' => [
                        'properties' => [
                            'data' => [
                                'type' => 'array',
                                'items' => [
                                    '$ref' => '#/components/schemas/Relationships',
                                ],
                            ],
                        ],
                    ],
                    'RestErrorMessage' => [
                        'properties' => [
                            'code' => [
                                'type' => 'string',
                            ],
                            'detail' => [
                                'type' => 'string',
                            ],
                            'status' => [
                                'type' => 'integer',
                            ],
                        ],
                    ],
                ],
                'parameters' => [
                    'acceptLanguage' => [
                        'name' => 'Accept-Language',
                        'in' => 'header',
                        'description' => 'Locale value relevant for the store.',
                        'schema' => [
                            'type' => 'string',
                        ],
                        'required' => false,
                    ],
                ],
            ],
        ];
    }
}
