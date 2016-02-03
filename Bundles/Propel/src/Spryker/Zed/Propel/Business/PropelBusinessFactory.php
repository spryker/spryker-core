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
use Symfony\Component\Filesystem\Filesystem;
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
 * @method \Spryker\Zed\Propel\PropelConfig getConfig()
 */
class PropelBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelSchemaInterface
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
     * @return \Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface
     */
    protected function createGroupedSchemaFinder()
    {
        $schemaFinder = new PropelGroupedSchemaFinder(
            $this->createSchemaFinder()
        );

        return $schemaFinder;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelSchemaFinderInterface
     */
    protected function createSchemaFinder()
    {
        $schemaFinder = new PropelSchemaFinder(
            $this->getConfig()->getPropelSchemaPathPatterns()
        );

        return $schemaFinder;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelSchemaWriterInterface
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
     * @return \Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface
     */
    protected function createSchemaMerger()
    {
        $propelSchemaMerger = new PropelSchemaMerger();

        return $propelSchemaMerger;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\DirectoryRemoverInterface
     */
    public function createDirectoryRemover()
    {
        return new DirectoryRemover(
            $this->getConfig()->getSchemaDirectory()
        );
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PostgresqlCompatibilityAdjusterInterface
     */
    public function createPostgresqlCompatibilityAdjuster()
    {
        return new PostgresqlCompatibilityAdjuster(
            $this->createSchemaFinder()
        );
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    protected function createFilesystem()
    {
        $filesystem = new Filesystem();

        return $filesystem;
    }

    /**
     * @return \Symfony\Component\Console\Command\Command[]
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
     * @return \Spryker\Zed\Propel\Communication\Console\PropelInstallConsole
     */
    protected function createPropelInstallConsole()
    {
        return new PropelInstallConsole();
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Console\PostgresqlCompatibilityConsole
     */
    protected function createPostgresqlCompatibilityConsole()
    {
        return new PostgresqlCompatibilityConsole();
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Console\BuildModelConsole
     */
    protected function createBuildModelConsole()
    {
        return new BuildModelConsole();
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Console\BuildSqlConsole
     */
    protected function createBuildSqlConsole()
    {
        return new BuildSqlConsole();
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Console\ConvertConfigConsole
     */
    protected function createConvertConfigConsole()
    {
        return new ConvertConfigConsole();
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Console\CreateDatabaseConsole
     */
    protected function createCreateDatabaseConsole()
    {
        return new CreateDatabaseConsole();
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Console\DiffConsole
     */
    protected function createDiffConsole()
    {
        return new DiffConsole();
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Console\InsertSqlConsole
     */
    protected function createInsertSqlConsole()
    {
        return new InsertSqlConsole();
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Console\MigrateConsole
     */
    protected function createMigrateConsole()
    {
        return new MigrateConsole();
    }

    /**
     * @return \Spryker\Zed\Propel\Communication\Console\SchemaCopyConsole
     */
    protected function createSchemaCopyConsole()
    {
        return new SchemaCopyConsole();
    }

}
