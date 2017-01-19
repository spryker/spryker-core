<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Installer\Communication\Plugin;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Installer\Business\Model\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Installer\Business\InstallerFacade getFacade()
 * @method \Spryker\Zed\Installer\Communication\InstallerCommunicationFactory getFactory()
 */
abstract class AbstractInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $messenger;

    /**
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return $this
     */
    public function setMessenger(LoggerInterface $messenger)
    {
        $this->messenger = $messenger;

        return $this;
    }

    /**
     * @return void
     */
    abstract protected function install();

    /**
     * @return void
     */
    public function run()
    {
        if ($this->messenger instanceof LoggerInterface) {
            $this->messenger->debug('Running installer plugin: ' . get_class($this));
        }

        $this->install();
    }

}
