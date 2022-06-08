<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\DocumentationGeneratorApi\Console;

use Codeception\Test\Unit;
use Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiDependencyProvider;
use Spryker\Glue\DocumentationGeneratorApi\Plugin\Console\ApiGenerateDocumentationConsole;
use Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi\DocumentationGeneratorOpenApiContentGeneratorStrategyPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group DocumentationGeneratorApi
 * @group Console
 * @group ApiGenerateDocumentationConsoleTest
 * Add your own group annotations below this line
 */
class ApiGenerateDocumentationConsoleTest extends Unit
{
    /**
     * @var \SprykerTest\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testExecuteSuccessful(): void
    {
        //Arrange
        $this->tester->setDependency(
            DocumentationGeneratorApiDependencyProvider::PLUGIN_CONTENT_GENERATOR_STRATEGY,
            new DocumentationGeneratorOpenApiContentGeneratorStrategyPlugin(),
        );
        $command = new ApiGenerateDocumentationConsole();
        $commandTester = $this->tester->getConsoleTester($command);

        $arguments = [
            'command' => $command->getName(),
        ];

        //Act
        $commandTester->execute($arguments);

        //Assert
        $this->assertSame(ApiGenerateDocumentationConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }
}
