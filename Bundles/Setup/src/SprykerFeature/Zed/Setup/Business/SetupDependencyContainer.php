<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Business;

use SprykerFeature\Zed\Setup\Business\Model\DirectoryRemover;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\Repeater;
use SprykerFeature\Zed\Setup\Business\Model\Cronjobs;
use SprykerFeature\Zed\Setup\Business\Model\DirectoryRemoverInterface;
use SprykerFeature\Zed\Setup\SetupConfig;
use SprykerFeature\Zed\Setup\SetupDependencyProvider;

/**
 * @method SetupConfig getConfig()
 */
class SetupDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Cronjobs
     */
    public function createModelCronjobs()
    {
        $config = $this->getConfig();

        return new Cronjobs($config);
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
        return new DirectoryRemover($path);
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
