<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business;

use Codeception\Test\Unit;
use Spryker\Zed\RestApiDocumentationGenerator\Business\RestApiDocumentationGeneratorFacade;
use Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group RestApiDocumentationGenerator
 * @group Business
 * @group Facade
 * @group RestApiDocumentationGeneratorFacadeTest
 * Add your own group annotations below this line
 */
class RestApiDocumentationGeneratorFacadeTest extends Unit
{
    protected const GENERATED_FILE_NAME_PATTERN = '*.schema.yml';

    /**
     * @var \SprykerTest\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorFacadeTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\RestApiDocumentationGeneratorFacadeInterface
     */
    protected $swaggerGeneratorFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->swaggerGeneratorFacade = new RestApiDocumentationGeneratorFacade();
    }

    /**
     * @return void
     */
    public function testGenerateShouldCreateYamlFile(): void
    {
        $this->swaggerGeneratorFacade->generateOpenApiSpecification();

        $finder = new Finder();
        $finder->in($this->getConfig()->getTargetDirectory())->name(static::GENERATED_FILE_NAME_PATTERN);
        $this->assertCount(1, $finder);
    }

    /**
     * @return void
     */
    public function testGenerateShouldCreateValidYamlFileThatCanBeParsedToArray(): void
    {
        $this->swaggerGeneratorFacade->generateOpenApiSpecification();
        $finder = new Finder();
        $finder->in($this->getConfig()->getTargetDirectory())->name(static::GENERATED_FILE_NAME_PATTERN);

        foreach ($finder as $fileInfo) {
            $parsedFile = Yaml::parseFile($fileInfo->getPathname());
            $this->assertInternalType('array', $parsedFile);
        }
    }

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\RestApiDocumentationGeneratorConfig
     */
    protected function getConfig(): RestApiDocumentationGeneratorConfig
    {
        return new RestApiDocumentationGeneratorConfig();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $finder = new Finder();
        $finder->in($this->getConfig()->getTargetDirectory())->name(static::GENERATED_FILE_NAME_PATTERN);
        if ($finder->count() > 0) {
            foreach ($finder as $fileInfo) {
                unlink($fileInfo->getPathname());
            }
        }

        parent::tearDown();
    }
}
