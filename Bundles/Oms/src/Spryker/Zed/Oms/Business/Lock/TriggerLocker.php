<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Lock;

use DateInterval;
use DateTime;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Oms\Business\Exception\LockException;
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
     * Attempts to save a lock entity, and if it fails due to unique identifier constraint (entity already locked) -
     * throws a LockException
     *
     * @param string $identifier
     * @param string|null $details
     *
     * @throws \Spryker\Zed\Oms\Business\Exception\LockException
     *
     * @return bool
     */
    public function acquire($identifier, $details = null)
    {
        $stateMachineLockEntity = $this->createStateMachineLockEntity();

        $stateMachineLockEntity->setIdentifier($identifier);
        $stateMachineLockEntity->setExpires($this->createExpirationDate());
        $stateMachineLockEntity->setDetails($details);

        try {
            $affectedRows = $stateMachineLockEntity->save();
        } catch (PropelException $exception) {
            throw new LockException(
                sprintf(
                    'State machine trigger is locked. Propel exception: %s',
                    $exception->getMessage()
                ),
                $exception->getCode(),
                $exception
            );
        }

        return $affectedRows > 0;
    }

    /**
     * @param string $identifier
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
            ->queryLockedItemsByExpirationDate(new DateTime('now'))
            ->delete();
    }

    /**
     * @return \DateTime
     */
    protected function createExpirationDate()
    {
        $dateInterval = DateInterval::createFromDateString(
            $this->omsConfig->getStateMachineLockerTimeoutInterval()
        );
        $expirationDate = new DateTime();
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
