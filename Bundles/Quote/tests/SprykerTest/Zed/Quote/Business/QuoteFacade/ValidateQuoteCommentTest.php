<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Bundles\Quote\tests\SprykerTest\Zed\Quote\Business\QuoteFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group Bundles
 * @group Quote
 * @group tests
 * @group SprykerTest
 * @group Zed
 * @group Quote
 * @group Business
 * @group QuoteFacade
 * @group ValidateQuoteCommentTest
 * Add your own group annotations below this line
 */
class ValidateQuoteCommentTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Quote\Business\Validator\QuoteCommentValidator::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @uses \Spryker\Zed\Quote\Business\Validator\QuoteCommentValidator::GLOSSARY_KEY_COMMENT_VALIDATION_CUSTOMER_NOT_SET
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_CUSTOMER_NOT_SET = 'comment.validation.error.customer_not_set';

    /**
     * @uses \Spryker\Zed\Quote\Business\Validator\QuoteCommentValidator::GLOSSARY_KEY_COMMENT_VALIDATION_CUSTOMER_NOT_SET
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_OWNER_NOT_SET = 'comment.validation.error.owner_not_set';

    /**
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'test-ref';

    /**
     * @var string
     */
    protected const TEST_OWNER_TYPE = 'test-owner-type';

    /**
     * @var \SprykerTest\Zed\Quote\QuoteBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWillReturnErrorIfCommentIsNotSet(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestTransfer());
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateQuoteComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError(
            $commentValidationResponseTransfer,
            static::GLOSSARY_KEY_COMMENT_VALIDATION_CUSTOMER_NOT_SET,
        );
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfCustomerIsNotSet(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestTransfer())->setComment((new CommentTransfer()));
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateQuoteComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError(
            $commentValidationResponseTransfer,
            static::GLOSSARY_KEY_COMMENT_VALIDATION_CUSTOMER_NOT_SET,
        );
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfCustomerReferenceIsNotSet(): void
    {
        // Arrange
        $commentTransfer = (new CommentTransfer())->setCustomer((new CustomerTransfer()));
        $commentRequestTransfer = (new CommentRequestTransfer())->setComment($commentTransfer);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateQuoteComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError(
            $commentValidationResponseTransfer,
            static::GLOSSARY_KEY_COMMENT_VALIDATION_CUSTOMER_NOT_SET,
        );
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfOwnerIsNotSet(): void
    {
        // Arrange
        $customerTransfer = (new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE);
        $commentTransfer = (new CommentTransfer())->setCustomer($customerTransfer);
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerType(static::TEST_OWNER_TYPE)
            ->setComment($commentTransfer);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateQuoteComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError(
            $commentValidationResponseTransfer,
            static::GLOSSARY_KEY_COMMENT_VALIDATION_OWNER_NOT_SET,
        );
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfQuoteDoesNotExist(): void
    {
        // Arrange
        $customerTransfer = (new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE);
        $commentTransfer = (new CommentTransfer())->setCustomer($customerTransfer);
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($commentTransfer)
            ->setOwnerId(0);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateQuoteComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError($commentValidationResponseTransfer, static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED);
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfCustomerIsNotAnOwnerOfTheQuote(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE,
            QuoteTransfer::CUSTOMER => [
                CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE,
            ],
        ]);
        $customerTransfer = (new CustomerTransfer())->setCustomerReference('random-ref');
        $commentTransfer = (new CommentTransfer())->setCustomer($customerTransfer);
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setComment($commentTransfer);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateQuoteComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError($commentValidationResponseTransfer, static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED);
    }

    /**
     * @return void
     */
    public function testWillReturnSuccessfulResponseWhenCustomerIsTheQuoteOwner(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE,
            QuoteTransfer::CUSTOMER => [
                CustomerTransfer::CUSTOMER_REFERENCE => static::TEST_CUSTOMER_REFERENCE,
            ],
        ]);
        $customerTransfer = (new CustomerTransfer())->setCustomerReference(static::TEST_CUSTOMER_REFERENCE);
        $commentTransfer = (new CommentTransfer())->setCustomer($customerTransfer);
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setComment($commentTransfer);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateQuoteComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertTrue($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $commentValidationResponseTransfer->getMessages());
    }

    /**
     * @param \Generated\Shared\Transfer\CommentValidationResponseTransfer $commentValidationResponseTransfer
     * @param string $expectedMessage
     *
     * @return void
     */
    protected function assertValidationError(CommentValidationResponseTransfer $commentValidationResponseTransfer, string $expectedMessage): void
    {
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $commentValidationResponseTransfer->getMessages());
        $this->assertSame($expectedMessage, $commentValidationResponseTransfer->getMessages()->offsetGet(0)->getValue());
    }
}
