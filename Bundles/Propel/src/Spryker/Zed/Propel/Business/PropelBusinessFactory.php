<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Propel\Business\ConfigReader\PropelConfigReader;
use Spryker\Zed\Propel\Business\ConfigReader\PropelConfigReaderInterface;
use Spryker\Zed\Propel\Business\Model\DirectoryRemover;
use Spryker\Zed\Propel\Business\Model\HealthCheck\HealthCheckInterface;
use Spryker\Zed\Propel\Business\Model\HealthCheck\PropelHealthCheck;
use Spryker\Zed\Propel\Business\Model\PostgresqlCompatibilityAdjuster;
use Spryker\Zed\Propel\Business\Model\PropelConfigConverterJson;
use Spryker\Zed\Propel\Business\Model\PropelDatabase;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\AdapterCollection;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\AdapterFactory;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorCollection;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\MySqlDatabaseCreator;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\PostgreSqlDatabaseCreator;
use Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinder;
use Spryker\Zed\Propel\Business\Model\PropelSchema;
use Spryker\Zed\Propel\Business\Model\PropelSchemaFinder;
use Spryker\Zed\Propel\Business\Model\PropelSchemaMerger;
use Spryker\Zed\Propel\Business\Model\PropelSchemaWriter;
use Spryker\Zed\Propel\Business\Model\Schema\Validator\PropelSchemaValidator;
use Spryker\Zed\Propel\Business\Model\Schema\XmlValidator\PropelSchemaXmlNameValidator;
use Spryker\Zed\Propel\Business\SchemaElementFilter\PropelSchemaElementFilter;
use Spryker\Zed\Propel\Business\SchemaElementFilter\SchemaElementFilterInterface;
use Spryker\Zed\Propel\Communication\Console\BuildModelConsole;
use Spryker\Zed\Propel\Communication\Console\BuildSqlConsole;
use Spryker\Zed\Propel\Communication\Console\ConvertConfigConsole;
use Spryker\Zed\Propel\Communication\Console\CreateDatabaseConsole;
use Spryker\Zed\Propel\Communication\Console\DiffConsole;
use Spryker\Zed\Propel\Communication\Console\InsertSqlConsole;
use Spryker\Zed\Propel\Communication\Console\MigrateConsole;
use Spryker\Zed\Propel\Communication\Console\MigrationCheckConsole;
use Spryker\Zed\Propel\Communication\Console\PostgresqlCompatibilityConsole;
use Spryker\Zed\Propel\Communication\Console\PropelInstallConsole;
use Spryker\Zed\Propel\Communication\Console\SchemaCopyConsole;
use Spryker\Zed\Propel\PropelDependencyProvider;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @method \Spryker\Zed\Propel\PropelConfig getConfig()
 */
class PropelBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Propel\Business\ConfigReader\PropelConfigReaderInterface
     */
    public function createPropelConfigReader(): PropelConfigReaderInterface
    {
        return new PropelConfigReader($this->getConfig());
    }

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
    public function createGroupedSchemaFinder()
    {
        $schemaFinder = new PropelGroupedSchemaFinder(
            $this->createSchemaFinder()
        );

        return $schemaFinder;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelSchemaFinderInterface
     */
    public function createSchemaFinder()
    {
        $schemaFinder = new PropelSchemaFinder(
            $this->getConfig()->getPropelSchemaPathPatterns()
        );

        return $schemaFinder;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelSchemaFinderInterface
     */
    public function createCoreSchemaFinder()
    {
        $schemaFinder = new PropelSchemaFinder(
            $this->getConfig()->getCorePropelSchemaPathPatterns()
        );

        return $schemaFinder;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelSchemaWriterInterface
     */
    public function createSchemaWriter()
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
    public function createSchemaMerger()
    {
        $propelSchemaMerger = new PropelSchemaMerger(
            $this->getUtilTextService(),
            $this->createPropelSchemaElementFilter(),
            $this->getConfig()
        );

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
     * @return \Spryker\Zed\Propel\Business\Model\DirectoryRemoverInterface
     */
    public function createMigrationDirectoryRemover()
    {
        return new DirectoryRemover(
            $this->getConfig()->getMigrationDirectory()
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
     * @return \Spryker\Zed\Propel\Business\Model\PostgresqlCompatibilityAdjusterInterface
     */
    public function createCorePostgresqlCompatibilityAdjuster()
    {
        return new PostgresqlCompatibilityAdjuster(
            $this->createCoreSchemaFinder()
        );
    }

    /**
     * @return \Symfony\Component\Filesystem\Filesystem
     */
    public function createFilesystem()
    {
        $filesystem = new Filesystem();

        return $filesystem;
    }

    /**
     * @deprecated Use {@link createPropelDatabaseAdapterCollection()} instead.
     *
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabaseInterface
     */
    public function createDatabaseCreator()
    {
        return new PropelDatabase(
            $this->createDatabaseCreatorCollection()
        );
    }

    /**
     * @deprecated Use {@link createPropelDatabaseAdapterCollection()} instead.
     *
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorCollectionInterface
     */
    public function createDatabaseCreatorCollection()
    {
        $databaseCreatorCollection = new DatabaseCreatorCollection();
        $databaseCreatorCollection
            ->add($this->createMySqlDatabaseCreator())
            ->add($this->createPostgreSqlDatabaseCreator());

        return $databaseCreatorCollection;
    }

    /**
     * @deprecated Use {@link createPropelDatabaseAdapterCollection()} instead.
     *
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorInterface
     */
    public function createMySqlDatabaseCreator()
    {
        return new MySqlDatabaseCreator();
    }

    /**
     * @deprecated Use {@link createPropelDatabaseAdapterCollection()} instead.
     *
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\DatabaseCreatorInterface
     */
    public function createPostgreSqlDatabaseCreator()
    {
        return new PostgreSqlDatabaseCreator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelConfigConverterJson
     */
    public function createConfigConverter()
    {
        return new PropelConfigConverterJson($this->getConfig()->getPropelConfig());
    }

    /**
     * @deprecated Please add the needed Commands into your ConsoleDependencyProvider
     *
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
            $this->createMigrationCheckConsole(),
        ];
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\PropelInstallConsole
     */
    public function createPropelInstallConsole()
    {
        return new PropelInstallConsole();
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\PostgresqlCompatibilityConsole
     */
    public function createPostgresqlCompatibilityConsole()
    {
        return new PostgresqlCompatibilityConsole();
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\BuildModelConsole
     */
    public function createBuildModelConsole()
    {
        return new BuildModelConsole();
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\BuildSqlConsole
     */
    public function createBuildSqlConsole()
    {
        return new BuildSqlConsole();
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\ConvertConfigConsole
     */
    public function createConvertConfigConsole()
    {
        return new ConvertConfigConsole();
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\CreateDatabaseConsole
     */
    public function createCreateDatabaseConsole()
    {
        return new CreateDatabaseConsole();
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\DiffConsole
     */
    public function createDiffConsole()
    {
        return new DiffConsole();
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\InsertSqlConsole
     */
    public function createInsertSqlConsole()
    {
        return new InsertSqlConsole();
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\MigrateConsole
     */
    public function createMigrateConsole()
    {
        return new MigrateConsole();
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\SchemaCopyConsole
     */
    public function createSchemaCopyConsole()
    {
        return new SchemaCopyConsole();
    }

    /**
     * @deprecated Please add the Command directly to your ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\Propel\Communication\Console\MigrationCheckConsole
     */
    public function createMigrationCheckConsole()
    {
        return new MigrationCheckConsole();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\AdapterCollectionInterface
     */
    public function createPropelDatabaseAdapterCollection()
    {
        $adapterCollection = new AdapterCollection($this->getConfig()->getCurrentDatabaseEngine());

        $adapterFactory = $this->createAdapterFactory();
        $adapterCollection->addAdapter($adapterFactory->createMySqlAdapter());
        $adapterCollection->addAdapter($adapterFactory->createPostgreSqlAdapter());

        return $adapterCollection;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\AdapterFactoryInterface
     */
    public function createAdapterFactory()
    {
        return new AdapterFactory($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\Schema\Validator\PropelSchemaValidatorInterface
     */
    public function createSchemaValidator()
    {
        $propelSchemaValidator = new PropelSchemaValidator(
            $this->createGroupedSchemaFinder(),
            $this->getUtilTextService(),
            $this->getConfig()->getWhitelistForAllowedAttributeValueChanges()
        );

        return $propelSchemaValidator;
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\Schema\XmlValidator\PropelSchemaXmlNameValidator
     */
    public function createSchemaXmlValidator(): PropelSchemaXmlNameValidator
    {
        return new PropelSchemaXmlNameValidator(
            $this->createCoreSchemaFinder()
        );
    }

    /**
     * @return \Spryker\Zed\Propel\Dependency\Service\PropelToUtilTextServiceInterface
     */
    public function getUtilTextService()
    {
        return $this->getProvidedDependency(PropelDependencyProvider::UTIL_TEXT_SERVICE);
    }

    /**
     * @return \Spryker\Zed\Propel\Business\Model\HealthCheck\HealthCheckInterface
     */
    public function createPropelHealthChecker(): HealthCheckInterface
    {
        return new PropelHealthCheck();
    }

    /**
     * @return \Spryker\Zed\Propel\Business\SchemaElementFilter\SchemaElementFilterInterface
     */
    public function createPropelSchemaElementFilter(): SchemaElementFilterInterface
    {
        return new PropelSchemaElementFilter($this->getPropelSchemaElementFilterPlugins());
    }

    /**
     * @return \Spryker\Zed\Propel\Dependency\Plugin\PropelSchemaElementFilterPluginInterface[]
     */
    public function getPropelSchemaElementFilterPlugins(): array
    {
        return $this->getProvidedDependency(PropelDependencyProvider::PLUGINS_PROPEL_SCHEMA_ELEMENT_FILTER);
    }
}
