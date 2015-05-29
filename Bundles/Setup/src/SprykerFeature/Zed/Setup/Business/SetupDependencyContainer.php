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
        return $this->getDirectoryRemover(
            $this->getConfig()->getGeneratedDirectory()
        );
    }

    /**
     * @return PropelSchemaInterface
     */
    public function createModelPropelSchema()
    {
        return $this->getFactory()->createModelPropelPropelSchema(
            $this->getDirectoryRemover($this->getConfig()->getSchemaDirectory()),
            $this->getSchemaFinder(),
            $this->getSchemaReplicator()
        );
    }

    /**
     * @param $path
     *
     * @return DirectoryRemoverInterface
     */
    private function getDirectoryRemover($path)
    {
        return $this->getFactory()->createModelDirectoryRemover($path);
    }

    /**
     * @return PropelSchemaReplicatorInterface
     */
    private function getSchemaReplicator()
    {
        $schemaReplicator = $this->getFactory()->createModelPropelPropelSchemaReplicator(
            $this->getConfig()->getSchemaDirectory()
        );

        return $schemaReplicator;
    }

    /**
     * @return PropelSchemaFinderInterface
     */
    private function getSchemaFinder()
    {
        $schemaFinder = $this->getFactory()->createModelPropelPropelSchemaFinder(
            $this->getConfig()->getPropelSchemaPathPattern(),
            $this->getConfig()->getPropelSchemaFileNamePattern()
        );

        return $schemaFinder;
    }
}
