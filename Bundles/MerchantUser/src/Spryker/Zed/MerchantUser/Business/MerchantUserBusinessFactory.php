<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserCreator;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserCreatorInterface;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserUpdater;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserUpdaterInterface;
use Spryker\Zed\MerchantUser\Business\User\UserReader;
use Spryker\Zed\MerchantUser\Business\User\UserReaderInterface;
use Spryker\Zed\MerchantUser\Business\User\UserWriter;
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
     * @return \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserCreatorInterface
     */
    public function createMerchantUserCreator(): MerchantUserCreatorInterface
    {
        return new MerchantUserCreator(
            $this->createUserWriter(),
            $this->createUserReader(),
            $this->getUtilTextService(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserUpdaterInterface
     */
    public function createMerchantUserUpdater(): MerchantUserUpdaterInterface
    {
        return new MerchantUserUpdater(
            $this->createMerchantUserCreator(),
            $this->getRepository(),
            $this->createUserWriter()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\User\UserReaderInterface
     */
    public function createUserReader(): UserReaderInterface
    {
        return new UserReader($this->getUserFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\User\UserWriter
     */
    public function createUserWriter()
    {
        return new UserWriter(
            $this->getUserFacade(),
            $this->createUserReader()
        );
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
