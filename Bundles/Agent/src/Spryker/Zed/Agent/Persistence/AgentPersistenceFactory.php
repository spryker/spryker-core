<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Persistence;

use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\User\Persistence\SpyUserQuery;
use Spryker\Zed\Agent\AgentDependencyProvider;
use Spryker\Zed\Agent\Persistence\Propel\Mapper\AgentMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Agent\AgentConfig getConfig()
 * @method \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface getRepository()
 */
class AgentPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function getCustomerQuery(): SpyCustomerQuery
    {
        return $this->getProvidedDependency(AgentDependencyProvider::PROPEL_QUERY_CUSTOMER);
    }

    /**
     * @return \Orm\Zed\User\Persistence\SpyUserQuery
     */
    public function getUserQuery(): SpyUserQuery
    {
        return $this->getProvidedDependency(AgentDependencyProvider::PROPEL_QUERY_USER);
    }

    /**
     * @return \Spryker\Zed\Agent\Persistence\Propel\Mapper\AgentMapper
     */
    public function createAgentMapper(): AgentMapper
    {
        return new AgentMapper();
    }
}
