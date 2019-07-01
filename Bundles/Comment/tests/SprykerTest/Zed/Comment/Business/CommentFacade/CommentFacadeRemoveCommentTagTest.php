<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Comment\Business\CommentFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\DataBuilder\CommentRequestBuilder;
use Generated\Shared\DataBuilder\CommentTagBuilder;
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
 * @group CommentFacadeRemoveCommentTagTest
 * Add your own group annotations below this line
 */
class CommentFacadeRemoveCommentTagTest extends Unit
{
    protected const FAKE_COMMENT_UUID = 'FAKE_COMMENT_UUID';

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentWriter::GLOSSARY_KEY_COMMENT_NOT_FOUND
     */
    protected const GLOSSARY_KEY_COMMENT_NOT_FOUND = 'comment.validation.error.comment_not_found';

    /**
     * @uses \Spryker\Zed\Comment\Business\Writer\CommentTagWriter::GLOSSARY_KEY_COMMENT_TAG_NOT_AVAILABLE
     */
    protected const GLOSSARY_KEY_COMMENT_TAG_NOT_AVAILABLE = 'comment.validation.error.comment_tag_not_available';

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
    public function testRemoveCommentTagRemovesCommentTagFromComment(): void
    {
        // Arrange
        $commentTagTransfer = (new CommentTagBuilder())->build();
        $this->availableCommentTags = [$commentTagTransfer->getName()];

        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer);

        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        $commentThreadResponseTransfer = $this->tester->createComment($commentRequestTransfer);

        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setName($commentTagTransfer->getName())
            ->setComment($commentThreadResponseTransfer->getCommentThread()->getComments()->offsetGet(0));

        $this->getFacadeMock()->addCommentTag($commentTagRequestTransfer);

        // Act
        $commentThreadResponseTransfer = $this->getFacadeMock()->removeCommentTag($commentTagRequestTransfer);
        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($commentRequestTransfer);

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadTransfer->getComments()->offsetGet(0);

        // Assert
        $this->assertTrue($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $storedCommentTransfer->getCommentTags());
    }

    /**
     * @return void
     */
    public function testRemoveCommentTagThrowsExceptionWhenCommentTagNameNotProvided(): void
    {
        // Arrange
        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setName(null)
            ->setComment((new CommentBuilder())->build());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->getFacadeMock()->removeCommentTag($commentTagRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveCommentTagThrowsExceptionWhenCommentNotProvided(): void
    {
        // Arrange
        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setName((new CommentTagBuilder())->build()->getName())
            ->setComment(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->getFacadeMock()->removeCommentTag($commentTagRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveCommentTagThrowsExceptionWhenCommentUuidNotProvided(): void
    {
        // Arrange
        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setName((new CommentTagBuilder())->build()->getName())
            ->setComment((new CommentBuilder())->build());

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->getFacadeMock()->removeCommentTag($commentTagRequestTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveCommentTagRemovesNotAvailableTagFromComment(): void
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

        // Act
        $commentThreadResponseTransfer = $this->getFacadeMock()->removeCommentTag($commentTagRequestTransfer);

        // Assert
        $this->assertFalse($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertEquals(
            static::GLOSSARY_KEY_COMMENT_TAG_NOT_AVAILABLE,
            $commentThreadResponseTransfer->getMessages()[0]->getValue()
        );
    }

    /**
     * @return void
     */
    public function testRemoveCommentTagRemovesTagFromCommentWithWrongUuid(): void
    {
        // Arrange
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->customerTransfer)
            ->setUuid(static::FAKE_COMMENT_UUID);

        $commentTagRequestTransfer = (new CommentTagRequestTransfer())
            ->setName((new CommentTagBuilder())->build()->getName())
            ->setComment($commentTransfer);

        $this->availableCommentTags = [$commentTagRequestTransfer->getName()];

        // Act
        $commentThreadResponseTransfer = $this->getFacadeMock()->removeCommentTag($commentTagRequestTransfer);

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
    public function testRemoveCommentTagRemovesUnsavedTagFromComment(): void
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

        // Act
        $commentThreadResponseTransfer = $this->getFacadeMock()->removeCommentTag($commentTagRequestTransfer);
        $commentThreadTransfer = $this->tester
            ->getFacade()
            ->findCommentThreadByOwner($commentRequestTransfer);

        /** @var \Generated\Shared\Transfer\CommentTransfer $storedCommentTransfer */
        $storedCommentTransfer = $commentThreadTransfer->getComments()->offsetGet(0);

        // Assert
        $this->assertTrue($commentThreadResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $storedCommentTransfer->getCommentTags());
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
