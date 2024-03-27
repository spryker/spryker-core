<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Comment\Business\CommentFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\DataBuilder\CommentRequestBuilder;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\Comment\Communication\Plugin\Comment\CustomerCommentAuthorValidationStrategyPlugin;
use SprykerTest\Zed\Comment\CommentBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Comment
 * @group Business
 * @group CommentFacade
 * @group Facade
 * @group CommentFacadeValidateCommentAuthorTest
 * Add your own group annotations below this line
 */
class CommentFacadeValidateCommentAuthorTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Comment\CommentDependencyProvider::PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY
     *
     * @var string
     */
    protected const PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY = 'PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY';

    /**
     * @uses \Spryker\Zed\Comment\Business\Validator\CustomerCommentValidator::GLOSSARY_KEY_COMMENT_ACCESS_DENIED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @uses \Spryker\Zed\Comment\Business\Validator\CustomerCommentValidator::GLOSSARY_KEY_CUSTOMER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_NOT_FOUND = 'comment.validation.error.customer_not_found';

    /**
     * @var \SprykerTest\Zed\Comment\CommentBusinessTester
     */
    protected CommentBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(static::PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY, [
            new CustomerCommentAuthorValidationStrategyPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorWhenIdCustomerOfExistingCustomerIsProvided(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $commentRequestTransfer = (new CommentRequestBuilder())
            ->withComment([CommentTransfer::CUSTOMER => $customerTransfer])
            ->build();

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);

        // Assert
        $this->assertTrue($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertEmpty($commentValidationResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorWhenIdCustomerIsProvidedAndCommentHasIdCommentSet(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();

        $commentTransfer = (new CommentBuilder([
            CommentTransfer::CUSTOMER => $customerTransfer,
        ]))->build();
        $commentThreadResponseTransfer = $this->tester->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
        ]);

        $commentTransfer = $commentThreadResponseTransfer->getCommentThreadOrFail()->getComments()->getIterator()->current();
        $commentRequestTransfer = (new CommentRequestBuilder())
            ->withComment($commentTransfer->toArray())
            ->build();

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);

        // Assert
        $this->assertTrue($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertEmpty($commentValidationResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenIdCustomerOfNonExistingCustomerIsProvided(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestBuilder())
            ->withComment([CommentTransfer::CUSTOMER => (new CustomerTransfer())->setIdCustomer(-1)])
            ->build();

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);

        // Assert
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $commentValidationResponseTransfer->getMessages());
        $this->assertSame(
            static::GLOSSARY_KEY_CUSTOMER_NOT_FOUND,
            $commentValidationResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenProvidedCustomerIsNotAnAuthorOfUpdatedComment(): void
    {
        // Arrange
        $customer1 = $this->tester->haveCustomer();
        $customer2 = $this->tester->haveCustomer();

        $commentTransfer = (new CommentBuilder([
            CommentTransfer::CUSTOMER => $customer1,
        ]))->build();
        $commentThreadResponseTransfer = $this->tester->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
        ]);

        $commentTransfer = $commentThreadResponseTransfer->getCommentThreadOrFail()->getComments()->getIterator()->current();
        $commentRequestTransfer = (new CommentRequestBuilder())
            ->withComment($commentTransfer->setCustomer($customer2)->toArray())
            ->build();

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);

        // Assert
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $commentValidationResponseTransfer->getMessages());
        $this->assertSame(
            static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED,
            $commentValidationResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @dataProvider throwsNullValueExceptionWhenRequiredPropertyIsNotSetDataProvider
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return void
     */
    public function testThrowsNullValueExceptionWhenRequiredPropertyIsNotSet(CommentRequestTransfer $commentRequestTransfer): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\CommentRequestTransfer>>
     */
    protected function throwsNullValueExceptionWhenRequiredPropertyIsNotSetDataProvider(): array
    {
        return [
            'CommentRequestTransfer.comment property is not set' => [
                (new CommentRequestBuilder([CommentRequestTransfer::COMMENT => null]))->build(),
            ],
            'CommentRequestTransfer.comment.customer property is not set' => [
                (new CommentRequestBuilder([
            CommentRequestTransfer::COMMENT => [
                    CommentTransfer::CUSTOMER => null,
                ]]))->build(),
            ],
            'CommentRequestTransfer.comment.customer.idCustomer property is not set' => [
                (new CommentRequestBuilder([
            CommentRequestTransfer::COMMENT => [
                    CommentTransfer::CUSTOMER => (new CustomerTransfer())->setIdCustomer(null),
                ]]))->build(),
            ],
        ];
    }
}
