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
    /**
     * @var string
     */
    public const PROPEL_QUERY_USER = 'PROPEL_QUERY_USER';

    /**
     * @var string
     */
    public const PROPEL_QUERY_CUSTOMER = 'PROPEL_QUERY_CUSTOMER';

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = $this->addUserPropelQuery($container);
        $container = $this->addCustomerPropelQuery($container);

        return $container;
    }

    protected function addUserPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_USER, $container->factory(function (): SpyUserQuery {
            return SpyUserQuery::create();
        }));

        return $container;
    }

    protected function addCustomerPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CUSTOMER, $container->factory(function (): SpyCustomerQuery {
            return SpyCustomerQuery::create();
        }));

        return $container;
    }
}
