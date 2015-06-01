<?php

namespace SprykerFeature\Zed\Setup\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SetupBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Setup\Business\Model\Cronjobs;
use SprykerFeature\Zed\Setup\Business\Model\DirectoryRemoverInterface;
use SprykerFeature\Zed\Setup\Business\Model\Propel\PropelSchemaFinderInterface;
use SprykerFeature\Zed\Setup\Business\Model\Propel\PropelSchemaInterface;
use SprykerFeature\Zed\Setup\Business\Model\Propel\PropelSchemaReplicatorInterface;
use SprykerFeature\Zed\Setup\SetupConfig;

/**
 * @method SetupConfig getConfig()
 * @method SetupBusiness getFactory()
 */
class SetupDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return Cronjobs
     */
    public function createModelCronjobs()
    {
        $config = $this->getConfig();

        return $this->getFactory()->createModelCronjobs($config);
    }

    /**
     * @return DirectoryRemoverInterface
     */
    public function createModelGeneratedDirectoryRemover()
    {
        return $this->createDirectoryRemover(
            $this->getConfig()->getGeneratedDirectory()
        );
    }

    /**
     * @return PropelSchemaInterface
     */
    public function createModelPropelSchema()
    {
        return $this->getFactory()->createModelPropelPropelSchema(
            $this->createDirectoryRemover($this->getConfig()->getSchemaDirectory()),
            $this->createSchemaFinder(),
            $this->createSchemaReplicator()
        );
    }

    /**
     * @param string $path
     *
     * @return DirectoryRemoverInterface
     */
    private function createDirectoryRemover($path)
    {
        return $this->getFactory()->createModelDirectoryRemover($path);
    }

    /**
     * @return PropelSchemaReplicatorInterface
     */
    private function createSchemaReplicator()
    {
        $schemaReplicator = $this->getFactory()->createModelPropelPropelSchemaReplicator(
            $this->getConfig()->getSchemaDirectory()
        );

        return $schemaReplicator;
    }

    /**
     * @return PropelSchemaFinderInterface
     */
    private function createSchemaFinder()
    {
        $schemaFinder = $this->getFactory()->createModelPropelPropelSchemaFinder(
            $this->getConfig()->getPropelSchemaPathPattern(),
            $this->getConfig()->getPropelSchemaFileNamePattern()
        );

        return $schemaFinder;
    }
}
