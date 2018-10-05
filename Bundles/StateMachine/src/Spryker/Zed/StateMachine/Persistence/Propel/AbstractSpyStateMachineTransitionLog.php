<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Persistence\Propel;

use Orm\Zed\StateMachine\Persistence\Base\SpyStateMachineTransitionLog as BaseSpyStateMachineTransitionLog;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'spy_StateMachine_transition_log' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements. This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyStateMachineTransitionLog extends BaseSpyStateMachineTransitionLog
{
    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $con
     *
     * @return bool
     */
    public function preSave(?ConnectionInterface $con = null)
    {
        if ($this->getIsError() === null
            && $this->getEvent() === null
            && $this->getCommand() === null
            && $this->getCondition() === null
        ) {
            return false;
        }

        return true;
    }
}
