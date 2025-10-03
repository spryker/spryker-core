<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest;

use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToCommentFacadeBridge;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToCountryFacadeBridge;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToLocaleFacadeBridge;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToMerchantFacadeBridge;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Facade\MerchantRegistrationRequestToMerchantUserFacadeBridge;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Service\MerchantRegistrationRequestGuiToUtilDateTimeServiceBridge;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Service\MerchantRegistrationRequestToUtilTextServiceBridge;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Service\MerchantRegistrationRequestToUtilUuidGeneratorServiceBridge;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig getConfig()
 */
class MerchantRegistrationRequestDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_COUNTRY = 'FACADE_COUNTRY';

    /**
     * @var string
     */
    public const FACADE_COMMENT = 'FACADE_COMMENT';

    /**
     * @var string
     */
    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    /**
     * @var string
     */
    public const PROPEL_QUERY_MERCHANT = 'PROPEL_QUERY_MERCHANT';

    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @var string
     */
    public const SERVICE_UTIL_UUID_GENERATOR = 'SERVICE_UTIL_UUID_GENERATOR';

    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addCommentFacade($container);
        $container = $this->addUtilUuidGeneratorService($container);

        return $container;
    }

    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addMerchantPropelQuery($container);

        return $container;
    }

    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addMerchantPropelQuery($container);

        return $container;
    }

    protected function addCountryFacade(Container $container): Container
    {
        $container->set(static::FACADE_COUNTRY, function (Container $container) {
            return new MerchantRegistrationRequestToCountryFacadeBridge($container->getLocator()->country()->facade());
        });

        return $container;
    }

    protected function addCommentFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMMENT, function (Container $container) {
            return new MerchantRegistrationRequestToCommentFacadeBridge($container->getLocator()->comment()->facade());
        });

        return $container;
    }

    protected function addUtilDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATE_TIME, function (Container $container) {
            return new MerchantRegistrationRequestGuiToUtilDateTimeServiceBridge($container->getLocator()->utilDateTime()->service());
        });

        return $container;
    }

    protected function addMerchantPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT, $container->factory(function () {
            return SpyMerchantQuery::create();
        }));

        return $container;
    }

    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new MerchantRegistrationRequestToMerchantFacadeBridge($container->getLocator()->merchant()->facade());
        });

        return $container;
    }

    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new MerchantRegistrationRequestToMerchantUserFacadeBridge($container->getLocator()->merchantUser()->facade());
        });

        return $container;
    }

    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new MerchantRegistrationRequestToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new MerchantRegistrationRequestToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }

    protected function addUtilUuidGeneratorService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_UUID_GENERATOR, function (Container $container) {
            return new MerchantRegistrationRequestToUtilUuidGeneratorServiceBridge($container->getLocator()->utilUuidGenerator()->service());
        });

        return $container;
    }
}
