<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantRelationshipApi\Business\Creator\MerchantRelationshipCreator;
use Spryker\Zed\MerchantRelationshipApi\Business\Creator\MerchantRelationshipCreatorInterface;
use Spryker\Zed\MerchantRelationshipApi\Business\Deleter\MerchantRelationshipDeleter;
use Spryker\Zed\MerchantRelationshipApi\Business\Deleter\MerchantRelationshipDeleterInterface;
use Spryker\Zed\MerchantRelationshipApi\Business\Filter\MerchantRelationshipRequestFilter;
use Spryker\Zed\MerchantRelationshipApi\Business\Filter\MerchantRelationshipRequestFilterInterface;
use Spryker\Zed\MerchantRelationshipApi\Business\Mapper\MerchantRelationshipMapper;
use Spryker\Zed\MerchantRelationshipApi\Business\Mapper\MerchantRelationshipMapperInterface;
use Spryker\Zed\MerchantRelationshipApi\Business\Reader\MerchantRelationshipReader;
use Spryker\Zed\MerchantRelationshipApi\Business\Reader\MerchantRelationshipReaderInterface;
use Spryker\Zed\MerchantRelationshipApi\Business\Updater\MerchantRelationshipUpdater;
use Spryker\Zed\MerchantRelationshipApi\Business\Updater\MerchantRelationshipUpdaterInterface;
use Spryker\Zed\MerchantRelationshipApi\Business\Validator\MerchantRelationshipApiValidator;
use Spryker\Zed\MerchantRelationshipApi\Business\Validator\MerchantRelationshipApiValidatorInterface;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToApiFacadeInterface;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Service\MerchantRelationshipApiToUtilEncodingServiceInterface;
use Spryker\Zed\MerchantRelationshipApi\MerchantRelationshipApiDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationshipApi\MerchantRelationshipApiConfig getConfig()
 */
class MerchantRelationshipApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationshipApi\Business\Creator\MerchantRelationshipCreatorInterface
     */
    public function createMerchantRelationshipCreator(): MerchantRelationshipCreatorInterface
    {
        return new MerchantRelationshipCreator(
            $this->getMerchantRelationshipFacade(),
            $this->getApiFacade(),
            $this->createMerchantRelationshipRequestFilter(),
            $this->createMerchantRelationshipMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipApi\Business\Reader\MerchantRelationshipReaderInterface
     */
    public function createMerchantRelationshipReader(): MerchantRelationshipReaderInterface
    {
        return new MerchantRelationshipReader(
            $this->getMerchantRelationshipFacade(),
            $this->getApiFacade(),
            $this->createMerchantRelationshipMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipApi\Business\Updater\MerchantRelationshipUpdaterInterface
     */
    public function createMerchantRelationshipUpdater(): MerchantRelationshipUpdaterInterface
    {
        return new MerchantRelationshipUpdater(
            $this->getMerchantRelationshipFacade(),
            $this->getApiFacade(),
            $this->createMerchantRelationshipRequestFilter(),
            $this->createMerchantRelationshipMapper(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipApi\Business\Deleter\MerchantRelationshipDeleterInterface
     */
    public function createMerchantRelationshipDeleter(): MerchantRelationshipDeleterInterface
    {
        return new MerchantRelationshipDeleter(
            $this->getMerchantRelationshipFacade(),
            $this->getApiFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipApi\Business\Mapper\MerchantRelationshipMapperInterface
     */
    public function createMerchantRelationshipMapper(): MerchantRelationshipMapperInterface
    {
        return new MerchantRelationshipMapper(
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipApi\Business\Validator\MerchantRelationshipApiValidatorInterface
     */
    public function createMerchantRelationshipApiValidator(): MerchantRelationshipApiValidatorInterface
    {
        return new MerchantRelationshipApiValidator();
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipApi\Business\Filter\MerchantRelationshipRequestFilterInterface
     */
    public function createMerchantRelationshipRequestFilter(): MerchantRelationshipRequestFilterInterface
    {
        return new MerchantRelationshipRequestFilter($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipApi\Dependency\Service\MerchantRelationshipApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): MerchantRelationshipApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): MerchantRelationshipApiToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipApiDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipApi\Dependency\Facade\MerchantRelationshipApiToApiFacadeInterface
     */
    public function getApiFacade(): MerchantRelationshipApiToApiFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipApiDependencyProvider::FACADE_API);
    }
}
