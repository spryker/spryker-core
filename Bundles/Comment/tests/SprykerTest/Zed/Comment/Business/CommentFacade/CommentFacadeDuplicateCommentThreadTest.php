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
 * @group CommentFacadeDuplicateCommentThreadTest
 * Add your own group annotations below this line
 */
class CommentFacadeDuplicateCommentThreadTest extends Unit
{
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
    protected $availableCommentTags = [];

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

        $this->availableCommentTags = [$commentTagRequestTransfer->getName()];
        $commentTransfer = $this->getFacadeMock()
            ->addCommentTag($commentTagRequestTransfer)
            ->getCommentThread()
            ->getComments()
            ->offsetGet(0);

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
     * @return \Spryker\Zed\Comment\Business\CommentFacadeInterface
     */
    protected function getFacadeMock(): CommentFacadeInterface
    {
        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Comment\CommentConfig $commentConfigMock */
        $commentConfigMock = $this->getMockBuilder(CommentConfig::class)
            ->setMethods(['getAvailableCommentTags'])
            ->disableOriginalConstructor()
            ->getMock();

        $commentConfigMock
            ->method('getAvailableCommentTags')
            ->willReturn($this->availableCommentTags);

        /** @var \Spryker\Zed\Comment\Business\CommentFacade $commentFacade */
        $commentFacade = $this->tester->getFacade();
        $commentFacade->setFactory((new CommentBusinessFactory())->setConfig($commentConfigMock));

        return $commentFacade;
    }
}
