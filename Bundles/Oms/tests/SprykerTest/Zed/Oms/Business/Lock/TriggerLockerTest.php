<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Oms\Business\Lock;

use Codeception\Test\Unit;
use DateInterval;
use DateTime;
use Orm\Zed\Oms\Persistence\SpyOmsStateMachineLock;
use Spryker\Zed\Oms\Business\Exception\LockException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Oms
 * @group Business
 * @group Lock
 * @group TriggerLockerTest
 * Add your own group annotations below this line
 */
class TriggerLockerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Oms\OmsBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAcquireWithSingleIdentifierWillAcquireSingleLockEntry(): void
    {
        $triggerLocker = $this->tester->createTriggerLocker();
        $triggerLocker->acquire('1');

        $this->tester->assertLockedEntityCount(1);
    }

    /**
     * @return void
     */
    public function testAcquireWithAnArrayOfIdentifierWillAcquireMultipleLockEntries(): void
    {
        $triggerLocker = $this->tester->createTriggerLocker();
        $triggerLocker->acquire([1, 2]);

        $this->tester->assertLockedEntityCount(2);
    }

    /**
     * @return void
     */
    public function testAcquireWithSingleIdentifierThrowsExceptionWhenEntryIsAlreadyLocked(): void
    {
        $triggerLocker = $this->tester->createTriggerLocker();
        $triggerLocker->acquire('1');

        $this->expectException(LockException::class);
        $triggerLocker->acquire('1');
    }

    /**
     * @return void
     */
    public function testAcquireWithAnArrayOfIdentifierThrowsExceptionWhenAtLEastOneEntryIsAlreadyLocked(): void
    {
        $triggerLocker = $this->tester->createTriggerLocker();
        $triggerLocker->acquire([1, 2, 3]);

        $this->expectException(LockException::class);
        $triggerLocker->acquire([2]);
    }

    /**
     * @return void
     */
    public function testReleaseSingleIdentifier(): void
    {
        $triggerLocker = $this->tester->createTriggerLocker();
        $triggerLocker->acquire('1');
        $this->tester->assertLockedEntityCount(1);
        $triggerLocker->release('1');
        $this->tester->assertLockedEntityCount(0);
    }

    /**
     * @return void
     */
    public function testReleaseMultipleIdentifier(): void
    {
        $triggerLocker = $this->tester->createTriggerLocker();
        $triggerLocker->acquire([1, 2]);
        $this->tester->assertLockedEntityCount(2);
        $triggerLocker->release([1, 2]);
        $this->tester->assertLockedEntityCount(0);
    }

    /**
     * @return void
     */
    public function testClearLocksWillRemoveAllLockEntries(): void
    {
        $triggerLocker = $this->tester->createTriggerLocker();
        $dateTime = new DateTime();
        $dateTime->sub(DateInterval::createFromDateString('1 day'));
        $omsStateMachineLockEntity = new SpyOmsStateMachineLock();
        $omsStateMachineLockEntity
            ->setIdentifier('1')
            ->setExpires($dateTime)
            ->save();
        $this->tester->assertLockedEntityCount(1);
        $triggerLocker->clearLocks();
        $this->tester->assertLockedEntityCount(0);
    }
}
