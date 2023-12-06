<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TestifyAsyncApi\Business\Codeception\Helper;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\TestifyAsyncApiBarTransfer;
use Generated\Shared\Transfer\TestifyAsyncApiFooTransfer;
use Generated\Shared\Transfer\TestifyAsyncApiItemTransfer;
use Generated\Shared\Transfer\TestifyAsyncApiNestedTransfer;
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
     * @var string
     */
    private const EXPECTED_EXCEPTION_MESSAGE_MISSING_ALL_REQUIRED_FIELDS = 'The message "TestifyAsyncApiFoo" does not contain all required properties "foo, foo.bar, foo.items, foo.nested, foo.nested.nestedPropA, foo.items.propA". The following properties are missing "foo, foo.bar, foo.items, foo.nested, foo.nested.nestedPropA".';

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
        $this->expectExceptionMessage(static::EXPECTED_EXCEPTION_MESSAGE_MISSING_ALL_REQUIRED_FIELDS);

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
        $this->expectExceptionMessage(static::EXPECTED_EXCEPTION_MESSAGE_MISSING_ALL_REQUIRED_FIELDS);

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

        $testifyAsyncApiItemTransfer = (new TestifyAsyncApiItemTransfer())->setPropA('propA');
        $testifyAsyncApiBarTransfer->addItem($testifyAsyncApiItemTransfer);

        $testifyAsyncApiNested = (new TestifyAsyncApiNestedTransfer())->setNestedPropA('nestedPropA');
        $testifyAsyncApiBarTransfer->setNested($testifyAsyncApiNested);

        $testifyAsyncApiFooTransfer = new TestifyAsyncApiFooTransfer();
        $testifyAsyncApiFooTransfer->setFoo($testifyAsyncApiBarTransfer);

        // Expect
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('The message "TestifyAsyncApiFoo" does not contain all required properties "foo, foo.bar, foo.items, foo.nested, foo.nested.nestedPropA, foo.items.propA". The following properties are missing "foo.bar".');

        // Assert
        $this->tester->runMessageReceiveTest($testifyAsyncApiFooTransfer, 'foo-events', [$this, 'handleMessage']);
    }

    /**
     * @return void
     */
    public function testDiffRequiredFieldsThrowsExceptionWhenNestedRequiredPropertyIsNotSet(): void
    {
        // Arrange
        $this->tester->setAsyncApi(codecept_data_dir('asyncapi/simple-valid-schema.yml'));

        $testifyAsyncApiBarTransfer = new TestifyAsyncApiBarTransfer();
        $testifyAsyncApiBarTransfer->setBar('bar');

        $testifyAsyncApiItemTransfer = (new TestifyAsyncApiItemTransfer())->setPropA('propA');
        $testifyAsyncApiBarTransfer->addItem($testifyAsyncApiItemTransfer);

        $testifyAsyncApiFooTransfer = new TestifyAsyncApiFooTransfer();
        $testifyAsyncApiFooTransfer->setFoo($testifyAsyncApiBarTransfer);

        $testifyAsyncApiNested = (new TestifyAsyncApiNestedTransfer())->setNestedPropB('nestedPropB');
        $testifyAsyncApiBarTransfer->setNested($testifyAsyncApiNested);

        // Expect
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('The message "TestifyAsyncApiFoo" does not contain all required properties "foo, foo.bar, foo.items, foo.nested, foo.nested.nestedPropA, foo.items.propA". The following properties are missing "foo.nested.nestedPropA".');

        // Assert
        $this->tester->runMessageReceiveTest($testifyAsyncApiFooTransfer, 'foo-events', [$this, 'handleMessage']);
    }

    /**
     * @return void
     */
    public function testDiffRequiredFieldsThrowsExceptionWhenArrayFieldsHaveRequiredPropertyNotSet(): void
    {
        // Arrange
        $this->tester->setAsyncApi(codecept_data_dir('asyncapi/simple-valid-schema.yml'));

        $testifyAsyncApiItemTransfer1 = (new TestifyAsyncApiItemTransfer())->setPropA('someProp');
        $testifyAsyncApiItemTransfer2 = (new TestifyAsyncApiItemTransfer());

        $testifyAsyncApiBarTransfer = new TestifyAsyncApiBarTransfer();
        $testifyAsyncApiBarTransfer->setBar('bar');
        $testifyAsyncApiBarTransfer->addItem($testifyAsyncApiItemTransfer1);
        $testifyAsyncApiBarTransfer->addItem($testifyAsyncApiItemTransfer2);
        $testifyAsyncApiBarTransfer->addItem($testifyAsyncApiItemTransfer1);
        $testifyAsyncApiBarTransfer->addItem($testifyAsyncApiItemTransfer2);

        $testifyAsyncApiNested = (new TestifyAsyncApiNestedTransfer())->setNestedPropA('nestedPropA');
        $testifyAsyncApiBarTransfer->setNested($testifyAsyncApiNested);

        $testifyAsyncApiFooTransfer = new TestifyAsyncApiFooTransfer();
        $testifyAsyncApiFooTransfer->setFoo($testifyAsyncApiBarTransfer);

        // Expect
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('The message "TestifyAsyncApiFoo" does not contain all required properties "foo, foo.bar, foo.items, foo.nested, foo.nested.nestedPropA, foo.items.propA". The following properties are missing "foo.items[1].propA, foo.items[3].propA".');

        // Act
        $this->tester->runMessageReceiveTest($testifyAsyncApiFooTransfer, 'foo-events', [$this, 'handleMessage']);
    }

    /**
     * @return void
     */
    public function testDiffRequiredFieldsDoesNotThrowAnExceptionWhenAllRequiredFieldsAreSet(): void
    {
        // Arrange
        $this->tester->setAsyncApi(codecept_data_dir('asyncapi/simple-valid-schema.yml'));

        $testifyAsyncApiItemTransfer = (new TestifyAsyncApiItemTransfer())->setPropA('someProp');

        $testifyAsyncApiBarTransfer = new TestifyAsyncApiBarTransfer();
        $testifyAsyncApiBarTransfer->setBar('bar');
        $testifyAsyncApiBarTransfer->addItem($testifyAsyncApiItemTransfer);

        $testifyAsyncApiNested = (new TestifyAsyncApiNestedTransfer())->setNestedPropA('nestedPropA');
        $testifyAsyncApiBarTransfer->setNested($testifyAsyncApiNested);

        $testifyAsyncApiFooTransfer = new TestifyAsyncApiFooTransfer();
        $testifyAsyncApiFooTransfer->setFoo($testifyAsyncApiBarTransfer);

        // Assert
        $this->tester->runMessageReceiveTest($testifyAsyncApiFooTransfer, 'foo-events', [$this, 'handleMessage']);

        $this->assertTrue($this->messageHandlerWasCalled);
    }

    /**
     * @return void
     */
    public function testDiffRequiredFieldsThrowsExceptionWhenArrayFieldsHaveRequiredPropertyReset(): void
    {
        // Arrange
        $this->tester->setAsyncApi(codecept_data_dir('asyncapi/simple-valid-schema.yml'));

        $testifyAsyncApiItemTransfer = (new TestifyAsyncApiItemTransfer())->setPropA('someProp');

        $testifyAsyncApiBarTransfer = new TestifyAsyncApiBarTransfer();
        $testifyAsyncApiBarTransfer->setBar('bar');
        $testifyAsyncApiBarTransfer->addItem($testifyAsyncApiItemTransfer);

        $testifyAsyncApiNested = (new TestifyAsyncApiNestedTransfer())->setNestedPropA('nestedPropA');
        $testifyAsyncApiBarTransfer->setNested($testifyAsyncApiNested);

        $testifyAsyncApiFooTransfer = new TestifyAsyncApiFooTransfer();
        $testifyAsyncApiFooTransfer->setFoo($testifyAsyncApiBarTransfer);

        // Reset Item.propA to null to trigger the exception.
        // Similar to setting the property with some value and later on removing it.
        $testifyAsyncApiFooTransfer->getFoo()->getItems()->offsetGet(0)->setPropA(null);

        // Expect
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage('The message "TestifyAsyncApiFoo" does not contain all required properties "foo, foo.bar, foo.items, foo.nested, foo.nested.nestedPropA, foo.items.propA". The following properties are missing "foo.items[0].propA".');

        // Act
        $this->tester->runMessageReceiveTest($testifyAsyncApiFooTransfer, 'foo-events', [$this, 'handleMessage']);
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
