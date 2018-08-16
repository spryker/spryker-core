<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestApiDocumentationGenerator\Business;

use Codeception\Test\Unit;
use Spryker\Zed\RestApiDocumentationGenerator\Business\RestApiDocumentationGeneratorFacade;
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
    protected const GENERATED_FILE_NAME = 'spryker_rest_api.schema.yml';

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
        $this->swaggerGeneratorFacade->generate();

        $this->assertFileExists(static::GENERATED_FILE_NAME);
        $this->assertFileIsWritable(static::GENERATED_FILE_NAME);
    }

    /**
     * @return void
     */
    public function testGenerateShouldCreateValidYamlFileThatCanBeParsedToArray(): void
    {
        $this->swaggerGeneratorFacade->generate();
        $parsedFile = Yaml::parseFile(static::GENERATED_FILE_NAME);

        $this->assertInternalType('array', $parsedFile);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        if (file_exists(static::GENERATED_FILE_NAME)) {
            unlink(static::GENERATED_FILE_NAME);
        }
    }
}
