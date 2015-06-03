<?php

namespace SprykerEngine\Zed\Propel\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\PropelBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerEngine\Zed\Propel\Business\Model\DirectoryRemoverInterface;
use SprykerEngine\Zed\Propel\Business\Model\Merge;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaFinderInterface;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaInterface;
use SprykerEngine\Zed\Propel\Business\Model\PropelSchemaReplicatorInterface;
use SprykerEngine\Zed\Propel\PropelConfig;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @method PropelBusiness getFactory()
 * @method PropelConfig getConfig()
 */
class PropelDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return PropelSchemaInterface
     */
    public function createModelPropelSchema()
    {
        return $this->getFactory()->createModelPropelSchema(
            $this->createDirectoryRemover($this->getConfig()->getSchemaDirectory()),
            $this->createSchemaFinder(),
            $this->createSchemaReplicator()
        );
    }

    /**
     * @return Merge
     */
    public function createModelMerge()
    {
        return $this->getFactory()->createModelMerge(
            new Filesystem(),
            new Finder(),
            $this->getConfig()->getSchemaDirectory()
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
        $schemaReplicator = $this->getFactory()->createModelPropelSchemaReplicator(
            $this->getConfig()->getSchemaDirectory()
        );

        return $schemaReplicator;
    }

    /**
     * @return PropelSchemaFinderInterface
     */
    private function createSchemaFinder()
    {
        $schemaFinder = $this->getFactory()->createModelPropelSchemaFinder(
            $this->getConfig()->getPropelSchemaPathPattern(),
            $this->getConfig()->getPropelSchemaFileNamePattern()
        );

        return $schemaFinder;
    }

}
