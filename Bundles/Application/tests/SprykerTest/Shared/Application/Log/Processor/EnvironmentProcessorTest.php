<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Log\Processor;

use Codeception\Test\Unit;
use Spryker\Shared\Application\Log\Processor\EnvironmentProcessor;
use SprykerTest\Shared\Application\ApplicationSharedTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Application
 * @group Log
 * @group Processor
 * @group EnvironmentProcessorTest
 * Add your own group annotations below this line
 */
class EnvironmentProcessorTest extends Unit
{
    /**
     * @var \SprykerTest\Shared\Application\ApplicationSharedTester
     */
    protected ApplicationSharedTester $tester;

    /**
     * @return void
     */
    public function testInvokeShouldAddEnvironmentInformationToRecordsExtra(): void
    {
        $processor = new EnvironmentProcessor();
        $result = $processor([$this->tester::EXTRA]);

        $this->assertArrayHasKey(EnvironmentProcessor::EXTRA, $result['extra']);
    }

    /**
     * @return void
     */
    public function testInvokeAddsStoreAndLocaleDataWhenDynamicStoreEnabled(): void
    {
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('Test is valid for Dynamic Store on-mode only.');
        }

        // Arrange
        $processor = new EnvironmentProcessor();

        // Act
        $result = $processor([$this->tester::EXTRA]);

        // Assert
        $this->assertArrayNotHasKey(EnvironmentProcessor::STORE, $result[$this->tester::EXTRA][EnvironmentProcessor::EXTRA]);
        $this->assertArrayNotHasKey(EnvironmentProcessor::LOCALE, $result[$this->tester::EXTRA][EnvironmentProcessor::EXTRA]);
    }

    /**
     * @return void
     */
    public function testInvokeAddsStoreAndLocaleDataWhenDynamicStoreDisabled(): void
    {
        if ($this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('Test is valid for disabled Dynamic Store on-mode only.');
        }
        // Arrange
        $processor = new EnvironmentProcessor();

        // Act
        $result = $processor([$this->tester::EXTRA]);

        // Assert
        $this->assertArrayHasKey(EnvironmentProcessor::STORE, $result[$this->tester::EXTRA][EnvironmentProcessor::EXTRA]);
        $this->assertArrayHasKey(EnvironmentProcessor::LOCALE, $result[$this->tester::EXTRA][EnvironmentProcessor::EXTRA]);
        $this->assertIsString($result[$this->tester::EXTRA][EnvironmentProcessor::EXTRA][EnvironmentProcessor::STORE]);
        $this->assertIsString($result[$this->tester::EXTRA][EnvironmentProcessor::EXTRA][EnvironmentProcessor::LOCALE]);
    }
}
