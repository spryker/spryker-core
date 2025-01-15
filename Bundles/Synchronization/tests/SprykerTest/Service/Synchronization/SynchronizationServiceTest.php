<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Synchronization\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use SprykerTest\Service\Synchronization\SynchronizationServiceTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Synchronization
 * @group Business
 * @group SynchronizationServiceTest
 * Add your own group annotations below this line
 */
class SynchronizationServiceTest extends Unit
{
    /**
     * @var \SprykerTest\Service\Synchronization\SynchronizationServiceTester
     */
    protected SynchronizationServiceTester $tester;

    /**
     * @return void
     */
    public function testGenerateNormalaizedWithooutColonInKeyWhereIsSingleKeyFormatNormalizedIsTrue(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('isSingleKeyFormatNormalized', true);

        // Act
        $key = $this->tester->getService()
            ->getStorageKeyBuilder(SynchronizationServiceTester::RESOURCE)
            ->generateKey((new SynchronizationDataTransfer()));

        // Assert
        $this->assertSame(SynchronizationServiceTester::RESOURCE, $key);
    }

    /**
     * @return void
     */
    public function testGenerateWithColonInKeyWhereIsSingleKeyFormatNormalizedIsFalse(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('isSingleKeyFormatNormalized', false);

        // Act
        $key = $this->tester->getService()
            ->getStorageKeyBuilder(SynchronizationServiceTester::RESOURCE)
            ->generateKey((new SynchronizationDataTransfer()));

        // Assert
        $this->assertSame(SynchronizationServiceTester::RESOURCE . ':', $key);
    }
}
