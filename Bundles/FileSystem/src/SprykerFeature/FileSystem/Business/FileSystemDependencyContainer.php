<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FileSystem\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\FileSystemBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\FileSystem\FileSystemDependencyProvider;
use SprykerFeature\Zed\FileSystem\Persistence\FileSystemQueryContainerInterface;

/**
 * @method FileSystemBusiness getFactory()
 * @method FileSystemQueryContainerInterface getQueryContainer()
 */
class FileSystemDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return FileSystemManagerInterface
     */
    public function getFileSystemManager()
    {
        return $this->getFactory()->createFileSystemManager(
            $this->getQueryContainer(),
            $this->getProvidedDependency(FileSystemDependencyProvider::PLUGIN_PROPEL_CONNECTION)
        );
    }

}
