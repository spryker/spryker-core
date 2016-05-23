<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Lock;

use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

class TriggerLocker implements LockerInterface
{

    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Oms\OmsConfig
     */
    protected $omsConfig;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Oms\OmsConfig $omsConfig
     */
    public function __construct(
        OmsQueryContainerInterface $queryContainer,
        OmsConfig $omsConfig
    ) {
        $this->queryContainer = $queryContainer;
        $this->omsConfig = $omsConfig;
    }

    /**
     * @param int $identifier
     *
     * @return bool
     */
    public function acquire($identifier)
    {
        $stateMachineLockEntity = $this->createStateMachineLockEntity();

        $stateMachineLockEntity->setIdentifier($identifier);
        $expirationDate = $this->createExpirationDate();
        $stateMachineLockEntity->setExpires($expirationDate);
        $affectedRows = $stateMachineLockEntity->save();

        return $affectedRows > 0;
    }

    /**
     * @param int $identifier
     *
     * @return bool
     */
    public function isLocked($identifier)
    {
        $locked = $this->queryContainer->queryLockedItemsByIdentifierAndExpirationDate(
            $identifier,
            new \DateTime('now')
        )->count();

        return $locked > 0;
    }

    /**
     * @param int $identifier
     *
     * @return void
     */
    public function release($identifier)
    {
        $this->queryContainer
            ->queryLockItemsByIdentifier($identifier)
            ->delete();
    }

    /**
     * @return void
     */
    public function clearLocks()
    {
        $this->queryContainer
            ->queryLockedItemsByExpirationDate(new \DateTime('now'))
            ->delete();
    }

    /**
     * @return \DateTime
     */
    protected function createExpirationDate()
    {
        $dateInterval = \DateInterval::createFromDateString(
            $this->omsConfig->getStateMachineLockerTimeoutInterval()
        );
        $expirationDate = new \DateTime();
        $expirationDate->add($dateInterval);

        return $expirationDate;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock
     */
    protected function createStateMachineLockEntity()
    {
        return new SpyOmsStateMachineLock();
    }

}
