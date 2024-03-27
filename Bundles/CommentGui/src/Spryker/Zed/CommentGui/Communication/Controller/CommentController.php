<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentGui\Communication\Controller;

use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadResponseTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 * @method \Spryker\Zed\CommentGui\Communication\CommentGuiCommunicationFactory getFactory()
 */
class CommentController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_INVALID_MESSAGE_LENGTH = 'Invalid message length.';

    /**
     * @var string
     */
    protected const MESSAGE_UNEXPECTED_ERROR = 'Unexpected error occurred.';

    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_ERROR = 'Something went wrong. Sorry.';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_ADD_COMMENT_FORM = 'add-comment-form';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_UPDATE_COMMENT_FORM = 'update-comment-form';

    /**
     * @var string
     */
    protected const REQUEST_FIELD_TOKEN = '_token';

    /**
     * @var string
     */
    protected const REQUEST_FIELD_RETURN_URL = 'returnUrl';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request): RedirectResponse
    {
        return $this->executeAddAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request): RedirectResponse
    {
        return $this->executeUpdateAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request): RedirectResponse
    {
        return $this->executeRemoveAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeAddAction(Request $request): RedirectResponse
    {
        $returnUrl = (string)$request->request->get(static::REQUEST_FIELD_RETURN_URL);
        $tokenValue = (string)$request->get(static::REQUEST_FIELD_TOKEN);

        if (!$this->validateCsrfToken(static::CSRF_TOKEN_ID_ADD_COMMENT_FORM, $tokenValue)) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_ERROR);

            return $this->redirectResponseExternal($returnUrl);
        }

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->fromArray($request->request->all(), true)
            ->setComment($this->createCommentTransferFromRequest($request));

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentFacade()
            ->addComment($commentRequestTransfer);

        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->redirectResponseExternal($returnUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeUpdateAction(Request $request): RedirectResponse
    {
        $returnUrl = (string)$request->request->get(static::REQUEST_FIELD_RETURN_URL);
        $tokenValue = (string)$request->get(static::REQUEST_FIELD_TOKEN);

        if (!$this->validateCsrfToken(static::CSRF_TOKEN_ID_UPDATE_COMMENT_FORM, $tokenValue)) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_ERROR);

            return $this->redirectResponseExternal($returnUrl);
        }

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($this->createCommentTransferFromRequest($request));

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentFacade()
            ->updateComment($commentRequestTransfer);

        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->redirectResponseExternal($returnUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeRemoveAction(Request $request): RedirectResponse
    {
        $returnUrl = (string)$request->request->get(static::REQUEST_FIELD_RETURN_URL);
        $tokenValue = (string)$request->get(static::REQUEST_FIELD_TOKEN);

        if (!$this->validateCsrfToken(static::CSRF_TOKEN_ID_UPDATE_COMMENT_FORM, $tokenValue)) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_ERROR);

            return $this->redirectResponseExternal($returnUrl);
        }

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($this->createCommentTransferFromRequest($request));

        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentFacade()
            ->removeComment($commentRequestTransfer);

        $this->handleResponseMessages($commentThreadResponseTransfer);

        return $this->redirectResponseExternal($returnUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function createCommentTransferFromRequest(Request $request): CommentTransfer
    {
        $userTransfer = $this->getFactory()
            ->getUserFacade()
            ->getCurrentUser();

        return (new CommentTransfer())
            ->fromArray($request->request->all(), true)
            ->setFkUser($userTransfer->getIdUserOrFail());
    }

    /**
     * @param string $tokenId
     * @param string $value
     *
     * @return bool
     */
    protected function validateCsrfToken(string $tokenId, string $value): bool
    {
        $csrfToken = new CsrfToken($tokenId, $value);

        return $this->getFactory()->getCsrfTokenManager()->isTokenValid($csrfToken);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadResponseTransfer $commentThreadResponseTransfer
     *
     * @return void
     */
    protected function handleResponseMessages(CommentThreadResponseTransfer $commentThreadResponseTransfer): void
    {
        if ($commentThreadResponseTransfer->getIsSuccessful()) {
            return;
        }

        foreach ($commentThreadResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValueOrFail());
        }
    }
}
