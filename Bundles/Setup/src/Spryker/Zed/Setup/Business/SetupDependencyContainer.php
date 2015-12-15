<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Business;

use Spryker\Zed\Setup\Business\Model\DirectoryRemover;
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\Application\Communication\Plugin\TransferObject\Repeater;
use Spryker\Zed\Setup\Business\Model\Cronjobs;
use Spryker\Zed\Setup\Business\Model\DirectoryRemoverInterface;
use Spryker\Zed\Setup\SetupConfig;
use Spryker\Zed\Setup\SetupDependencyProvider;
use Symfony\Component\Console\Command\Command;

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

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getProvidedDependency(SetupDependencyProvider::COMMANDS);
    }

}
