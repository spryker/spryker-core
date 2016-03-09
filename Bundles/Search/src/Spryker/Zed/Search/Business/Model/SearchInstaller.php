<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;

class SearchInstaller implements SearchInstallerInterface
{

    /**
     * @var \Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin[]
     */
    private $installer;

    /**
     * @var \Spryker\Zed\Messenger\Business\Model\MessengerInterface
     */
    private $messenger;

    /**
     * @param array $installer
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     */
    public function __construct(array $installer, MessengerInterface $messenger)
    {
        $this->installer = $installer;
        $this->messenger = $messenger;
    }

    /**
     * @return void
     */
    public function install()
    {
        foreach ($this->installer as $installerPlugin) {
            $installerPlugin->setMessenger($this->messenger);
            $installerPlugin->run();
        }
    }

}
