<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Merchant\Business\Creator\MerchantCreator;
use Spryker\Zed\Merchant\Business\Creator\MerchantCreatorInterface;
use Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaver;
use Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface;
use Spryker\Zed\Merchant\Business\Reader\MerchantReader;
use Spryker\Zed\Merchant\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\Merchant\Business\Status\MerchantStatusReader;
use Spryker\Zed\Merchant\Business\Status\MerchantStatusReaderInterface;
use Spryker\Zed\Merchant\Business\Status\MerchantStatusValidator;
use Spryker\Zed\Merchant\Business\Status\MerchantStatusValidatorInterface;
use Spryker\Zed\Merchant\Business\Updater\MerchantUpdater;
use Spryker\Zed\Merchant\Business\Updater\MerchantUpdaterInterface;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToUrlFacadeInterface;
use Spryker\Zed\Merchant\Dependency\Service\MerchantToUtilTextServiceInterface;
use Spryker\Zed\Merchant\MerchantDependencyProvider;

/**
 * @method \Spryker\Zed\Merchant\Persistence\MerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\Merchant\Persistence\MerchantEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Merchant\MerchantConfig getConfig()
 */
class MerchantBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Merchant\Business\Creator\MerchantCreatorInterface
     */
    public function createMerchantCreator(): MerchantCreatorInterface
    {
        return new MerchantCreator(
            $this->getEntityManager(),
            $this->getConfig(),
            $this->getMerchantPostCreatePlugins(),
            $this->createMerchantUrlSaver(),
            $this->getEventFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Updater\MerchantUpdaterInterface
     */
    public function createMerchantUpdater(): MerchantUpdaterInterface
    {
        return new MerchantUpdater(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createMerchantStatusValidator(),
            $this->getMerchantPostUpdatePlugins(),
            $this->createMerchantUrlSaver(),
            $this->getEventFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Reader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->getRepository(),
            $this->getMerchantExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Status\MerchantStatusReaderInterface
     */
    public function createMerchantStatusReader(): MerchantStatusReaderInterface
    {
        return new MerchantStatusReader(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Status\MerchantStatusValidatorInterface
     */
    public function createMerchantStatusValidator(): MerchantStatusValidatorInterface
    {
        return new MerchantStatusValidator(
            $this->createMerchantStatusReader()
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Dependency\Service\MerchantToUtilTextServiceInterface
     */
    public function getUtilTextService(): MerchantToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostCreatePluginInterface[]
     */
    public function getMerchantPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::PLUGINS_MERCHANT_POST_CREATE);
    }

    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface[]
     */
    public function getMerchantPostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::PLUGINS_MERCHANT_POST_UPDATE);
    }

    /**
     * @return \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface[]
     */
    public function getMerchantExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::PLUGINS_MERCHANT_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\Merchant\Dependency\Facade\MerchantToUrlFacadeInterface
     */
    public function getUrlFacade(): MerchantToUrlFacadeInterface
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::FACADE_URL);
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface
     */
    public function createMerchantUrlSaver(): MerchantUrlSaverInterface
    {
        return new MerchantUrlSaver(
            $this->getUrlFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface
     */
    public function getEventFacade(): MerchantToEventFacadeInterface
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::FACADE_EVENT);
    }
}
