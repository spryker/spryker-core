<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui;

use Orm\Zed\Country\Persistence\Base\SpyCountryStoreQuery;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Zed\CountryGui\Dependency\Facade\CountryGuiToCountryFacadeBridge;
use Spryker\Zed\CountryGui\Dependency\Facade\CountryGuiToStoreFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CountryGui\CountryGuiConfig getConfig()
 */
class CountryGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_COUNTRY = 'FACADE_COUNTRY';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_COUNTRY_STORE = 'PROPEL_QUERY_COUNTRY_STORE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_COUNTRY = 'PROPEL_QUERY_COUNTRY';

    /**
     * @var string
     */
    public const RENDERER = 'RENDERER';

    /**
     * @uses \Spryker\Zed\Twig\Communication\Plugin\Application\TwigApplicationPlugin::SERVICE_TWIG
     *
     * @var string
     */
    protected const SERVICE_TWIG = 'twig';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addCountryFacade($container);
        $container = $this->addCountryStorePropelQuery($container);
        $container = $this->addCountryPropelQuery($container);
        $container = $this->addRenderer($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryFacade(Container $container): Container
    {
        $container->set(static::FACADE_COUNTRY, function (Container $container) {
            return new CountryGuiToCountryFacadeBridge(
                $container->getLocator()->country()->facade(),
            );
        });

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
            return new CountryGuiToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COUNTRY_STORE, $container->factory(function () {
            return SpyCountryStoreQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COUNTRY, $container->factory(function () {
            return SpyCountryQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRenderer(Container $container): Container
    {
        $container->set(static::RENDERER, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_TWIG);
        });

        return $container;
    }
}
