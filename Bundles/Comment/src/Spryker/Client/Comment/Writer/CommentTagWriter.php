<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Comment\Writer;

use Generated\Shared\Transfer\CommentTagRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Client\Comment\CommentConfig;
use Spryker\Client\Comment\Zed\CommentStubInterface;

class CommentTagWriter implements CommentTagWriterInterface
{
    protected const GLOSSARY_KEY_COMMENT_TAG_NOT_AVAILABLE = 'comment.validation.error.comment_tag_not_available';

    /**
     * @var \Spryker\Client\Comment\Zed\CommentStubInterface
     */
    protected $commentStub;

    /**
     * @var \Spryker\Client\Comment\CommentConfig
     */
    protected $commentConfig;

    /**
     * @param \Spryker\Client\Comment\Zed\CommentStubInterface $commentStub
     * @param \Spryker\Client\Comment\CommentConfig $commentConfig
     */
    public function __construct(CommentStubInterface $commentStub, CommentConfig $commentConfig)
    {
        $this->commentStub = $commentStub;
        $this->commentConfig = $commentConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function addCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer
    {
        if (!$this->isCommentTagAvailable($commentTagRequestTransfer)) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_TAG_NOT_AVAILABLE);
        }

        return $this->commentStub->addCommentTag($commentTagRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    public function removeCommentTag(CommentTagRequestTransfer $commentTagRequestTransfer): CommentThreadResponseTransfer
    {
        if (!$this->isCommentTagAvailable($commentTagRequestTransfer)) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_COMMENT_TAG_NOT_AVAILABLE);
        }

        return $this->commentStub->removeCommentTag($commentTagRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTagRequestTransfer $commentTagRequestTransfer
     *
     * @return bool
     */
    protected function isCommentTagAvailable(CommentTagRequestTransfer $commentTagRequestTransfer): bool
    {
        return in_array($commentTagRequestTransfer->getName(), $this->commentConfig->getAvailableCommentTags(), true);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CommentThreadResponseTransfer
     */
    protected function createErrorResponse(string $message): CommentThreadResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new CommentThreadResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
