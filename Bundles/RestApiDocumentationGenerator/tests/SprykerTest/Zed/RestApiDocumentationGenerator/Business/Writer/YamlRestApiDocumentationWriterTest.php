<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business\Writer;

use Codeception\Test\Unit;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\Fixtures\GlueAnnotationAnalyzerExpectedResult;
use SprykerTest\Zed\RestApiDocumentationGenerator\Business\RestApiDocumentationGeneratorTestFactory;
use Symfony\Component\Finder\Finder;

class YamlRestApiDocumentationWriterTest extends Unit
{
    protected const GENERATED_FILE_NAME_PATTERN = '*.schema.yml';

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface
     */
    protected $yamlWriter;

    /**
     * @var \Pyz\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig
     */
    protected $config;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->yamlWriter = (new RestApiDocumentationGeneratorTestFactory())->createYamlRestApiDocumentationWriter();
        $this->config = (new RestApiDocumentationGeneratorTestFactory())->createConfig();
    }

    /**
     * @return void
     */
    public function testWriteShouldCreateAFile(): void
    {
        $data = GlueAnnotationAnalyzerExpectedResult::getTestCreateRestApiDocumentationFromPluginsExpectedResult();
        $this->yamlWriter->write($data);

        $finder = new Finder();
        $finder->in($this->config->getTargetDirectory())->name(static::GENERATED_FILE_NAME_PATTERN);
        $this->assertCount(1, $finder);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $finder = new Finder();
        $finder->in($this->config->getTargetDirectory())->name(static::GENERATED_FILE_NAME_PATTERN);
        if ($finder->count() > 0) {
            foreach ($finder as $fileInfo) {
                unlink($fileInfo->getPathname());
            }
        }

        parent::tearDown();
    }
}
