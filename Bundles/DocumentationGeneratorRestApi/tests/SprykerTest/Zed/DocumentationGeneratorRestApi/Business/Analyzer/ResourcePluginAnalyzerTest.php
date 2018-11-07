<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Codeception\Test\Unit;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Fixtures\GlueAnnotationAnalyzerExpectedResult;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\RestApiDocumentationGeneratorTestFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group RestApiDocumentationGenerator
 * @group Business
 * @group Analyzer
 * @group ResourcePluginAnalyzerTest
 * Add your own group annotations below this line
 */
class ResourcePluginAnalyzerTest extends Unit
{
    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourcePluginAnalyzerInterface
     */
    protected $resourcePluginAnalyzer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->resourcePluginAnalyzer = (new RestApiDocumentationGeneratorTestFactory())->createResourcePluginAnalyzer();
    }

    /**
     * @return void
     */
    public function testCreateRestApiDocumentationFromPlugins(): void
    {
        $generatedDocumentationData = $this->resourcePluginAnalyzer->createRestApiDocumentationFromPlugins();

        $this->assertNotEmpty($generatedDocumentationData);
        $this->assertArraySubset(GlueAnnotationAnalyzerExpectedResult::getTestCreateRestApiDocumentationFromPluginsExpectedResult(), $generatedDocumentationData);
    }
}
