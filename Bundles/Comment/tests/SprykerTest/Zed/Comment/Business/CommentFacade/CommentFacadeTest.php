<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Comment\Business\CommentFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\DataBuilder\CommentFilterBuilder;
use Generated\Shared\DataBuilder\CommentRequestBuilder;
use Generated\Shared\DataBuilder\CommentTagBuilder;
use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTagRequestTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Comment\Business\CommentBusinessFactory;
use Spryker\Zed\Comment\Business\CommentFacadeInterface;
use Spryker\Zed\Comment\CommentConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Comment
 * @group Business
 * @group CommentFacade
 * @group Facade
 * @group CommentFacadeTest
 * Add your own group annotations below this line
 */
class CommentFacadeTest extends Unit
{
    protected const FAKE_COMMENT_MESSAGE = 'FAKE_COMMENT_MESSAGE';
    protected const FAKE_COMMENT_UUID = 'FAKE_COMMENT_UUID';

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentWriter::GLOSSARY_KEY_COMMENT_NOT_FOUND
     */
    protected const GLOSSARY_KEY_COMMENT_NOT_FOUND = 'comment.validation.error.comment_not_found';

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentWriter::GLOSSARY_KEY_COMMENT_ACCESS_DENIED
     */
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentWriter::GLOSSARY_KEY_COMMENT_EMPTY_MESSAGE
     */
    protected const GLOSSARY_KEY_COMMENT_EMPTY_MESSAGE = 'comment.validation.error.empty_message';

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentThreadWriter::GLOSSARY_KEY_COMMENT_THREAD_ALREADY_EXISTS
     */
    protected const GLOSSARY_KEY_COMMENT_THREAD_ALREADY_EXISTS = 'comment.validation.error.comment_thread_already_exists';

    /**
     * @var \SprykerTest\Zed\Comment\CommentBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var string[]
     */
    protected $commentAvailableTags;

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
    public function testFindCommentThreadByOwnerRetrievesCommentThread(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $this->tester->createComment($commentRequestTransfer);

        // Act
        $commentThreadTransfer = $this->tester->getFacade()->findCommentThreadByOwner($commentRequestTransfer);

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadTransfer->getComments()->offsetGet(0);

        // Assert
        $this->assertNotNull($commentThreadTransfer);
        $this->assertEquals($commentTransfer->getMessage(), $storedCommentTransfer->getMessage());
        $this->assertEquals(
            $commentTransfer->getCustomer()->getIdCustomer(),
            $storedCommentTransfer->getCustomer()->getIdCustomer()
        );
    }

    /**
     * @return void
     */
    public function testFindCommentThreadByOwnerReturnNullResultWhenWrongRequestIsPassed(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        // Act
        $commentThreadTransfer = $this->tester->getFacade()->findCommentThreadByOwner($commentRequestTransfer);

        // Assert
        $this->assertNull($commentThreadTransfer);
    }

