<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DocumentationGeneratorRestApi\Business;

use Codeception\Test\Unit;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiFacade;
use Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DocumentationGeneratorRestApi
 * @group Business
 * @group Facade
 * @group DocumentationGeneratorRestApiFacadeTest
 * Add your own group annotations below this line
 */
class DocumentationGeneratorRestApiFacadeTest extends Unit
{
    protected const GENERATED_FILE_NAME_PATTERN = '*.schema.yml';

    /**
     * @var \SprykerTest\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiFacadeTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\DocumentationGeneratorRestApiFacadeInterface
     */
    protected $swaggerGeneratorFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->swaggerGeneratorFacade = new DocumentationGeneratorRestApiFacade();
    }

    /**
     * @return void
     */
    public function testGenerateShouldCreateYamlFile(): void
    {
        $this->swaggerGeneratorFacade->generateOpenApiSpecification();

        $finder = new Finder();
        $finder->in($this->getConfig()->getGeneratedFileTargetDirectory())->name(static::GENERATED_FILE_NAME_PATTERN);
        $this->assertCount(1, $finder);
    }

    /**
     * @return void
     */
    public function testGenerateShouldCreateValidYamlFileThatCanBeParsedToArray(): void
    {
        $this->swaggerGeneratorFacade->generateOpenApiSpecification();
        $finder = new Finder();
        $finder->in($this->getConfig()->getGeneratedFileTargetDirectory())->name(static::GENERATED_FILE_NAME_PATTERN);

        foreach ($finder as $fileInfo) {
            $parsedFile = Yaml::parseFile($fileInfo->getPathname());
            $this->assertInternalType('array', $parsedFile);
        }
    }

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConfig
     */
    protected function getConfig(): DocumentationGeneratorRestApiConfig
    {
        return new DocumentationGeneratorRestApiConfig();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $finder = new Finder();
        $finder->in($this->getConfig()->getGeneratedFileTargetDirectory())->name(static::GENERATED_FILE_NAME_PATTERN);
        if ($finder->count() > 0) {
            foreach ($finder as $fileInfo) {
                unlink($fileInfo->getPathname());
            }
        }

        parent::tearDown();
    }
}
