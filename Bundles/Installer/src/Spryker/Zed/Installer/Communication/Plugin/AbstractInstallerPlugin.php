<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Installer\Communication\Plugin;

use Spryker\Zed\Installer\Business\Model\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

/**
 * @method \Spryker\Zed\Installer\Business\InstallerFacade getFacade()
 * @method \Spryker\Zed\Installer\Communication\InstallerCommunicationFactory getFactory()
 */
abstract class AbstractInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{

    /**
     * @var \Spryker\Zed\Messenger\Business\Model\MessengerInterface
     */
    protected $messenger;

    /**
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     *
     * @return $this
     */
    public function setMessenger(MessengerInterface $messenger)
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
        if (!($this->messenger instanceof MessengerInterface)) {
            return;
        }

        $this->messenger->debug('Running installer plugin: ' . get_class($this));
        $this->install();
    }

}
