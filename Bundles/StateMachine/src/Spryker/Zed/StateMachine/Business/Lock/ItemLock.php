<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Lock;

use DateInterval;
use DateTime;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineLock;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\StateMachine\Business\Exception\LockException;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;
use Spryker\Zed\StateMachine\StateMachineConfig;

class ItemLock implements ItemLockInterface
{
    /**
     * @var \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\StateMachine\StateMachineConfig
     */
    protected $stateMachineConfig;

    /**
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\StateMachine\StateMachineConfig $stateMachineConfig
     */
    public function __construct(
        StateMachineQueryContainerInterface $queryContainer,
        StateMachineConfig $stateMachineConfig
    ) {
        $this->queryContainer = $queryContainer;
        $this->stateMachineConfig = $stateMachineConfig;
    }

    /**
     * @param string $identifier
     *
     * @throws \Spryker\Zed\StateMachine\Business\Exception\LockException
     *
     * @return bool
     */
    public function acquire($identifier)
    {
        $stateMachineLockEntity = $this->createStateMachineLockEntity();

        $stateMachineLockEntity->setIdentifier($identifier);
        $expirationDate = $this->createExpirationDate();
        $stateMachineLockEntity->setExpires($expirationDate);

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
            $this->stateMachineConfig->getStateMachineItemLockExpirationInterval()
        );
        $expirationDate = new DateTime();
        $expirationDate->add($dateInterval);

        return $expirationDate;
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineLock
     */
    protected function createStateMachineLockEntity()
    {
        return new SpyStateMachineLock();
    }
}