    /**
     * @return void
     */
    public function testFindCommentThreadByOwnerThrowsExceptionWithEmptyOwnerId(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setOwnerId(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->findCommentThreadByOwner($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testFindCommentThreadByOwnerThrowsExceptionWithEmptyOwnerType(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setOwnerType(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->findCommentThreadByOwner($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddCommentCreatesNewThreadWithFirstComment(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->addComment($commentRequestTransfer);
        $storedCommentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);

        // Assert
        $this->assertEquals($commentTransfer->getMessage(), $storedCommentTransfer->getMessage());
        $this->assertFalse($storedCommentTransfer->getIsUpdated());
        $this->assertEquals(
            $commentTransfer->getCustomer()->getIdCustomer(),
            $storedCommentTransfer->getCustomer()->getIdCustomer()
        );
    }

    /**
     * @return void
     */
    public function testAddCommentAddsCommentToExistingThread(): void
    {
        // Arrange
        $firstCommentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($firstCommentTransfer);

        $this->tester->createComment($commentRequestTransfer);

        $secondCommentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($secondCommentTransfer)
            ->setOwnerId($commentRequestTransfer->getOwnerId())
            ->setOwnerType($commentRequestTransfer->getOwnerType());

        // Act
        $this->tester->getFacade()->addComment($commentRequestTransfer);
        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($commentRequestTransfer);

        // Assert
        $this->assertCount(2, $commentThreadTransfer->getComments());
        $this->assertEquals(
            $firstCommentTransfer->getMessage(),
            $commentThreadTransfer->getComments()->offsetGet(0)->getMessage()
        );
        $this->assertEquals(
            $secondCommentTransfer->getMessage(),
            $commentThreadTransfer->getComments()->offsetGet(1)->getMessage()
        );
    }

    /**
     * @return void
     */
    public function testAddCommentThrowsExceptionWithEmptyOwnerId(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setOwnerId(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->addComment($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddCommentThrowsExceptionWithEmptyOwnerType(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setOwnerType(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->addComment($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddCommentThrowsExceptionWithEmptyMessage(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->setMessage(null);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->addComment($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testAddCommentThrowsExceptionWithEmptyCustomer(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build();
        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->addComment($commentRequestTransfer);
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
            static::GLOSSARY_KEY_COMMENT_EMPTY_MESSAGE,
            $commentThreadResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testRemoveCommentRemovesExistingComment(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->addCommentTag((new CommentTagBuilder())->build());

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentRequestTransfer->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->removeComment($commentRequestTransfer);
        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($commentRequestTransfer);

        // Assert
        $this->assertTrue($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $commentThreadTransfer->getComments());
    }

    /**
     * @return void
     */
    public function testRemoveCommentThrowsExceptionWithEmptyComment(): void
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
        $this->tester->getFacade()->removeComment($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveCommentThrowsExceptionWithEmptyCustomer(): void
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
        $this->tester->getFacade()->removeComment($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveCommentThrowsExceptionWithEmptyIdCustomer(): void
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
        $this->tester->getFacade()->removeComment($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveCommentWithWrongUuidComment(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer->setUuid(static::FAKE_COMMENT_UUID);

        $commentRequestTransfer->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->removeComment($commentRequestTransfer);

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
    public function testRemoveCommentWithWrongCustomer(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer->setCustomer($this->tester->haveCustomer());

        $commentRequestTransfer->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->removeComment($commentRequestTransfer);

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
    public function testDuplicateCommentThreadCopyExistingCommentThreadToNewOne(): void
    {
        // Arrange
        $firstCommentTransfer = (new CommentBuilder())->build()->setCustomer($this->customerTransfer);
        $secondCommentTransfer = (new CommentBuilder())->build()->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($firstCommentTransfer);

        $this->tester->createComment($commentRequestTransfer);

        $commentRequestTransfer->setComment($secondCommentTransfer);
        $this->tester->getFacade()->addComment($commentRequestTransfer);

        $commentFilterTransfer = (new CommentFilterTransfer())
            ->setOwnerId($commentRequestTransfer->getOwnerId())
            ->setOwnerType($commentRequestTransfer->getOwnerType());

        $newCommentRequestTransfer = (new CommentRequestBuilder())->build();

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()
            ->duplicateCommentThread($commentFilterTransfer, $newCommentRequestTransfer);

        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($newCommentRequestTransfer);

        // Assert
        $this->assertTrue($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertCount(2, $commentThreadTransfer->getComments());
    }

    /**
     * @return void
     */
    public function testDuplicateCommentThreadThrowsExceptionWithEmptyFilterOwnerId(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestBuilder())->build();
        $commentFilterTransfer = (new CommentFilterBuilder())->build()
            ->setOwnerId(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->duplicateCommentThread($commentFilterTransfer, $commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testDuplicateCommentThreadThrowsExceptionWithEmptyFilterOwnerType(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestBuilder())->build();
        $commentFilterTransfer = (new CommentFilterBuilder())->build()
            ->setOwnerType(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->duplicateCommentThread($commentFilterTransfer, $commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testDuplicateCommentThreadThrowsExceptionWithEmptyOwnerId(): void
    {
        // Arrange
        $commentFilterTransfer = (new CommentFilterBuilder())->build();
        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setOwnerId(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->duplicateCommentThread($commentFilterTransfer, $commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testDuplicateCommentThreadThrowsExceptionWithEmptyOwnerType(): void
    {
        // Arrange
        $commentFilterTransfer = (new CommentFilterBuilder())->build();
        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setOwnerType(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->duplicateCommentThread($commentFilterTransfer, $commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testDuplicateCommentThreadCopyThreadToExistingCommentThread(): void
    {
        // Arrange
        $firstCommentTransfer = (new CommentBuilder())->build()->setCustomer($this->customerTransfer);
        $secondCommentTransfer = (new CommentBuilder())->build()->setCustomer($this->customerTransfer);

        $firstCommentRequestTransfer = (new CommentRequestBuilder())->build()->setComment($firstCommentTransfer);
        $secondCommentRequestTransfer = (new CommentRequestBuilder())->build()->setComment($secondCommentTransfer);

        $this->tester->createComment($firstCommentRequestTransfer);
        $this->tester->createComment($secondCommentRequestTransfer);

        $commentFilterTransfer = (new CommentFilterTransfer())
            ->setOwnerId($secondCommentRequestTransfer->getOwnerId())
            ->setOwnerType($secondCommentRequestTransfer->getOwnerType());

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()
            ->duplicateCommentThread($commentFilterTransfer, $secondCommentRequestTransfer);

        // Assert
        $this->assertFalse($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_COMMENT_THREAD_ALREADY_EXISTS,
            $commentThreadResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testDuplicateCommentThreadCopyCommentsWithTagToNewOne(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);

        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setName((new CommentTagBuilder())->build()->getName())
            ->setComment($commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0));

        $this->commentAvailableTags = [$commentTagRequestTransfer->getName()];
        $commentTransfer = $this->getFacadeMock()->addCommentTag($commentTagRequestTransfer)->getComment();

        $commentFilterTransfer = (new CommentFilterTransfer())
            ->setOwnerId($commentRequestTransfer->getOwnerId())
            ->setOwnerType($commentRequestTransfer->getOwnerType());

        $newCommentRequestTransfer = (new CommentRequestBuilder())->build();

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()
            ->duplicateCommentThread($commentFilterTransfer, $newCommentRequestTransfer);

        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($newCommentRequestTransfer);

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadTransfer->getComments()->offsetGet(0);

        // Assert
        $this->assertTrue($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $commentThreadTransfer->getComments());
        $this->assertEquals(
            $commentTransfer->getCommentTags()->offsetGet(0)->getName(),
            $storedCommentTransfer->getCommentTags()->offsetGet(0)->getName()
        );
    }

    /**
     * @return void
     */
    public function testAddCommentTagAddsCommentTagToComment(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);

        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setName((new CommentTagBuilder())->build()->getName())
            ->setComment($commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0));

        $this->commentAvailableTags = [$commentTagRequestTransfer->getName()];

        // Act
        $commentResponseTransfer = $this->getFacadeMock()->addCommentTag($commentTagRequestTransfer);
        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($commentRequestTransfer);

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadTransfer->getComments()->offsetGet(0);

        // Assert
        $this->assertTrue($commentResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $storedCommentTransfer->getCommentTags());
        $this->assertEquals(
            $commentTagRequestTransfer->getName(),
            $storedCommentTransfer->getCommentTags()->offsetGet(0)->getName()
        );
    }

    /**
     * @return void
     */
    public function testAddCommentTagAddsCommentTagToCommentWithTags(): void
    {
        // Arrange
        $firstCommentTagTransfer = (new CommentTagBuilder())->build();
        $secondCommentTagTransfer = (new CommentTagBuilder())->build();

        $this->commentAvailableTags = [
            $firstCommentTagTransfer->getName(),
            $secondCommentTagTransfer->getName(),
        ];

        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);

        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setName($firstCommentTagTransfer->getName())
            ->setComment($commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0));

        $commentTransfer = $this->getFacadeMock()->addCommentTag($commentTagRequestTransfer)->getComment();

        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setName($secondCommentTagTransfer->getName())
            ->setComment($commentTransfer);

        // Act
        $commentResponseTransfer = $this->getFacadeMock()->addCommentTag($commentTagRequestTransfer);
        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($commentRequestTransfer);

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadTransfer->getComments()->offsetGet(0);

        // Assert
        $this->assertTrue($commentResponseTransfer->getIsSuccessful());
        $this->assertCount(2, $storedCommentTransfer->getCommentTags());
        $this->assertEquals(
            $firstCommentTagTransfer->getName(),
            $storedCommentTransfer->getCommentTags()->offsetGet(0)->getName()
        );
        $this->assertEquals(
            $secondCommentTagTransfer->getName(),
            $storedCommentTransfer->getCommentTags()->offsetGet(1)->getName()
        );
    }

    /**
     * @return \Spryker\Zed\Comment\Business\CommentFacadeInterface
     */
    protected function getFacadeMock(): CommentFacadeInterface
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Comment\CommentConfig $commentConfigMock */
        $commentConfigMock = $this->getMockBuilder(CommentConfig::class)
            ->setMethods(['getCommentAvailableTags'])
            ->disableOriginalConstructor()
            ->getMock();

        $commentConfigMock
            ->method('getCommentAvailableTags')
            ->willReturn($this->commentAvailableTags);

        /** @var \Spryker\Zed\Comment\Business\CommentFacade $commentFacade */
        $commentFacade = $this->tester->getFacade();
        $commentFacade->setFactory((new CommentBusinessFactory())->setConfig($commentConfigMock));

        return $commentFacade;
    }
}
