<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Comment\Business\CommentFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\DataBuilder\CommentRequestBuilder;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Comment
 * @group Business
 * @group CommentFacade
 * @group Facade
 * @group CommentFacadeUpdateCommentTest
 * Add your own group annotations below this line
 */
class CommentFacadeUpdateCommentTest extends Unit
{
    protected const FAKE_COMMENT_MESSAGE = 'FAKE_COMMENT_MESSAGE';
    protected const FAKE_COMMENT_UUID = 'FAKE_COMMENT_UUID';

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentWriter::COMMENT_MESSAGE_MAX_LENGTH
     */
    protected const COMMENT_MESSAGE_MAX_LENGTH = 5000;

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentWriter::GLOSSARY_KEY_COMMENT_NOT_FOUND
     */
    protected const GLOSSARY_KEY_COMMENT_NOT_FOUND = 'comment.validation.error.comment_not_found';

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentWriter::GLOSSARY_KEY_COMMENT_ACCESS_DENIED
     */
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentWriter::GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH
     */
    protected const GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH = 'comment.validation.error.invalid_message_length';

    /**
     * @var \SprykerTest\Zed\Comment\CommentBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->customerTransfer = $this->tester->haveCustomer();
    }

    /**
     * @return void
     */
    public function testUpdateCommentUpdatesExistingComment(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer->setMessage(static::FAKE_COMMENT_MESSAGE);

        $commentRequestTransfer->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->updateComment($commentRequestTransfer);
        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($commentRequestTransfer);

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadTransfer->getComments()->offsetGet(0);

        // Assert
        $this->assertTrue($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertEquals($commentTransfer->getMessage(), $storedCommentTransfer->getMessage());
        $this->assertTrue($storedCommentTransfer->getIsUpdated());
    }

    /**
     * @return void
     */
    public function testUpdateCommentThrowsExceptionWithEmptyComment(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $this->tester->createComment($commentRequestTransfer);
        $commentRequestTransfer->setComment(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateComment($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateCommentThrowsExceptionWithEmptyCommentMessage(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $this->tester->createComment($commentRequestTransfer);
        $commentTransfer->setMessage(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateComment($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateCommentThrowsExceptionWithEmptyCustomer(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $this->tester->createComment($commentRequestTransfer);
        $commentTransfer->setCustomer(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateComment($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateCommentThrowsExceptionWithEmptyIdCustomer(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $this->tester->createComment($commentRequestTransfer);
        $commentTransfer->getCustomer()->setIdCustomer(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateComment($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateCommentWithWrongUuidComment(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $this->tester->createComment($commentRequestTransfer);
        $commentTransfer->setUuid(static::FAKE_COMMENT_UUID);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->updateComment($commentRequestTransfer);

        // Assert
        $this->assertFalse($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_COMMENT_NOT_FOUND,
            $commentThreadResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testUpdateCommentWithWrongCustomer(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $this->tester->createComment($commentRequestTransfer);
        $commentTransfer->setCustomer($this->tester->haveCustomer());

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->updateComment($commentRequestTransfer);

        // Assert
        $this->assertFalse($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED,
            $commentThreadResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testUpdateCommentTryToUpdateCommentWithEmptyMessage(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer->setMessage('');

        $commentRequestTransfer->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->updateComment($commentRequestTransfer);

        // Assert
        $this->assertFalse($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH,
            $commentThreadResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testUpdateCommentTryToUpdateCommentWithExtendedLength(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer->setMessage($this->tester->generateRandomString(static::COMMENT_MESSAGE_MAX_LENGTH + 1));

        $commentRequestTransfer->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->updateComment($commentRequestTransfer);

        // Assert
        $this->assertFalse($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH,
            $commentThreadResponseTransfer->getMessages()[0]->getValue()
        );
    }
}
