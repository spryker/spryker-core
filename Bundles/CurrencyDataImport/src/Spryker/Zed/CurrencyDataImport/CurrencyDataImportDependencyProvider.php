<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CurrencyDataImport;

use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CurrencyDataImport\CurrencyDataImportConfig getConfig()
 */
class CurrencyDataImportDependencyProvider extends DataImportDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_CURRENCY = 'PROPEL_QUERY_CURRENCY';

    /**
     * @var string
     */
    public const PROPEL_QUERY_CURRENCY_STORE = 'PROPEL_QUERY_CURRENCY_STORE';

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

        $container = $this->addCurrencyPropelQuery($container);
        $container = $this->addCurrencyStorePropelQuery($container);
        $container = $this->addStorePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CURRENCY, $container->factory(function (): SpyCurrencyQuery {
            return SpyCurrencyQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CURRENCY_STORE, $container->factory(function (): SpyCurrencyStoreQuery {
            return SpyCurrencyStoreQuery::create();
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
