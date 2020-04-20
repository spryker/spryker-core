<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Persistence;

use Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLogQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\StateMachine\Persistence\Propel\Mapper\StateMachineMapper;

/**
 * @method \Spryker\Zed\StateMachine\StateMachineConfig getConfig()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\StateMachine\Persistence\StateMachineRepositoryInterface getRepository()
 */
class StateMachinePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLogQuery
     */
    public function createStateMachineTransitionLogQuery()
    {
        return SpyStateMachineTransitionLogQuery::create();
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery
     */
    public function createStateMachineProcessQuery()
    {
        return SpyStateMachineProcessQuery::create();
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery
     */
    public function createStateMachineItemStateQuery()
    {
        return SpyStateMachineItemStateQuery::create();
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineEventTimeoutQuery
     */
    public function createStateMachineEventTimeoutQuery()
    {
        return SpyStateMachineEventTimeoutQuery::create();
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateHistoryQuery
     */
    public function createStateMachineItemStateHistoryQuery()
    {
        return SpyStateMachineItemStateHistoryQuery::create();
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineLockQuery
     */
    public function createStateMachineLockQuery()
    {
        return SpyStateMachineLockQuery::create();
    }

    /**
     * @return \Spryker\Zed\StateMachine\Persistence\Propel\Mapper\StateMachineMapper
     */
    public function createStateMachineMapper(): StateMachineMapper
    {
        return new StateMachineMapper();
    }
}
