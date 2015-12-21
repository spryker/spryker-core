<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business;

use Spryker\Zed\Propel\Business\Model\PostgresqlCompatibilityAdjuster;
use Spryker\Zed\Propel\Business\Model\DirectoryRemover;
use Spryker\Zed\Propel\Business\Model\PropelSchemaMerger;
use Spryker\Zed\Propel\Business\Model\PropelSchemaWriter;
use Spryker\Zed\Propel\Business\Model\PropelSchemaFinder;
use Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinder;
use Spryker\Zed\Propel\Business\Model\PropelSchema;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Propel\Business\Model\DirectoryRemoverInterface;
use Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface;
use Spryker\Zed\Propel\Business\Model\PostgresqlCompatibilityAdjusterInterface;
use Spryker\Zed\Propel\Business\Model\PropelSchemaFinderInterface;
use Spryker\Zed\Propel\Business\Model\PropelSchemaInterface;
use Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface;
use Spryker\Zed\Propel\Business\Model\PropelSchemaWriterInterface;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
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

/**
 * @method PropelConfig getConfig()
 */
class PropelBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return PropelSchemaInterface
     */
    public function createModelSchema()
    {
        return new PropelSchema(
            $this->createGroupedSchemaFinder(),
            $this->createSchemaWriter(),
            $this->createSchemaMerger()
        );
    }

    /**
     * @return PropelGroupedSchemaFinderInterface
     */
    protected function createGroupedSchemaFinder()
    {
        $schemaFinder = new PropelGroupedSchemaFinder(
            $this->createSchemaFinder()
        );

        return $schemaFinder;
    }

    /**
     * @return PropelSchemaFinderInterface
     */
    protected function createSchemaFinder()
    {
        $schemaFinder = new PropelSchemaFinder(
            $this->getConfig()->getPropelSchemaPathPatterns()
        );

        return $schemaFinder;
    }

    /**
     * @return PropelSchemaWriterInterface
     */
    protected function createSchemaWriter()
    {
        $schemaWriter = new PropelSchemaWriter(
            $this->createFilesystem(),
            $this->getConfig()->getSchemaDirectory()
        );

        return $schemaWriter;
    }

    /**
     * @return PropelSchemaMergerInterface
     */
    protected function createSchemaMerger()
    {
        $propelSchemaMerger = new PropelSchemaMerger();

        return $propelSchemaMerger;
    }

    /**
     * @return DirectoryRemoverInterface
     */
    public function createDirectoryRemover()
    {
        return new DirectoryRemover(
            $this->getConfig()->getSchemaDirectory()
        );
    }

    /**
     * @return PostgresqlCompatibilityAdjusterInterface
     */
    public function createPostgresqlCompatibilityAdjuster()
    {
        return new PostgresqlCompatibilityAdjuster(
            $this->createSchemaFinder()
        );
    }

    /**
     * @return Filesystem
     */
    protected function createFilesystem()
    {
        $filesystem = new Filesystem();

        return $filesystem;
    }

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return [
            $this->createPropelInstallConsole(),
            $this->createPostgresqlCompatibilityConsole(),
            $this->createBuildModelConsole(),
            $this->createBuildSqlConsole(),
            $this->createConvertConfigConsole(),
            $this->createCreateDatabaseConsole(),
            $this->createDiffConsole(),
            $this->createInsertSqlConsole(),
            $this->createMigrateConsole(),
            $this->createSchemaCopyConsole(),
        ];
    }

    /**
     * @return PropelInstallConsole
     */
    protected function createPropelInstallConsole()
    {
        return new PropelInstallConsole();
    }

    /**
     * @return PostgresqlCompatibilityConsole
     */
    protected function createPostgresqlCompatibilityConsole()
    {
        return new PostgresqlCompatibilityConsole();
    }

    /**
     * @return BuildModelConsole
     */
    protected function createBuildModelConsole()
    {
        return new BuildModelConsole();
    }

    /**
     * @return BuildSqlConsole
     */
    protected function createBuildSqlConsole()
    {
        return new BuildSqlConsole();
    }

    /**
     * @return ConvertConfigConsole
     */
    protected function createConvertConfigConsole()
    {
        return new ConvertConfigConsole();
    }

    /**
     * @return CreateDatabaseConsole
     */
    protected function createCreateDatabaseConsole()
    {
        return new CreateDatabaseConsole();
    }

    /**
     * @return DiffConsole
     */
    protected function createDiffConsole()
    {
        return new DiffConsole();
    }

    /**
     * @return InsertSqlConsole
     */
    protected function createInsertSqlConsole()
    {
        return new InsertSqlConsole();
    }

    /**
     * @return MigrateConsole
     */
    protected function createMigrateConsole()
    {
        return new MigrateConsole();
    }

    /**
     * @return SchemaCopyConsole
     */
    protected function createSchemaCopyConsole()
    {
        return new SchemaCopyConsole();
    }

}
