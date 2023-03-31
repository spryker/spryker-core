<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CountryDataImport;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Orm\Zed\Country\Persistence\SpyCountryStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CountryDataImport\CountryDataImportConfig getConfig()
 */
class CountryDataImportDependencyProvider extends DataImportDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_COUNTRY = 'PROPEL_QUERY_COUNTRY';

    /**
     * @var string
     */
    public const PROPEL_QUERY_COUNTRY_STORE = 'PROPEL_QUERY_COUNTRY_STORE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCountryPropelQuery($container);
        $container = $this->addCountryStorePropelQuery($container);
        $container = $this->addStorePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COUNTRY, $container->factory(function (): SpyCountryQuery {
            return SpyCountryQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COUNTRY_STORE, $container->factory(function (): SpyCountryStoreQuery {
            return SpyCountryStoreQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STORE, $container->factory(function (): SpyStoreQuery {
            return SpyStoreQuery::create();
        }));

        return $container;
    }
}
