<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Lock;

use DateInterval;
use DateTime;
use Exception;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock;
use Propel\Runtime\Exception\PropelException;
use RuntimeException;
use Spryker\Zed\Oms\Business\Exception\LockException;
use Spryker\Zed\Oms\OmsConfig;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

class TriggerLocker implements LockerInterface
{
    use ActiveRecordBatchProcessorTrait;

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
     * @param array<string>|string $identifiers
     * @param string|null $details
     *
     * @throws \Spryker\Zed\Oms\Business\Exception\LockException
     *
     * @return bool
     */
    public function acquire($identifiers, $details = null)
    {
        if (is_array($identifiers)) {
            return $this->acquireBatch($identifiers, $details);
        }

        $stateMachineLockEntity = $this->createStateMachineLockEntity();

        $stateMachineLockEntity->setIdentifier($identifiers);
        $stateMachineLockEntity->setExpires($this->createExpirationDate());
        $stateMachineLockEntity->setDetails($details);

        try {
            $affectedRows = $stateMachineLockEntity->save();
        } catch (PropelException $exception) {
            throw new LockException(
                sprintf(
                    'State machine trigger is locked. Propel exception: %s',
                    $exception->getMessage(),
                ),
                $exception->getCode(),
                $exception,
            );
        }

        return $affectedRows > 0;
    }

    /**
     * @param array<string>|string $identifiers
     *
     * @return void
     */
    public function release($identifiers)
    {
        // To avoid deadlocks in concurrent requests removing is done one by one,
        // see https://dev.mysql.com/doc/refman/8.4/en/innodb-locking.html#innodb-gap-locks
        foreach ((array)$identifiers as $identifier) {
            $this->queryContainer
                ->queryLockItemsByIdentifier($identifier)
                ->delete();
        }
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
     * @param array $identifiers
     * @param string|null $details
     *
     * @throws \Spryker\Zed\Oms\Business\Exception\LockException
     *
     * @return bool
     */
    protected function acquireBatch(array $identifiers, ?string $details = null): bool
    {
        $expirationDate = $this->createExpirationDate();

        foreach ($identifiers as $identifier) {
            $stateMachineLockEntity = $this->createStateMachineLockEntity();

            $stateMachineLockEntity->setIdentifier($identifier);
            $stateMachineLockEntity->setExpires($expirationDate);
            $stateMachineLockEntity->setDetails($details);

            $this->persist($stateMachineLockEntity);
        }

        try {
            $isCommitSuccess = $this->commitIdentical();
        } catch (Exception $exception) {
            throw new LockException(
                sprintf(
                    'State machine trigger is locked. Propel exception: %s',
                    $exception->getMessage(),
                ),
                $exception->getCode(),
                $exception,
            );
        }

        return $isCommitSuccess;
    }

    /**
     * @throws \RuntimeException
     *
     * @return \DateTime
     */
    protected function createExpirationDate()
    {
        $dateInterval = DateInterval::createFromDateString(
            $this->omsConfig->getStateMachineLockerTimeoutInterval(),
        );
        if ($dateInterval === false) {
            throw new RuntimeException('Cannot create a DateInterval from `OmsConfig::getStateMachineLockerTimeoutInterval()`');
        }

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
