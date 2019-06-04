<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Comment\Business\CommentFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\DataBuilder\CommentRequestBuilder;
use Generated\Shared\DataBuilder\CommentTagBuilder;
use Generated\Shared\Transfer\CommentRequestTransfer;
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
    public function testAddCommentCreatesNewThreadWithNewTag(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->addCommentTag((new CommentTagBuilder())->build());

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->addComment($commentRequestTransfer);
        $storedCommentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);

        // Assert
        $this->assertCount(1, $storedCommentTransfer->getTags());
        $this->assertEquals(
            $commentTransfer->getTags()->offsetGet(0)->getName(),
            $storedCommentTransfer->getTags()->offsetGet(0)->getName()
        );
    }

    /**
     * @return void
     */
    public function testAddCommentCreatesNewThreadWithExistingTag(): void
    {
        // Arrange
        $commentTagTransfer = (new CommentTagBuilder())->build();

        $firstCommentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->addCommentTag($commentTagTransfer);

        $firstCommentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($firstCommentTransfer);

        $this->tester->createComment($firstCommentRequestTransfer);

        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->addCommentTag($commentTagTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->addComment($commentRequestTransfer);
        $storedCommentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);

        // Assert
        $this->assertCount(1, $storedCommentTransfer->getTags());
    }

    /**
     * @return void
     */
    public function testAddCommentCreatesNewThreadWithTwoTags(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->addCommentTag((new CommentTagBuilder())->build())
            ->addCommentTag((new CommentTagBuilder())->build());

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->addComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);

        // Assert
        $this->assertCount(2, $storedCommentTransfer->getTags());
        $this->assertFalse($storedCommentTransfer->getIsUpdated());
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
    public function testUpdateCommentAddsCommentTagsToCommentWithoutTags(): void
    {
        // Arrange
        $firstCommentTagTransfer = (new CommentTagBuilder())->build();
        $secondCommentTagTransfer = (new CommentTagBuilder())->build();

        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer
            ->addCommentTag($firstCommentTagTransfer)
            ->addCommentTag($secondCommentTagTransfer);

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
        $this->assertCount(2, $storedCommentTransfer->getTags());
        $this->assertEquals($commentTransfer->getMessage(), $storedCommentTransfer->getMessage());
    }

    /**
     * @return void
     */
    public function testUpdateCommentRemovesCommentTagsFromComment(): void
    {
        // Arrange
        $firstCommentTagTransfer = (new CommentTagBuilder())->build();
        $secondCommentTagTransfer = (new CommentTagBuilder())->build();

        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->addCommentTag($firstCommentTagTransfer)
            ->addCommentTag($secondCommentTagTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer->setTags(new ArrayObject());

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
        $this->assertCount(0, $storedCommentTransfer->getTags());
    }

    /**
     * @return void
     */
    public function testUpdateCommentAdjustsCommentTagsFromComment(): void
    {
        // Arrange
        $firstCommentTagTransfer = (new CommentTagBuilder())->build();
        $secondCommentTagTransfer = (new CommentTagBuilder())->build();
        $thirdCommentTagTransfer = (new CommentTagBuilder())->build();

        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->addCommentTag($firstCommentTagTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer->setTags(new ArrayObject())
            ->addCommentTag($secondCommentTagTransfer)
            ->addCommentTag($thirdCommentTagTransfer);

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
        $this->assertCount(2, $storedCommentTransfer->getTags());
        $this->assertEquals(
            $secondCommentTagTransfer->getName(),
            $storedCommentTransfer->getTags()->offsetGet(0)->getName()
        );
        $this->assertEquals(
            $thirdCommentTagTransfer->getName(),
            $storedCommentTransfer->getTags()->offsetGet(1)->getName()
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
    public function testUpdateCommentTagsRemovesCommentTagsFromComment(): void
    {
        // Arrange
        $firstCommentTagTransfer = (new CommentTagBuilder())->build();
        $secondCommentTagTransfer = (new CommentTagBuilder())->build();

        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->addCommentTag($firstCommentTagTransfer)
            ->addCommentTag($secondCommentTagTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer->setTags(new ArrayObject());

        $commentRequestTransfer->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->updateCommentTags($commentRequestTransfer);
        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($commentRequestTransfer);

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadTransfer->getComments()->offsetGet(0);

        // Assert
        $this->assertTrue($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $storedCommentTransfer->getTags());
    }

    /**
     * @return void
     */
    public function testUpdateCommentUpdatesCommentTagsByOwnerCustomer(): void
    {
        // Arrange
        $firstCommentTagTransfer = (new CommentTagBuilder())->build();
        $secondCommentTagTransfer = (new CommentTagBuilder())->build();

        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->addCommentTag($firstCommentTagTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer
            ->setTags(new ArrayObject())
            ->addCommentTag($secondCommentTagTransfer);

        $commentRequestTransfer->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->updateCommentTags($commentRequestTransfer);
        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($commentRequestTransfer);

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadTransfer->getComments()->offsetGet(0);

        // Assert
        $this->assertTrue($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $storedCommentTransfer->getTags());
        $this->assertEquals(
            $secondCommentTagTransfer->getName(),
            $storedCommentTransfer->getTags()->offsetGet(0)->getName()
        );
    }

    /**
     * @return void
     */
    public function testUpdateCommentUpdatesCommentTagsByAnotherCustomer(): void
    {
        // Arrange
        $firstCommentTagTransfer = (new CommentTagBuilder())->build();
        $secondCommentTagTransfer = (new CommentTagBuilder())->build();

        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->addCommentTag($firstCommentTagTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0);
        $commentTransfer->setTags(new ArrayObject())
            ->setCustomer($this->tester->haveCustomer())
            ->addCommentTag($secondCommentTagTransfer);

        $commentRequestTransfer->setComment($commentTransfer);

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->updateCommentTags($commentRequestTransfer);
        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($commentRequestTransfer);

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadTransfer->getComments()->offsetGet(0);

        // Assert
        $this->assertTrue($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $storedCommentTransfer->getTags());
        $this->assertEquals(
            $secondCommentTagTransfer->getName(),
            $storedCommentTransfer->getTags()->offsetGet(0)->getName()
        );
    }
}
