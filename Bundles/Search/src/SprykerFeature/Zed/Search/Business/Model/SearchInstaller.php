<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Search\Business\Model;

use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerFeature\Zed\Installer\Communication\Plugin\AbstractInstallerPlugin;

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
     * @param MessengerInterface $messenger
     */
    public function __construct(array $installer, MessengerInterface $messenger)
    {
        $this->installer = $installer;
        $this->messenger = $messenger;
    }

    public function install()
    {
        foreach ($this->installer as $installer) {
            $installer->setMessenger($this->messenger);
            $installer->install();
        }
    }

}
