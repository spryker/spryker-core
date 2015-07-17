<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\SetupBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\Repeater;
use SprykerFeature\Zed\Setup\Business\Model\Cronjobs;
use SprykerFeature\Zed\Setup\Business\Model\DirectoryRemoverInterface;
use SprykerFeature\Zed\Setup\SetupConfig;
use SprykerFeature\Zed\Setup\SetupDependencyProvider;

/**
 * @method SetupConfig getConfig()
 * @method SetupBusiness getFactory()
 */
class SetupDependencyContainer extends AbstractBusinessDependencyContainer
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
     * @param string $path
     *
     * @return DirectoryRemoverInterface
     */
    private function createDirectoryRemover($path)
    {
        return $this->getFactory()->createModelDirectoryRemover($path);
    }

    /**
     * @throws \ErrorException
     *
     * @return Repeater
     */
    public function createTransferObjectRepeater()
    {
        return $this->getProvidedDependency(SetupDependencyProvider::PLUGIN_TRANSFER_OBJECT_REPEATER);
    }

}
