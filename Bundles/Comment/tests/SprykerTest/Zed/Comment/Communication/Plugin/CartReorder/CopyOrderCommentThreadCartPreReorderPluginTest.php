<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Comment\Communication\Plugin\CartReorder;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\DataBuilder\CommentRequestBuilder;
use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Comment\Persistence\SpyCommentThreadQuery;
use Spryker\Zed\Comment\Communication\Plugin\CartReorder\CopyOrderCommentThreadCartPreReorderPlugin;
use SprykerTest\Zed\Comment\CommentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Comment
 * @group Communication
 * @group Plugin
 * @group CartReorder
 * @group CopyOrderCommentThreadCartPreReorderPluginTest
 * Add your own group annotations below this line
 */
class CopyOrderCommentThreadCartPreReorderPluginTest extends Unit
{
    /**
     * @var int
     */
    protected const ID_QUOTE = 12345;

    /**
     * @var \SprykerTest\Zed\Comment\CommentCommunicationTester
     */
    protected CommentCommunicationTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureDatabaseTableIsEmpty(SpyCommentThreadQuery::create());
    }

    /**
     * @return void
     */
    public function testShouldCopyCommentThreadFromOrderToQuote(): void
    {
        // Arrange
        $commentThreadTransfer = $this->createCommentThread();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(true);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrder((new OrderTransfer())->setCommentThread($commentThreadTransfer))
            ->setQuote((new QuoteTransfer())->setIdQuote(static::ID_QUOTE));

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = (new CopyOrderCommentThreadCartPreReorderPlugin())->preReorder(
            $cartReorderRequestTransfer,
            $cartReorderTransfer,
        );

        // Assert
        $quoteCommentThead = $updatedSalesOrderAmendmentItemCollectionTransfer->getQuote()->getCommentThread();

        $this->assertSame(static::ID_QUOTE, $quoteCommentThead->getOwnerId());
        $this->assertSame('quote', $quoteCommentThead->getOwnerType());
        $this->assertSame($commentThreadTransfer->getComments()->count(), $quoteCommentThead->getComments()->count());
    }

    /**
     * @return void
     */
    public function testShouldNotCopyCommentThreadFromOrderToQuoteWithoutAmendment(): void
    {
        // Arrange
        $commentThreadTransfer = $this->createCommentThread();
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(false);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrder((new OrderTransfer())->setCommentThread($commentThreadTransfer))
            ->setQuote((new QuoteTransfer())->setIdQuote(static::ID_QUOTE));

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = (new CopyOrderCommentThreadCartPreReorderPlugin())->preReorder(
            $cartReorderRequestTransfer,
            $cartReorderTransfer,
        );

        // Assert
        $this->assertNull($updatedSalesOrderAmendmentItemCollectionTransfer->getQuote()->getCommentThread());
    }

    /**
     * @return void
     */
    public function testShouldNotCopyCommentThreadFromOrderToQuoteWithoutComments(): void
    {
        // Arrange
        $commentThreadTransfer = $this->createCommentThread();
        $commentThreadTransfer->setComments(new ArrayObject([]));
        $cartReorderRequestTransfer = (new CartReorderRequestTransfer())->setIsAmendment(true);
        $cartReorderTransfer = (new CartReorderTransfer())
            ->setOrder((new OrderTransfer())->setCommentThread($commentThreadTransfer))
            ->setQuote((new QuoteTransfer())->setIdQuote(static::ID_QUOTE));

        // Act
        $updatedSalesOrderAmendmentItemCollectionTransfer = (new CopyOrderCommentThreadCartPreReorderPlugin())->preReorder(
            $cartReorderRequestTransfer,
            $cartReorderTransfer,
        );

        // Assert
        $this->assertNull($updatedSalesOrderAmendmentItemCollectionTransfer->getQuote()->getCommentThread());
    }

    /**
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    protected function createCommentThread(): CommentThreadTransfer
    {
        $commentTransfer = (new CommentBuilder())->build()
            ->setCustomer($this->tester->haveCustomer());
        $commentRequestTransfer = (new CommentRequestBuilder())->build()
            ->setComment($commentTransfer);

        return $this->tester->createComment($commentRequestTransfer)->getCommentThread();
    }
}
