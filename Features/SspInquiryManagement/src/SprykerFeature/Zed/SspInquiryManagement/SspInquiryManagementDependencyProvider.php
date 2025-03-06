<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 */
class SspInquiryManagementDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_STATE_MACHINE = 'FACADE_STATE_MACHINE';

    /**
     * @var string
     */
    public const FACADE_MAIL = 'FACADE_MAIL';

    /**
     * @var string
     */
    public const PLUGINS_STATE_MACHINE_COMMAND = 'PLUGINS_STATE_MACHINE_COMMAND';

    /**
     * @var string
     */
    public const PLUGINS_STATE_MACHINE_CONDITION = 'PLUGINS_STATE_MACHINE_CONDITION';

    /**
     * @var string
     */
    public const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';

    /**
     * @var string
     */
    public const FACADE_FILE_MANAGER = 'FACADE_FILE_MANAGER';

    /**
     * @var string
     */
    public const FACADE_COMMENT = 'FACADE_COMMENT';

    /**
     * @var string
     */
    public const FACADE_SALES = 'FACADE_SALES';

    /**
     * @var string
     */
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';

    /**
     * @var string
     */
    public const PROPEL_QUERY_COMPANY_USER = 'PROPEL_QUERY_COMPANY_USER';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    /**
     * @var string
     */
    public const SERVICE_FILE_MANAGER = 'SERVICE_FILE_MANAGER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addStateMachineCommandPlugins($container);
        $container = $this->addStateMachineConditionPlugins($container);
        $container = $this->addMailFacade($container);
        $container = $this->addStateMachineFacade($container);
        $container = $this->addFileManagerFacade($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addFileManagerService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addFileManagerFacade($container);
        $container = $this->addCompanyUserPropelQuery($container);
        $container = $this->addStateMachineFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addCommentFacade($container);
        $container = $this->addCommentFacade($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSequenceNumberFacade(Container $container): Container
    {
        $container->set(static::FACADE_SEQUENCE_NUMBER, function (Container $container) {
            return $container->getLocator()->sequenceNumber()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileManagerFacade(Container $container): Container
    {
        $container->set(static::FACADE_FILE_MANAGER, function (Container $container) {
            return $container->getLocator()->fileManager()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container): Container
    {
        $container->set(static::FACADE_MAIL, function (Container $container) {
            return $container->getLocator()->mail()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStateMachineConditionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STATE_MACHINE_CONDITION, function () {
            return $this->getStateMachineConditionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStateMachineCommandPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STATE_MACHINE_COMMAND, function () {
            return $this->getStateMachineCommandPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface>
     */
    protected function getStateMachineConditionPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface>
     */
    protected function getStateMachineCommandPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStateMachineFacade(Container $container): Container
    {
        $container->set(static::FACADE_STATE_MACHINE, function (Container $container) {
            return $container->getLocator()->stateMachine()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATE_TIME, function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCommentFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMMENT, function (Container $container) {
            return $container->getLocator()->comment()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return $container->getLocator()->sales()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_USER, function (Container $container) {
            return $container->getLocator()->companyUser()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COMPANY_USER, $container->factory(function (): SpyCompanyUserQuery {
            return SpyCompanyUserQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return $container->getLocator()->store()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileManagerService(Container $container): Container
    {
        $container->set(static::SERVICE_FILE_MANAGER, function (Container $container): FileManagerServiceInterface {
            return $container->getLocator()->fileManager()->service();
        });

        return $container;
    }
}
