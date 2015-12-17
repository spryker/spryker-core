<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Locale\Business\Internal\Install\LocaleInstaller;
use Spryker\Zed\Locale\Business\Manager\LocaleManager;
use Spryker\Zed\Locale\LocaleConfig;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainer;

/**
 * @method LocaleConfig getConfig()
 * @method LocaleQueryContainer getQueryContainer()
 */
class LocaleBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return LocaleManager
     */
    public function getLocaleManager()
    {
        return new LocaleManager(
            $this->getQueryContainer(),
            $this->createTransferGenerator()
        );
    }

    /**
     * @return TransferGeneratorInterface
     */
    protected function createTransferGenerator()
    {
        return new TransferGenerator();
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return LocaleInstaller
     */
    public function getInstaller(MessengerInterface $messenger)
    {
        $installer = new LocaleInstaller(
            $this->getQueryContainer(),
            $this->getConfig()->getLocaleFile()
        );
        $installer->setMessenger($messenger);

        return $installer;
    }

}
