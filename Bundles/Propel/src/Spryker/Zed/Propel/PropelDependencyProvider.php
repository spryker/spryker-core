<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Propel\Communication\Console\BuildModelConsole;
use Spryker\Zed\Propel\Communication\Console\BuildSqlConsole;
use Spryker\Zed\Propel\Communication\Console\ConvertConfigConsole;
use Spryker\Zed\Propel\Communication\Console\CreateDatabaseConsole;
use Spryker\Zed\Propel\Communication\Console\DiffConsole;
use Spryker\Zed\Propel\Communication\Console\InsertSqlConsole;
use Spryker\Zed\Propel\Communication\Console\MigrateConsole;
use Spryker\Zed\Propel\Communication\Console\PostgresqlCompatibilityConsole;
use Spryker\Zed\Propel\Communication\Console\PropelInstallConsole;
use Spryker\Zed\Propel\Communication\Console\SchemaCopyConsole;
use Symfony\Component\Console\Command\Command;

class PropelDependencyProvider extends AbstractBundleDependencyProvider
{

    const COMMANDS = 'commands';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::COMMANDS] = function ($container) {
            return $this->getConsoleCommands($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Command[]
     */
    protected function getConsoleCommands(Container $container)
    {
        return [
            new PropelInstallConsole(),
            new PostgresqlCompatibilityConsole(),
            new BuildModelConsole(),
            new BuildSqlConsole(),
            new ConvertConfigConsole(),
            new CreateDatabaseConsole(),
            new DiffConsole(),
            new InsertSqlConsole(),
            new MigrateConsole(),
            new SchemaCopyConsole(),
        ];
    }

}
