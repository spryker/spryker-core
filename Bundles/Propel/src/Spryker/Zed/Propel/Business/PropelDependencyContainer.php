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
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\Propel\Business\Model\DirectoryRemoverInterface;
use Spryker\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface;
use Spryker\Zed\Propel\Business\Model\PostgresqlCompatibilityAdjusterInterface;
use Spryker\Zed\Propel\Business\Model\PropelSchemaFinderInterface;
use Spryker\Zed\Propel\Business\Model\PropelSchemaInterface;
use Spryker\Zed\Propel\Business\Model\PropelSchemaMergerInterface;
use Spryker\Zed\Propel\Business\Model\PropelSchemaWriterInterface;
use Spryker\Zed\Propel\PropelConfig;
use Spryker\Zed\Propel\PropelDependencyProvider;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;

/**
 * @method PropelConfig getConfig()
 */
class PropelDependencyContainer extends AbstractBusinessDependencyContainer
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
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getProvidedDependency(PropelDependencyProvider::COMMANDS);
    }

    /**
     * @return Filesystem
     */
    protected function createFilesystem()
    {
        $filesystem = new Filesystem();

        return $filesystem;
    }

}
