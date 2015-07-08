<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\PropelBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Zed\Propel\Business\Model\DirectoryRemoverInterface;
use SprykerEngine\Zed\Propel\Business\Model\PropelGroupedSchemaFinderInterface;
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
        return $this->getFactory()->createModelPropelSchema(
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
        $schemaFinder = $this->getFactory()->createModelPropelGroupedSchemaFinder(
            $this->createSchemaFinder()
        );

        return $schemaFinder;
    }

    /**
     * @return PropelSchemaFinderInterface
     */
    private function createSchemaFinder()
    {
        $schemaFinder = $this->getFactory()->createModelPropelSchemaFinder(
            $this->getConfig()->getPropelSchemaPathPatterns()
        );

        return $schemaFinder;
    }

    /**
     * @return PropelSchemaWriterInterface
     */
    private function createSchemaWriter()
    {
        $schemaWriter = $this->getFactory()->createModelPropelSchemaWriter(
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
        return $this->getFactory()->createModelPropelSchemaMerger();
    }

    /**
     * @return DirectoryRemoverInterface
     */
    public function createDirectoryRemover()
    {
        return $this->getFactory()->createModelDirectoryRemover(
            $this->getConfig()->getSchemaDirectory()
        );
    }

}
