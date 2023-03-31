<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\LocaleDataImport;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\LocaleDataImport\LocaleDataImportConfig getConfig()
 */
class LocaleDataImportDependencyProvider extends DataImportDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_LOCALE = 'PROPEL_QUERY_LOCALE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_LOCALE_STORE = 'PROPEL_QUERY_LOCALE_STORE';

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

        $container = $this->addLocalePropelQuery($container);
        $container = $this->addLocaleStorePropelQuery($container);
        $container = $this->addStorePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocalePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_LOCALE, $container->factory(function (): SpyLocaleQuery {
            return SpyLocaleQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_LOCALE_STORE, $container->factory(function (): SpyLocaleStoreQuery {
            return SpyLocaleStoreQuery::create();
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
