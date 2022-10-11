<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Store\Reader\StoreReader as SharedStoreReader;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Store\Business\Cache\StoreCache;
use Spryker\Zed\Store\Business\Cache\StoreCacheInterface;
use Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceAccessTokenRequestExpander;
use Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceAccessTokenRequestExpanderInterface;
use Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceMessageAttributesExpander;
use Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceMessageAttributesExpanderInterface;
use Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProvider;
use Spryker\Zed\Store\Business\Model\StoreMapper;
use Spryker\Zed\Store\Business\Model\StoreReader;
use Spryker\Zed\Store\Business\Model\StoreValidator;
use Spryker\Zed\Store\Business\Model\StoreValidatorInterface;
use Spryker\Zed\Store\Business\Reader\StoreReferenceReader;
use Spryker\Zed\Store\Business\Reader\StoreReferenceReaderInterface;
use Spryker\Zed\Store\Business\Validator\MessageValidator;
use Spryker\Zed\Store\Business\Validator\MessageValidatorInterface;
use Spryker\Zed\Store\StoreDependencyProvider;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Persistence\StoreRepositoryInterface getRepository()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class StoreBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Store\Business\Model\StoreReaderInterface
     */
    public function createStoreReader()
    {
        return new StoreReader(
            $this->getSharedStore(),
            $this->getQueryContainer(),
            $this->getRepository(),
            $this->createStoreMapper(),
            $this->createStoreCache(),
            $this->createStoreReferenceReader(),
        );
    }

    /**
     * @return \Spryker\Zed\Store\Business\Model\StoreValidatorInterface
     */
    public function createStoreValidator(): StoreValidatorInterface
    {
        return new StoreValidator($this->createStoreReader());
    }

    /**
     * @return \Spryker\Zed\Store\Business\Cache\StoreCacheInterface
     */
    public function createStoreCache(): StoreCacheInterface
    {
        return new StoreCache();
    }

    /**
     * @return \Spryker\Zed\Store\Business\Model\StoreMapperInterface
     */
    protected function createStoreMapper()
    {
        return new StoreMapper($this->createSharedStoreReader());
    }

    /**
     * @return \Spryker\Shared\Store\Reader\StoreReaderInterface
     */
    protected function createSharedStoreReader()
    {
        return new SharedStoreReader($this->getSharedStore());
    }

    /**
     * @return \Spryker\Shared\Store\Dependency\Adapter\StoreToStoreInterface
     */
    protected function getSharedStore()
    {
        return $this->getProvidedDependency(StoreDependencyProvider::STORE);
    }

    /**
     * @deprecated Unused method will be removed in next major.
     *
     * @return \Spryker\Zed\Store\Business\Model\Configuration\StoreConfigurationProviderInterface
     */
    protected function createStoreConfigurationProvider()
    {
        return new StoreConfigurationProvider($this->getStore());
    }

    /**
     * @deprecated Unused method will be removed in next major.
     *
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return Store::getInstance();
    }

    /**
     * @return \Spryker\Zed\Store\Business\Reader\StoreReferenceReaderInterface
     */
    public function createStoreReferenceReader(): StoreReferenceReaderInterface
    {
        return new StoreReferenceReader($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceAccessTokenRequestExpanderInterface
     */
    public function createStoreReferenceAccessTokenRequestExpander(): CurrentStoreReferenceAccessTokenRequestExpanderInterface
    {
        return new CurrentStoreReferenceAccessTokenRequestExpander(
            $this->createStoreReader(),
        );
    }

    /**
     * @return \Spryker\Zed\Store\Business\Validator\MessageValidatorInterface
     */
    public function createMessageTransferValidator(): MessageValidatorInterface
    {
        return new MessageValidator($this->createStoreReader());
    }

    /**
     * @return \Spryker\Zed\Store\Business\Expander\CurrentStoreReferenceMessageAttributesExpanderInterface
     */
    public function createCurrentStoreReferenceMessageAttributesExpander(): CurrentStoreReferenceMessageAttributesExpanderInterface
    {
        return new CurrentStoreReferenceMessageAttributesExpander($this->createStoreReader());
    }
}
