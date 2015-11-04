<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Persistence\Propel;

use Orm\Zed\Oms\Persistence\Base\SpyOmsTransitionLog as BaseSpyOmsTransitionLog;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'spy_oms_transition_log' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 */
abstract class AbstractSpyOmsTransitionLog extends BaseSpyOmsTransitionLog
{

    /**
     * @param ConnectionInterface|null $con
     *
     * @return bool
     */
    public function preSave(ConnectionInterface $con = null) {
        if ($this->getIsError() !== null || $this->getEvent() !== null) {
            return true;
        }

        if ($this->getCommand() !== null || $this->getCondition() !== null) {
            return true;
        }

        return false;
    }

}
