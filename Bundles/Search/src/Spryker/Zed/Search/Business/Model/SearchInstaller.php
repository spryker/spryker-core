<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Search\Business\Model;

use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
use Spryker\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

class SearchInstaller implements SearchInstallerInterface
{

    /**
     * @var AbstractInstallerPlugin[]
     */
    private $installer;

    /**
     * @var MessengerInterface
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
        foreach ($this->installer as $installer) {
            $installer->setMessenger($this->messenger);
            $installer->install();
        }
    }

}
