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
 * @group CommentFacadeFindCommentThreadByOwnerTest
 * Add your own group annotations below this line
 */
class CommentFacadeFindCommentThreadByOwnerTest extends Unit
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
}
