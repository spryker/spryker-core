<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IndexGenerator\Business;

use Spryker\Zed\IndexGenerator\Business\ForeignKeysProvider\ForeignKeysProvider;
use Spryker\Zed\IndexGenerator\Business\ForeignKeysProvider\ForeignKeysProviderInterface;
use Spryker\Zed\IndexGenerator\Business\IndexGenerator\PostgresIndexGenerator;
use Spryker\Zed\IndexGenerator\Business\IndexGenerator\PostgresIndexGeneratorInterface;
use Spryker\Zed\IndexGenerator\Business\IndexRemover\PostgresIndexRemover;
use Spryker\Zed\IndexGenerator\Business\IndexRemover\PostgresIndexRemoverInterface;
use Spryker\Zed\IndexGenerator\Business\SchemaFinder\MergedSchemaFinder;
use Spryker\Zed\IndexGenerator\Business\SchemaFinder\MergedSchemaFinderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\IndexGenerator\IndexGeneratorConfig getConfig()
 */
class IndexGeneratorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\IndexGenerator\Business\IndexGenerator\PostgresIndexGeneratorInterface
     */
    public function createPostgresIndexGenerator(): PostgresIndexGeneratorInterface
    {
        return new PostgresIndexGenerator(
            $this->getConfig(),
            $this->createForeignKeysProvider()
        );
    }

    /**
     * @return \Spryker\Zed\IndexGenerator\Business\IndexRemover\PostgresIndexRemoverInterface
     */
    public function createPostgresIndexRemover(): PostgresIndexRemoverInterface
    {
        return new PostgresIndexRemover(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\IndexGenerator\Business\ForeignKeysProvider\ForeignKeysProviderInterface
     */
    protected function createForeignKeysProvider(): ForeignKeysProviderInterface
    {
        return new ForeignKeysProvider(
            $this->createFinder(),
            $this->getConfig()->getExcludedTables()
        );
    }

    /**
     * @return \Spryker\Zed\IndexGenerator\Business\SchemaFinder\MergedSchemaFinderInterface
     */
    protected function createFinder(): MergedSchemaFinderInterface
    {
        return new MergedSchemaFinder(
            $this->getConfig()
        );
    }
}
