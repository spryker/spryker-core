<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Messenger\Business\Model\MessengerInterface;
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
     * @return \Spryker\Zed\Locale\Business\Manager\LocaleManager
     */
    public function createLocaleManager()
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
     * @return \Spryker\Zed\Locale\Business\Internal\Install\LocaleInstaller
     */
    public function createInstaller(MessengerInterface $messenger)
    {
        $installer = new LocaleInstaller(
            $this->getQueryContainer(),
            $this->getConfig()->getLocaleFile()
        );
        $installer->setMessenger($messenger);

        return $installer;
    }

}
