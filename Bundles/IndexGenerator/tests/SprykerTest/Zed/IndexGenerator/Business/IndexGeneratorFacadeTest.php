<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\IndexGenerator\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group IndexGenerator
 * @group Business
 * @group Facade
 * @group IndexGeneratorFacadeTest
 * Add your own group annotations below this line
 */
class IndexGeneratorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\IndexGenerator\IndexGeneratorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGeneratesSchemaFileWithIndexWhenIndexIsMissing(): void
    {
        $indexGeneratorFacade = $this->tester->getFacadeWithMockedConfig('SchemaWithMissingIndex');
        $indexGeneratorFacade->removeIndexSchemaFiles();
        $indexGeneratorFacade->generateIndexSchemaFiles();

        $this->tester->assertSchemaHasIndex();
    }

    /**
     * @return void
     */
    public function testDoesNotGenerateSchemaFileWhenIndexIsDefined(): void
    {
        $indexGeneratorFacade = $this->tester->getFacadeWithMockedConfig('SchemaWithIndex');
        $indexGeneratorFacade->removeIndexSchemaFiles();
        $indexGeneratorFacade->generateIndexSchemaFiles();

        $this->tester->assertSchemaFileNotExists();
    }

    /**
     * @return void
     */
    public function testDoesNotGenerateSchemaFileWhenTableNotIndexable(): void
    {
        $indexGeneratorFacade = $this->tester->getFacadeWithMockedConfig('SchemaWithArchivableBehavior');
        $indexGeneratorFacade->removeIndexSchemaFiles();
        $indexGeneratorFacade->generateIndexSchemaFiles();

        $this->tester->assertSchemaFileNotExists();
    }

    /**
     * @return void
     */
    public function testDoesNotGenerateSchemaFileWhenTableIsExcluded(): void
    {
        $indexGeneratorFacade = $this->tester->getFacadeWithMockedConfig('SchemaWithMissingIndex', ['spy_foo_bar']);
        $indexGeneratorFacade->removeIndexSchemaFiles();
        $indexGeneratorFacade->generateIndexSchemaFiles();

        $this->tester->assertSchemaFileNotExists();
    }
}
