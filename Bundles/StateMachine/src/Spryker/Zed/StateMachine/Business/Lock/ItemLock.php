<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Lock;

use Orm\Zed\StateMachine\Persistence\SpyStateMachineLock;
use Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface;
use Spryker\Zed\StateMachine\StateMachineConfig;

class ItemLock implements ItemLockInterface
{

    /**
     * @var \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface
     */
    protected $stateMachineConfig;

    /**
     * @param \Spryker\Zed\StateMachine\Persistence\StateMachineQueryContainerInterface $queryContainer
     * @param StateMachineConfig $stateMachineConfig
     */
    public function __construct(
        StateMachineQueryContainerInterface $queryContainer,
        StateMachineConfig $stateMachineConfig
    ) {
        $this->queryContainer = $queryContainer;
        $this->stateMachineConfig = $stateMachineConfig;
    }

    /**
     * @param int $identifier
     *
     * @return bool
     */
    public function acquire($identifier)
    {
        $stateMachineLockEntity = new SpyStateMachineLock();
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
        $locked = $this->queryContainer->queryStateMachineLockedItemsByIdentifierAndExpirationDate(
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
            ->queryStateMachineLockItemsByIdentifier($identifier)
            ->delete();
    }

    /**
     * @return \DateTime
     */
    protected function createExpirationDate()
    {
        $dateInterval = \DateInterval::createFromDateString(
            $this->stateMachineConfig->getStateMachineItemLockExpirationInterval()
        );
        $expirationDate = new \DateTime();
        $expirationDate->add($dateInterval);

        return $expirationDate;
    }

}
