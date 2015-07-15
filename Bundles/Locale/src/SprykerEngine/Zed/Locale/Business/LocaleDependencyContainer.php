<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Locale\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\LocaleBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Locale\Business\Internal\Install\LocaleInstaller;
use SprykerEngine\Zed\Locale\Business\Manager\LocaleManager;
use SprykerEngine\Zed\Locale\LocaleConfig;
use SprykerEngine\Zed\Locale\Persistence\LocaleQueryContainerInterface;

/**
 * @method LocaleBusiness getFactory()
 * @method LocaleConfig getConfig()
 */
class LocaleDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return LocaleManager
     */
    public function getLocaleManager()
    {
        return $this->getFactory()->createManagerLocaleManager(
            $this->getQueryContainer(),
            $this->getLocator()
        );
    }

    /**
     * @return LocaleQueryContainerInterface
     */
    protected function getQueryContainer()
    {
        return $this->getLocator()->locale()->queryContainer();
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return LocaleInstaller
     */
    public function getInstaller(MessengerInterface $messenger)
    {
        $installer = $this->getFactory()->createInternalInstallLocaleInstaller(
            $this->getQueryContainer(),
            $this->getConfig()->getLocaleFile()
        );
        $installer->setMessenger($messenger);

        return $installer;
    }

}
