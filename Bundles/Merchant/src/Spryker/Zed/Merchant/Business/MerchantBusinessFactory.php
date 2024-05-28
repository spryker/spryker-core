<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Merchant\Business\Creator\MerchantCreator;
use Spryker\Zed\Merchant\Business\Creator\MerchantCreatorInterface;
use Spryker\Zed\Merchant\Business\Expander\MerchantExpander;
use Spryker\Zed\Merchant\Business\Expander\MerchantExpanderInterface;
use Spryker\Zed\Merchant\Business\Exporter\MerchantExporter;
use Spryker\Zed\Merchant\Business\Exporter\MerchantExporterInterface;
use Spryker\Zed\Merchant\Business\Filter\PriceProductMerchantRelationshipStorageFilter;
use Spryker\Zed\Merchant\Business\Filter\PriceProductMerchantRelationshipStorageFilterInterface;
use Spryker\Zed\Merchant\Business\Mapper\TransferMapper;
use Spryker\Zed\Merchant\Business\Mapper\TransferMapperInterface;
use Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaver;
use Spryker\Zed\Merchant\Business\MerchantUrlSaver\MerchantUrlSaverInterface;
use Spryker\Zed\Merchant\Business\Publisher\MerchantMessageBrokerPublisher;
use Spryker\Zed\Merchant\Business\Publisher\MerchantPublisherInterface;
use Spryker\Zed\Merchant\Business\Reader\MerchantReader;
use Spryker\Zed\Merchant\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\Merchant\Business\Status\MerchantStatusReader;
use Spryker\Zed\Merchant\Business\Status\MerchantStatusReaderInterface;
use Spryker\Zed\Merchant\Business\Status\MerchantStatusValidator;
use Spryker\Zed\Merchant\Business\Status\MerchantStatusValidatorInterface;
use Spryker\Zed\Merchant\Business\Trigger\MerchantEventTrigger;
use Spryker\Zed\Merchant\Business\Trigger\MerchantEventTriggerInterface;
use Spryker\Zed\Merchant\Business\Updater\MerchantUpdater;
use Spryker\Zed\Merchant\Business\Updater\MerchantUpdaterInterface;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToMessageBrokerFacadeInterface;
use Spryker\Zed\Merchant\Dependency\Facade\MerchantToStoreFacadeInterface;
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
            $this->getEventFacade(),
            $this->createMerchantEventTrigger(),
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
            $this->getEventFacade(),
            $this->createMerchantEventTrigger(),
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Reader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->getRepository(),
            $this->getStoreFacade(),
            $this->createMerchantExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Status\MerchantStatusReaderInterface
     */
    public function createMerchantStatusReader(): MerchantStatusReaderInterface
    {
        return new MerchantStatusReader(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Status\MerchantStatusValidatorInterface
     */
    public function createMerchantStatusValidator(): MerchantStatusValidatorInterface
    {
        return new MerchantStatusValidator(
            $this->createMerchantStatusReader(),
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
     * @return array<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostCreatePluginInterface>
     */
    public function getMerchantPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::PLUGINS_MERCHANT_POST_CREATE);
    }

    /**
     * @return array<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface>
     */
    public function getMerchantPostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::PLUGINS_MERCHANT_POST_UPDATE);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Merchant\Business\MerchantBusinessFactory::getMerchantBulkExpanderPlugins()} instead.
     *
     * @return array<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface>
     */
    public function getMerchantExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::PLUGINS_MERCHANT_EXPANDER);
    }

    /**
     * @return list<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantBulkExpanderPluginInterface>
     */
    public function getMerchantBulkExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::PLUGINS_MERCHANT_BULK_EXPANDER);
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
            $this->getUrlFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Dependency\Facade\MerchantToEventFacadeInterface
     */
    public function getEventFacade(): MerchantToEventFacadeInterface
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Filter\PriceProductMerchantRelationshipStorageFilterInterface
     */
    public function createPriceProductMerchantRelationshipStorageFilter(): PriceProductMerchantRelationshipStorageFilterInterface
    {
        return new PriceProductMerchantRelationshipStorageFilter($this->getRepository());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Merchant\Dependency\Facade\MerchantToMessageBrokerFacadeInterface
     */
    public function getMessageBrokerFacade(): MerchantToMessageBrokerFacadeInterface
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::FACADE_MESSAGE_BROKER);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Merchant\Business\Publisher\MerchantPublisherInterface
     */
    public function createMerchantMessageBrokerPublisher(): MerchantPublisherInterface
    {
        return new MerchantMessageBrokerPublisher(
            $this->getMessageBrokerFacade(),
            $this->createMerchantReader(),
            $this->createTransferMapper(),
            $this->getConfig(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Merchant\Business\Mapper\TransferMapperInterface
     */
    public function createTransferMapper(): TransferMapperInterface
    {
        return new TransferMapper();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\Merchant\Business\Exporter\MerchantExporterInterface
     */
    public function createMerchantExporter(): MerchantExporterInterface
    {
        return new MerchantExporter(
            $this->getEventFacade(),
            $this->getStoreFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Trigger\MerchantEventTriggerInterface
     */
    public function createMerchantEventTrigger(): MerchantEventTriggerInterface
    {
        return new MerchantEventTrigger($this->getEventFacade());
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\Expander\MerchantExpanderInterface
     */
    public function createMerchantExpander(): MerchantExpanderInterface
    {
        return new MerchantExpander(
            $this->getMerchantExpanderPlugins(),
            $this->getMerchantBulkExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Merchant\Dependency\Facade\MerchantToStoreFacadeInterface
     */
    public function getStoreFacade(): MerchantToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantDependencyProvider::FACADE_STORE);
    }
}
