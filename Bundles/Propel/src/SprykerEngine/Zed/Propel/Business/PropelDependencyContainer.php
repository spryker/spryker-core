<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business;

use SprykerEngine\Zed\Propel\Business\Model\PostgresqlCompatibilityAdjuster;
use SprykerEngine\Zed\Propel\Business\Model\DirectoryRemover;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaMerger;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaWriter;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaFinder;
use SprykerEngine\Zed\Propel\Business\Model\PropelGroupedSchemaFinder;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchema;
use Generated\Zed\Ide\FactoryAutoCompletion\PropelBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Propel\Business\Model\DirectoryRemoverInterface;
use SprykerEngine\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface;
use SprykerEngine\Zed\Propel\Business\Model\PostgresqlCompatibilityAdjusterInterface;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaFinderInterface;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaInterface;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaMergerInterface;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaWriterInterface;
use SprykerEngine\Zed\Propel\PropelConfig;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @method PropelBusiness getFactory()
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
    private function createGroupedSchemaFinder()
    {
        $schemaFinder = new PropelGroupedSchemaFinder(
            $this->createSchemaFinder()
        );

        return $schemaFinder;
    }

    /**
     * @return PropelSchemaFinderInterface
     */
    private function createSchemaFinder()
    {
        $schemaFinder = new PropelSchemaFinder(
            $this->getConfig()->getPropelSchemaPathPatterns()
        );

        return $schemaFinder;
    }

    /**
     * @return PropelSchemaWriterInterface
     */
    private function createSchemaWriter()
    {
        $schemaWriter = new PropelSchemaWriter(
            new Filesystem(),
            $this->getConfig()->getSchemaDirectory()
        );

        return $schemaWriter;
    }

    /**
     * @return PropelSchemaMergerInterface
     */
    private function createSchemaMerger()
    {
        return new PropelSchemaMerger();
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

}
