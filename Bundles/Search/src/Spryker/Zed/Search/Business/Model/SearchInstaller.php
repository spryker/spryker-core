<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
        foreach ($this->installer as $installer) {
            $installer->setMessenger($this->messenger);
            $installer->install();
        }
    }

}
