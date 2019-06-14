<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent;

use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\User\Persistence\SpyUserQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Agent\AgentConfig getConfig()
 */
class AgentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_USER = 'PROPEL_QUERY_USER';
    public const PROPEL_QUERY_CUSTOMER = 'PROPEL_QUERY_CUSTOMER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = $this->addUserPropelQuery($container);
        $container = $this->addCustomerPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_USER] = function (): SpyUserQuery {
            return SpyUserQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CUSTOMER] = function (): SpyCustomerQuery {
            return SpyCustomerQuery::create();
        };

        return $container;
    }
}
