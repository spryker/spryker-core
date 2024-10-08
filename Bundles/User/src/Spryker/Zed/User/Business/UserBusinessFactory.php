<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business;

use Spryker\Client\Session\SessionClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\User\Business\Expander\MailExpander;
use Spryker\Zed\User\Business\Expander\MailExpanderInterface;
use Spryker\Zed\User\Business\Model\Installer;
use Spryker\Zed\User\Business\Model\User;
use Spryker\Zed\User\Business\Reader\UserReader;
use Spryker\Zed\User\Business\Reader\UserReaderInterface;
use Spryker\Zed\User\UserDependencyProvider;

/**
 * @method \Spryker\Zed\User\UserConfig getConfig()
 * @method \Spryker\Zed\User\Persistence\UserQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\User\Persistence\UserRepositoryInterface getRepository()
 */
class UserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\User\Business\Expander\MailExpanderInterface
     */
    public function createMailExpander(): MailExpanderInterface
    {
        return new MailExpander($this->createUserModel());
    }

    /**
     * @return \Spryker\Zed\User\Business\Model\UserInterface
     */
    public function createUserModel()
    {
        return new User(
            $this->getQueryContainer(),
            $this->getSessionClient(),
            $this->getConfig(),
            $this->getPostSavePlugins(),
            $this->getUserPreSavePlugins(),
            $this->getUserTransferExpanderPlugins(),
            $this->getUserExpanderPlugins(),
            $this->getUserPostCreatePlugins(),
            $this->getUserPostUpdatePlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\User\Business\Reader\UserReaderInterface
     */
    public function createUserReader(): UserReaderInterface
    {
        return new UserReader(
            $this->getRepository(),
            $this->getUserExpanderPlugins(),
            $this->getUserTransferExpanderPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostSavePluginInterface>
     */
    public function getPostSavePlugins(): array
    {
        return $this->getProvidedDependency(UserDependencyProvider::PLUGINS_POST_SAVE);
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPreSavePluginInterface>
     */
    public function getUserPreSavePlugins(): array
    {
        return $this->getProvidedDependency(UserDependencyProvider::PLUGINS_USER_PRE_SAVE);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\User\Business\UserBusinessFactory::getUserExpanderPlugins()} instead.
     *
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTransferExpanderPluginInterface>
     */
    public function getUserTransferExpanderPlugins(): array
    {
        return $this->getProvidedDependency(UserDependencyProvider::PLUGINS_USER_TRANSFER_EXPANDER);
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface
     */
    public function getSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(UserDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Zed\User\Business\Model\Installer
     */
    public function createInstallerModel()
    {
        return new Installer(
            $this->getQueryContainer(),
            $this->createUserModel(),
            $this->getConfig(),
        );
    }

    /**
     * @return array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface>
     */
    public function getUserExpanderPlugins(): array
    {
        return $this->getProvidedDependency(UserDependencyProvider::PLUGINS_USER_EXPANDER);
    }

    /**
     * @return list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostCreatePluginInterface>
     */
    protected function getUserPostCreatePlugins(): array
    {
        return $this->getProvidedDependency(UserDependencyProvider::PLUGINS_USER_POST_CREATE);
    }

    /**
     * @return list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserPostUpdatePluginInterface>
     */
    protected function getUserPostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(UserDependencyProvider::PLUGINS_USER_POST_UPDATE);
    }
}
