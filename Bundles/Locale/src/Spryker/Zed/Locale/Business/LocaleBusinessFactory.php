<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Locale\Business\Internal\Install\LocaleInstaller;
use Spryker\Zed\Locale\Business\Manager\LocaleManager;
use Spryker\Zed\Locale\LocaleDependencyProvider;

/**
 * @method \Spryker\Zed\Locale\LocaleConfig getConfig()
 * @method \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Locale\Persistence\LocaleRepositoryInterface getRepository()
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
            $this->createTransferGenerator(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Locale\Business\TransferGeneratorInterface
     */
    protected function createTransferGenerator()
    {
        return new TransferGenerator();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\Internal\Install\LocaleInstaller
     */
    public function createInstaller()
    {
        $installer = new LocaleInstaller(
            $this->getQueryContainer(),
            $this->getConfig()->getLocaleFile()
        );

        return $installer;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::STORE);
    }
}
