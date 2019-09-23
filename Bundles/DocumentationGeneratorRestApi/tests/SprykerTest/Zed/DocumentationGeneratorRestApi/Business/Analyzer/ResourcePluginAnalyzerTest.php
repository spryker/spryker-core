<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Codeception\Test\Unit;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Analyzer
 * @group ResourcePluginAnalyzerTest
 * Add your own group annotations below this line
 */
class ResourcePluginAnalyzerTest extends Unit
{
    protected const EXPECTED_KEYS = [
        'paths',
        'schemas',
        'securitySchemes',
    ];

    /**
     * @var \SprykerTest\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface
     */
    protected $resourcePluginAnalyzer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->resourcePluginAnalyzer = (new DocumentationGeneratorRestApiTestFactory())->createResourcePluginAnalyzer();
    }

    /**
     * @return void
     */
    public function testCreateRestApiDocumentationFromPlugins(): void
    {
        $generatedDocumentationData = $this->resourcePluginAnalyzer->createRestApiDocumentationFromPlugins();
        $expectedResult = $this->tester->getRestApiDocumentationFromPluginsExpectedResult();

        $this->assertNotEmpty($generatedDocumentationData);
        foreach (static::EXPECTED_KEYS as $key) {
            $this->assertArraySubset($expectedResult[$key], $generatedDocumentationData[$key]);
        }
    }
}
