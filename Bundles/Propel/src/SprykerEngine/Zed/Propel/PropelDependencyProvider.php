<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Propel\Communication\Console\BuildModelConsole;
use SprykerEngine\Zed\Propel\Communication\Console\BuildSqlConsole;
use SprykerEngine\Zed\Propel\Communication\Console\ConvertConfigConsole;
use SprykerEngine\Zed\Propel\Communication\Console\CreateDatabaseConsole;
use SprykerEngine\Zed\Propel\Communication\Console\DiffConsole;
use SprykerEngine\Zed\Propel\Communication\Console\InsertSqlConsole;
use SprykerEngine\Zed\Propel\Communication\Console\MigrateConsole;
use SprykerEngine\Zed\Propel\Communication\Console\PostgresqlCompatibilityConsole;
use SprykerEngine\Zed\Propel\Communication\Console\PropelInstallConsole;
use SprykerEngine\Zed\Propel\Communication\Console\SchemaCopyConsole;
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
