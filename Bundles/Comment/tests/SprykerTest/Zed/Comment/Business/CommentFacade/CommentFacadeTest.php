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
    public function testFindCommentThreadRetrievesCommentThread(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $this->tester->createCommentThread($commentRequestTransfer);

        // Act
        $commentThreadTransfer = $this->tester->getFacade()->findCommentThread($commentRequestTransfer);

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
    public function testFindCommentThreadReturnNullResultWhenWrongRequestIsPassed(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        // Act
        $commentThreadTransfer = $this->tester->getFacade()->findCommentThread($commentRequestTransfer);

        // Assert
        $this->assertNull($commentThreadTransfer);
    }

    /**
     * @return void
     */
    public function testFindCommentThreadThrowsExceptionWithEmptyOwnerId(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setOwnerId(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->findCommentThread($commentRequestTransfer);
    }

    /**
     * @return void
     */
    public function testFindCommentThreadThrowsExceptionWithEmptyOwnerType(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setOwnerType(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->findCommentThread($commentRequestTransfer);
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

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadResponseTransfer->getCommentThread()
            ->getComments()
            ->offsetGet(0);

        // Assert
        $this->assertCount(1, $commentThreadResponseTransfer->getCommentThread()->getComments());
        $this->assertEquals($commentTransfer->getMessage(), $storedCommentTransfer->getMessage());
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

        $commentThreadTransfer = $this->tester->createCommentThread($commentRequestTransfer);

        $secondCommentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($secondCommentTransfer)
            ->setOwnerId($commentThreadTransfer->getOwnerId())
            ->setOwnerType($commentThreadTransfer->getOwnerType());

        // Act
        $commentThreadResponseTransfer = $this->tester->getFacade()->addComment($commentRequestTransfer);

        // Assert
        $this->assertCount(2, $commentThreadResponseTransfer->getCommentThread()->getComments());
        $this->assertEquals(
            $firstCommentTransfer->getMessage(),
            $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0)->getMessage()
        );
        $this->assertEquals(
            $secondCommentTransfer->getMessage(),
            $commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(1)->getMessage()
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
}
