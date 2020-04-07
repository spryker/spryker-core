<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantUser\Business\AclGroup\AclGroupAdder;
use Spryker\Zed\MerchantUser\Business\AclGroup\AclGroupAdderInterface;
use Spryker\Zed\MerchantUser\Business\MerchantUser\CurrentMerchantUserReader;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserCreator;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserCreatorInterface;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserReaderInterface;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserUpdater;
use Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserUpdaterInterface;
use Spryker\Zed\MerchantUser\Business\User\UserMapper;
use Spryker\Zed\MerchantUser\Business\User\UserMapperInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAclFacadeInterface;
use Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface;
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
            $this->getUtilTextService(),
            $this->getUserFacade(),
            $this->createUserMapper(),
            $this->createAclGroupAdder(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\AclGroup\AclGroupAdderInterface
     */
    public function createAclGroupAdder(): AclGroupAdderInterface
    {
        return new AclGroupAdder($this->getAclFacade());
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\User\UserMapperInterface
     */
    public function createUserMapper(): UserMapperInterface
    {
        return new UserMapper();
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserUpdaterInterface
     */
    public function createMerchantUserUpdater(): MerchantUserUpdaterInterface
    {
        return new MerchantUserUpdater(
            $this->getRepository(),
            $this->getUserFacade(),
            $this->createUserMapper(),
            $this->getAuthFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\MerchantUser\MerchantUserReaderInterface
     */
    public function createCurrentMerchantUserReader(): MerchantUserReaderInterface
    {
        return new CurrentMerchantUserReader(
            $this->getUserFacade(),
            $this->getRepository()
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

    /**
     * @return \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAclFacadeInterface
     */
    public function getAclFacade(): MerchantUserToAclFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::FACADE_ACL);
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Dependency\Facade\MerchantUserToAuthFacadeInterface
     */
    public function getAuthFacade(): MerchantUserToAuthFacadeInterface
    {
        return $this->getProvidedDependency(MerchantUserDependencyProvider::FACADE_AUTH);
    }
}
