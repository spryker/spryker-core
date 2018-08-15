<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SwaggerGenerator\Business;

use Codeception\Test\Unit;
use Spryker\Zed\SwaggerGenerator\Business\SwaggerGeneratorFacade;
use Symfony\Component\Yaml\Yaml;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group SwaggerGenerator
 * @group Business
 * @group Facade
 * @group SwaggerGeneratorFacadeTest
 * Add your own group annotations below this line
 */
class SwaggerGeneratorFacadeTest extends Unit
{
    protected const GENERATED_FILE_NAME = 'spryker_rest_api.schema.yml';

    /**
     * @var \SprykerTest\Zed\SwaggerGenerator\SwaggerGeneratorFacadeTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\SwaggerGenerator\Business\SwaggerGeneratorFacadeInterface
     */
    protected $swaggerGeneratorFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->swaggerGeneratorFacade = new SwaggerGeneratorFacade();
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
