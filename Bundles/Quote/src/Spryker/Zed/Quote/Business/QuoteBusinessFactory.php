<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Quote\Business\GuestQuote\GuestQuoteDeleter;
use Spryker\Zed\Quote\Business\GuestQuote\GuestQuoteDeleterInterface;
use Spryker\Zed\Quote\Business\Model\QuoteDeleter;
use Spryker\Zed\Quote\Business\Model\QuoteDeleterInterface;
use Spryker\Zed\Quote\Business\Model\QuoteReader;
use Spryker\Zed\Quote\Business\Model\QuoteReaderInterface;
use Spryker\Zed\Quote\Business\Model\QuoteWriter;
use Spryker\Zed\Quote\Business\Model\QuoteWriterInterface;
use Spryker\Zed\Quote\Business\Model\QuoteWriterPluginExecutor;
use Spryker\Zed\Quote\Business\Model\QuoteWriterPluginExecutorInterface;
use Spryker\Zed\Quote\Business\Quote\QuoteFieldsConfigurator;
use Spryker\Zed\Quote\Business\Quote\QuoteFieldsConfiguratorInterface;
use Spryker\Zed\Quote\Business\Quote\QuoteLocker;
use Spryker\Zed\Quote\Business\Quote\QuoteLockerInterface;
use Spryker\Zed\Quote\Business\QuoteValidator\QuoteLockStatusValidator;
use Spryker\Zed\Quote\Business\QuoteValidator\QuoteLockStatusValidatorInterface;
use Spryker\Zed\Quote\Business\Validator\QuoteValidator;
use Spryker\Zed\Quote\Business\Validator\QuoteValidatorInterface;
use Spryker\Zed\Quote\QuoteConfig;
use Spryker\Zed\Quote\QuoteDependencyProvider;

/**
 * @method \Spryker\Zed\Quote\Persistence\QuoteEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Quote\Persistence\QuoteRepositoryInterface getRepository()
 * @method \Spryker\Zed\Quote\QuoteConfig getConfig()
 */
class QuoteBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteWriterInterface
     */
    public function createQuoteWriter(): QuoteWriterInterface
    {
        return new QuoteWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createQuoteWriterPluginExecutor(),
            $this->getStoreFacade(),
            $this->createQuoteValidator(),
            $this->createQuoteFieldsConfigurator(),
            $this->getQuoteExpandBeforeCreatePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteWriterPluginExecutorInterface
     */
    public function createQuoteWriterPluginExecutor(): QuoteWriterPluginExecutorInterface
    {
        return new QuoteWriterPluginExecutor(
            $this->getQuoteCreateBeforePlugins(),
            $this->getQuoteCreateAfterPlugins(),
            $this->getQuoteUpdateBeforePlugins(),
            $this->getQuoteUpdateAfterPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\QuoteValidator\QuoteLockStatusValidatorInterface
     */
    public function createQuoteLockStatusValidator(): QuoteLockStatusValidatorInterface
    {
        return new QuoteLockStatusValidator();
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteReaderInterface
     */
    public function createQuoteReader(): QuoteReaderInterface
    {
        return new QuoteReader(
            $this->getRepository(),
            $this->getQuoteExpanderPlugins(),
            $this->getStoreFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Quote\QuoteLockerInterface
     */
    public function createQuoteLocker(): QuoteLockerInterface
    {
        return new QuoteLocker();
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Model\QuoteDeleterInterface
     */
    public function createQuoteDeleter(): QuoteDeleterInterface
    {
        return new QuoteDeleter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getQuoteDeleteBeforePlugins(),
            $this->getQuoteDeleteAfterPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\GuestQuote\GuestQuoteDeleterInterface
     */
    public function createGuestQuoteDeleter(): GuestQuoteDeleterInterface
    {
        return new GuestQuoteDeleter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig(),
            $this->getQuoteDeleteBeforePlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Validator\QuoteValidatorInterface
     */
    public function createQuoteValidator(): QuoteValidatorInterface
    {
        return new QuoteValidator(
            $this->getQuoteValidatorPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\Quote\QuoteFieldsConfigurator
     */
    public function createQuoteFieldsConfigurator(): QuoteFieldsConfiguratorInterface
    {
        return new QuoteFieldsConfigurator(
            $this->getConfig(),
            $this->getQuoteFieldsAllowedForSavingProviderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\QuoteConfig
     */
    public function getBundleConfig(): QuoteConfig
    {
        return $this->getConfig();
    }

    /**
     * @return \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface
     */
    public function getStoreFacade()
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface[]
     */
    public function getQuoteExpanderPlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected function getQuoteCreateAfterPlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_CREATE_AFTER);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected function getQuoteCreateBeforePlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_CREATE_BEFORE);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpandBeforeCreatePluginInterface[]
     */
    public function getQuoteExpandBeforeCreatePlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_EXPAND_BEFORE_CREATE);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected function getQuoteUpdateAfterPlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_UPDATE_AFTER);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected function getQuoteUpdateBeforePlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_UPDATE_BEFORE);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteWritePluginInterface[]
     */
    protected function getQuoteDeleteBeforePlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_DELETE_BEFORE);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteDeleteAfterPluginInterface[]
     */
    protected function getQuoteDeleteAfterPlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_DELETE_AFTER);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatorPluginInterface[]
     */
    public function getQuoteValidatorPlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_VALIDATOR);
    }

    /**
     * @return \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteFieldsAllowedForSavingProviderPluginInterface[]
     */
    protected function getQuoteFieldsAllowedForSavingProviderPlugins(): array
    {
        return $this->getProvidedDependency(QuoteDependencyProvider::PLUGINS_QUOTE_FIELDS_ALLOWED_FOR_SAVING_PROVIDER);
    }
}
