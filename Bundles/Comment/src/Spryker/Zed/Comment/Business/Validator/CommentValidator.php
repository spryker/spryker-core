<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Validator;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;

class CommentValidator implements CommentValidatorInterface
{
    /**
     * @var int
     */
    protected const COMMENT_MESSAGE_MIN_LENGTH = 1;

    /**
     * @var int
     */
    protected const COMMENT_MESSAGE_MAX_LENGTH = 5000;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH = 'comment.validation.error.invalid_message_length';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_NOT_FOUND = 'comment.validation.error.comment_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @var list<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentValidatorPluginInterface>
     */
    protected $commentValidatorPlugins;

    /**
     * @var list<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentAuthorValidatorStrategyPluginInterface>
     */
    protected array $commentAuthorValidatorStrategyPlugins;

    /**
     * @param list<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentValidatorPluginInterface> $commentValidatorPlugins
     * @param list<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentAuthorValidatorStrategyPluginInterface> $commentAuthorValidatorStrategyPlugins
     */
    public function __construct(array $commentValidatorPlugins, array $commentAuthorValidatorStrategyPlugins)
    {
        $this->commentValidatorPlugins = $commentValidatorPlugins;
        $this->commentAuthorValidatorStrategyPlugins = $commentAuthorValidatorStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    public function validateCommentRequestOnCreate(CommentRequestTransfer $commentRequestTransfer): CommentValidationResponseTransfer
    {
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer())->setIsSuccessful(true);
        $commentValidationResponseTransfer = $this->validateCommentMessageLength(
            $commentRequestTransfer->getComment(),
            $commentValidationResponseTransfer,
        );

        return $this->executeCommentValidatorPlugins($commentRequestTransfer, $commentValidationResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     * @param \Generated\Shared\Transfer\CommentTransfer|null $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    public function validateCommentRequestOnUpdate(
        CommentRequestTransfer $commentRequestTransfer,
        ?CommentTransfer $commentTransfer
    ): CommentValidationResponseTransfer {
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer())->setIsSuccessful(true);
        $commentValidationResponseTransfer = $this->validateCommentMessageLength(
            $commentRequestTransfer->getComment(),
            $commentValidationResponseTransfer,
        );

        return $this->validateCommentRequest(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
            $commentTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     * @param \Generated\Shared\Transfer\CommentTransfer|null $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    public function validateCommentRequestOnDelete(
        CommentRequestTransfer $commentRequestTransfer,
        ?CommentTransfer $commentTransfer
    ): CommentValidationResponseTransfer {
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer())->setIsSuccessful(true);

        return $this->validateCommentRequest(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
            $commentTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    public function validateCommentAuthor(
        CommentRequestTransfer $commentRequestTransfer
    ): CommentValidationResponseTransfer {
        foreach ($this->commentAuthorValidatorStrategyPlugins as $commentAuthorValidatorStrategyPlugin) {
            if (!$commentAuthorValidatorStrategyPlugin->isApplicable($commentRequestTransfer)) {
                continue;
            }

            return $commentAuthorValidatorStrategyPlugin->validate($commentRequestTransfer);
        }

        $commentRequestTransfer->getCommentOrFail()
            ->requireCustomer()
            ->getCustomer()
            ->requireIdCustomer();

        return (new CommentValidationResponseTransfer())->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     * @param \Generated\Shared\Transfer\CommentValidationResponseTransfer $commentValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    protected function validateCommentMessageLength(
        CommentTransfer $commentTransfer,
        CommentValidationResponseTransfer $commentValidationResponseTransfer
    ): CommentValidationResponseTransfer {
        $messageLength = mb_strlen(trim($commentTransfer->getMessage()));

        if ($messageLength < static::COMMENT_MESSAGE_MIN_LENGTH || $messageLength > static::COMMENT_MESSAGE_MAX_LENGTH) {
            $messageTransfer = $this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_INVALID_MESSAGE_LENGTH);

            return $commentValidationResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($messageTransfer);
        }

        return $commentValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     * @param \Generated\Shared\Transfer\CommentValidationResponseTransfer $commentValidationResponseTransfer
     * @param \Generated\Shared\Transfer\CommentTransfer|null $commentTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    protected function validateCommentRequest(
        CommentRequestTransfer $commentRequestTransfer,
        CommentValidationResponseTransfer $commentValidationResponseTransfer,
        ?CommentTransfer $commentTransfer
    ): CommentValidationResponseTransfer {
        if (!$commentTransfer) {
            $messageTransfer = $this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_NOT_FOUND);

            return $commentValidationResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($messageTransfer);
        }

        if (!$this->isSameCustomer($commentTransfer, $commentRequestTransfer)) {
            $messageTransfer = $this->createMessageTransfer(static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED);
            $commentValidationResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($messageTransfer);
        }

        return $commentValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     * @param \Generated\Shared\Transfer\CommentValidationResponseTransfer $commentValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CommentValidationResponseTransfer
     */
    protected function executeCommentValidatorPlugins(
        CommentRequestTransfer $commentRequestTransfer,
        CommentValidationResponseTransfer $commentValidationResponseTransfer
    ): CommentValidationResponseTransfer {
        foreach ($this->commentValidatorPlugins as $commentValidatorPlugin) {
            if (!$commentValidatorPlugin->isApplicable($commentRequestTransfer)) {
                continue;
            }

            $commentValidationResponseTransfer = $commentValidatorPlugin->validate(
                $commentRequestTransfer,
                $commentValidationResponseTransfer,
            );
        }

        return $commentValidationResponseTransfer;
    }

    /**
     * @deprecated For BC only. Use {@link \Spryker\Zed\Comment\Communication\Plugin\Comment\CustomerCommentAuthorValidationStrategyPlugin} instead.
     *
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return bool
     */
    protected function isSameCustomer(CommentTransfer $commentTransfer, CommentRequestTransfer $commentRequestTransfer): bool
    {
        if (!$commentTransfer->getCustomer()) {
            return true;
        }

        return $commentRequestTransfer->getCommentOrFail()->getCustomer()
            && $commentTransfer->getCustomerOrFail()->getIdCustomer() === $commentRequestTransfer->getCommentOrFail()->getCustomerOrFail()->getIdCustomer();
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(string $message): MessageTransfer
    {
        return (new MessageTransfer())->setValue($message);
    }
}
