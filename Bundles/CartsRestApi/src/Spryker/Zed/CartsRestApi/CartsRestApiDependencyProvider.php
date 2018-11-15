<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi;

use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class CartsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_QUOTE = 'PROPEL_QUERY_QUOTE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addQuotePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuotePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_QUOTE] = function () {
            return SpyQuoteQuery::create();
        };

        return $container;
    }
}
