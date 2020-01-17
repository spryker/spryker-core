<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantUser\Business\Merchant\MerchantPostCreator;
use Spryker\Zed\MerchantUser\Business\Merchant\MerchantPostCreatorInterface;
use Spryker\Zed\MerchantUser\Business\Merchant\MerchantPostUpdater;
use Spryker\Zed\MerchantUser\Business\Merchant\MerchantPostUpdaterInterface;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriter;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface;
use Spryker\Zed\MerchantUser\Business\Message\MessageConverter;
use Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface;
use Spryker\Zed\MerchantUser\MerchantUserDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantUser\MerchantUserConfig getConfig()
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantUser\Persistence\MerchantUserRepositoryInterface getRepository()
 */
class MerchantUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantUser\Business\Merchant\MerchantPostCreatorInterface
     */
    public function createMerchantPostCreator(): MerchantPostCreatorInterface
    {
        return new MerchantPostCreator(
            $this->createMerchantUserWriter(),
            $this->createMessageConverter()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\Merchant\MerchantPostUpdaterInterface
     */
    public function createMerchantPostUpdater(): MerchantPostUpdaterInterface
    {
        return new MerchantPostUpdater(
            $this->createMerchantUserWriter(),
            $this->createMerchantPostCreator(),
            $this->createMessageConverter(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserWriterInterface
     */
    public function createMerchantUserWriter(): MerchantUserWriterInterface
    {
        return new MerchantUserWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig(),
            $this->getUserFacade(),
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\Message\MessageConverterInterface
     */
    public function createMessageConverter(): MessageConverterInterface
    {
        return new MessageConverter();
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToUserFacadeInterface
     */
    public function getUserFacade(): MerchantUserToUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::FACADE_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Dependency\Service\MerchantUserToUtilTextServiceInterface
     */
    public function getUtilTextService(): MerchantUserToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
