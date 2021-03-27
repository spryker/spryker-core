<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Communication\Plugin\Quote;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentsRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuotePostExpanderPluginInterface;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuotePreExpanderPluginInterface;

/**
 * @method \Spryker\Zed\Comment\Business\CommentFacadeInterface getFacade()
 * @method \Spryker\Zed\Comment\Communication\CommentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Comment\CommentConfig getConfig()
 */
class CommentThreadQuoteExpanderPlugin extends AbstractPlugin implements QuoteExpanderPluginInterface, QuotePreExpanderPluginInterface, QuotePostExpanderPluginInterface
{
    protected const COMMENT_THREAD_QUOTE_OWNER_TYPE = 'quote';

    /**
     * @var int[]
     */
    protected $quoteIds = [];

    /**
     * @var \Generated\Shared\Transfer\CommentThreadTransfer[]
     */
    protected $commentsByIdQuote = [];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function preExpand(QuoteTransfer $quoteTransfer): void
    {
        $this->quoteIds[] = $quoteTransfer->getIdQuoteOrFail();
    }

    /**
     * {@inheritDoc}
     * - Expands quote transfer with CommentThread.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expand(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $this->preloadComments();

        $commentThread = $this->getCommentThreadByIdQuote($quoteTransfer->getIdQuote());

        $quoteTransfer->setCommentThread($commentThread);

        return $quoteTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function postExpand(): void
    {
        $this->quoteIds = [];
        $this->commentsByIdQuote = [];
    }

    /**
     * @return void
     */
    protected function preloadComments(): void
    {
        if ($this->commentsByIdQuote !== []) {
            return;
        }

        $commentRequestTransfer = (new CommentsRequestTransfer())
            ->setOwnerIds($this->quoteIds)
            ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE);

        $this->commentsByIdQuote = $this->getFacade()->getCommentThreads($commentRequestTransfer);
    }

    /**
     * @param int $idQuote
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    protected function getCommentThreadByIdQuote(int $idQuote): ?CommentThreadTransfer
    {
        if (!isset($this->commentsByIdQuote[$idQuote])) {
            $commentRequestTransfer = (new CommentRequestTransfer())
                ->setOwnerId($idQuote)
                ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE);

            $this->commentsByIdQuote[$idQuote] = $this->getFacade()->findCommentThreadByOwner($commentRequestTransfer);
        }

        return $this->commentsByIdQuote[$idQuote];
    }
}
