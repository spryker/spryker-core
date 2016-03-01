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
    protected $installerPlugins;

    /**
     * @var \Spryker\Zed\Messenger\Business\Model\MessengerInterface
     */
    protected $messenger;

    /**
     * @param \Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin[] $installerPlugins
     * @param \Spryker\Zed\Messenger\Business\Model\MessengerInterface $messenger
     */
    public function __construct(array $installerPlugins, MessengerInterface $messenger)
    {
        $this->installerPlugins = $installerPlugins;
        $this->messenger = $messenger;
    }

    /**
     * @return void
     */
    public function install()
    {
        foreach ($this->installerPlugins as $installerPlugin) {
            $installerPlugin->setMessenger($this->messenger);
            $installerPlugin->run();
        }
    }

}
