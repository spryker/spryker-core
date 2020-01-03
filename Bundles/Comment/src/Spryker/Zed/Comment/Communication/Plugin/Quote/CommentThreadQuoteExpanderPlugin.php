<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Communication\Plugin\Quote;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface;

/**
 * @method \Spryker\Zed\Comment\Business\CommentFacadeInterface getFacade()
 * @method \Spryker\Zed\Comment\Communication\CommentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Comment\CommentConfig getConfig()
 */
class CommentThreadQuoteExpanderPlugin extends AbstractPlugin implements QuoteExpanderPluginInterface
{
    protected const COMMENT_THREAD_QUOTE_OWNER_TYPE = 'quote';

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
        $quoteTransfer->requireIdQuote();

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE);

        $quoteTransfer->setCommentThread($this->getFacade()->findCommentThreadByOwner($commentRequestTransfer));

        return $quoteTransfer;
    }
}
