<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Codeception\Test\Unit;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiTestFactory;
use SprykerTest\Zed\DocumentationGeneratorRestApi\Business\Fixtures\GlueAnnotationAnalyzerExpectedResult;

/**
 * Auto-generated group annotations
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
        $expectedResult = GlueAnnotationAnalyzerExpectedResult::getTestCreateRestApiDocumentationFromPluginsExpectedResult();

        $this->assertNotEmpty($generatedDocumentationData);
        $this->assertArraySubset($expectedResult['paths'], $generatedDocumentationData['paths']);
        $this->assertArraySubset($expectedResult['schemas'], $generatedDocumentationData['schemas']);
        $this->assertArraySubset($expectedResult['securitySchemes'], $generatedDocumentationData['securitySchemes']);
    }
}
