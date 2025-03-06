<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement;

use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig getConfig()
 */
class SspFileManagementDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_FILE_MANAGER = 'FACADE_FILE_MANAGER';

    /**
     * @var string
     */
    public const FACADE_TRANSLATOR = 'FACADE_TRANSLATOR';

    /**
     * @var string
     */
    public const PROPEL_QUERY_FILE = 'PROPEL_QUERY_FILE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_FILE_INFO = 'PROPEL_QUERY_FILE_INFO';

    /**
     * @var string
     */
    public const FORM_FACTORY = 'FORM_FACTORY';

    /**
     * @var string
     */
    public const FACADE_COMPANY = 'FACADE_COMPANY';

    /**
     * @var string
     */
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';

    /**
     * @var string
     */
    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';

    /**
     * @var string
     */
    public const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';

    /**
     * @var string
     */
    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addFileQuery($container);
        $container = $this->addFileInfoQuery($container);
        $container = $this->addFileManagerFacade($container);
        $container = $this->addTranslatorFacade($container);
        $container = $this->addCompanyFacade($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addUtilDateTimeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addFileQuery($container);
        $container = $this->addFileInfoQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_FILE, $container->factory(function () {
            return SpyFileQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFileInfoQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_FILE_INFO, $container->factory(function () {
            return SpyFileInfoQuery::create();
        }));

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
    protected function addTranslatorFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSLATOR, function (Container $container) {
            return $container->getLocator()->translator()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY, function (Container $container) {
            return $container->getLocator()->company()->facade();
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
    protected function addCompanyBusinessUnitFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_BUSINESS_UNIT, function (Container $container) {
            return $container->getLocator()->companyBusinessUnit()->facade();
        });

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
    protected function addUtilDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATE_TIME, function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        });

        return $container;
    }
}
