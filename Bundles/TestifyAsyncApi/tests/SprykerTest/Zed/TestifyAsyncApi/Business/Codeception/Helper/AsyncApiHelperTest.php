<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TestifyAsyncApi\Business\Codeception\Helper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\TestifyAsyncApiBarTransfer;
use Generated\Shared\Transfer\TestifyAsyncApiFooTransfer;
use PHPUnit\Framework\ExpectationFailedException;
use SprykerTest\Zed\TestifyAsyncApi\TestifyAsyncApiBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group TestifyAsyncApi
 * @group Business
 * @group Codeception
 * @group Helper
 * @group AsyncApiHelperTest
 * Add your own group annotations below this line
 */
class AsyncApiHelperTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\TestifyAsyncApi\TestifyAsyncApiBusinessTester
     */
    protected TestifyAsyncApiBusinessTester $tester;

    /**
     * Will be set by the mocked message handler in case validation of required fields was fine.
     *
     * @var bool
     */
    protected bool $messageHandlerWasCalled = false;

    /**
     * @return void
     */
    public function testDiffRequiredFieldsThrowsExceptionWhenRequiredFieldsAreNotSet(): void
    {
        // Arrange
        $this->tester->setAsyncApi(codecept_data_dir('asyncapi/simple-valid-schema.yml'));

        $testifyAsyncApiFooTransfer = new TestifyAsyncApiFooTransfer();

        // Expect
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('The message "TestifyAsyncApiFoo" does not contain all required fields "foo, foo.bar". The following fields are missing "foo, foo.bar".');

        // Assert
        $this->tester->runMessageReceiveTest($testifyAsyncApiFooTransfer, 'foo-events', [$this, 'handleMessage']);
    }

    /**
     * @return void
     */
    public function testDiffRequiredFieldsThrowsExceptionWhenRequiredPropertyIsEmpty(): void
    {
        // Arrange
        $this->tester->setAsyncApi(codecept_data_dir('asyncapi/simple-valid-schema.yml'));

        $testifyAsyncApiFooTransfer = new TestifyAsyncApiFooTransfer();
        $testifyAsyncApiFooTransfer->setFoo(new TestifyAsyncApiBarTransfer());

        // Expect
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('The message "TestifyAsyncApiFoo" does not contain all required fields "foo, foo.bar". The following fields are missing "foo, foo.bar".');

        // Assert
        $this->tester->runMessageReceiveTest($testifyAsyncApiFooTransfer, 'foo-events', [$this, 'handleMessage']);
    }

    /**
     * @return void
     */
    public function testDiffRequiredFieldsThrowsExceptionWhenInnerRequiredPropertyIsNotSet(): void
    {
        // Arrange
        $this->tester->setAsyncApi(codecept_data_dir('asyncapi/simple-valid-schema.yml'));

        $testifyAsyncApiBarTransfer = new TestifyAsyncApiBarTransfer();
        $testifyAsyncApiBarTransfer->setBaz('baz'); // Bar is not set but foo doesn't contain an empty array.

        $testifyAsyncApiFooTransfer = new TestifyAsyncApiFooTransfer();
        $testifyAsyncApiFooTransfer->setFoo($testifyAsyncApiBarTransfer);

        // Expect
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('The message "TestifyAsyncApiFoo" does not contain all required fields "foo, foo.bar". The following fields are missing "foo.bar".');

        // Assert
        $this->tester->runMessageReceiveTest($testifyAsyncApiFooTransfer, 'foo-events', [$this, 'handleMessage']);
    }

    /**
     * @return void
     */
    public function testDiffRequiredFieldsDoesNotThrowAnExceptionWhenAllRequiredFieldsAreSet(): void
    {
        // Arrange
        $this->tester->setAsyncApi(codecept_data_dir('asyncapi/simple-valid-schema.yml'));

        $testifyAsyncApiBarTransfer = new TestifyAsyncApiBarTransfer();
        $testifyAsyncApiBarTransfer->setBar('bar');

        $testifyAsyncApiFooTransfer = new TestifyAsyncApiFooTransfer();
        $testifyAsyncApiFooTransfer->setFoo($testifyAsyncApiBarTransfer);

        // Assert
        $this->tester->runMessageReceiveTest($testifyAsyncApiFooTransfer, 'foo-events', [$this, 'handleMessage']);

        $this->assertTrue($this->messageHandlerWasCalled);
    }

    /**
     * Helper method to get around the need of a configured MessageHandler
     *
     * @return iterable
     */
    public function handles(): iterable
    {
        yield TestifyAsyncApiFooTransfer::class => [$this, 'handleMessage'];
    }

    /**
     * Helper method to get around the need of a configured MessageHandler
     *
     * @return void
     */
    public function handleMessage(): void
    {
        $this->messageHandlerWasCalled = true;
    }
}
